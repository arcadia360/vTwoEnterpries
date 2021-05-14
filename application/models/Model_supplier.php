<?php

class Model_supplier extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_grn');
    }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('supplier', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {

            $this->db->trans_start();
            $sql = "SELECT intSupplierID, vcSupplierName, vcAddress, vcContactNo, dtCreatedDate, decCreditLimit, decAvailableCredit, IsActive, intUserID, rv FROM supplier WHERE intSupplierID = ? ";
            $query = $this->db->query($sql, array($id));
            if ($query->num_rows()) {
                $this->db->insert('supplier_his', $query->row_array());
                $insert_id = $this->db->insert_id();
                $this->db->where('intSupplier_hisID', $insert_id);
                $update = $this->db->update('supplier_his', array('intEnteredBy' => $this->session->userdata('user_id')));

                $this->db->where('intSupplierID', $id);
                $update = $this->db->update('supplier', $data);

                $this->db->trans_complete();
                return ($update == true) ? true : false;
            }
        }
    }

    /* get the Supplier data */
    public function getSupplierData($id = null)
    {
        if ($id) {
            $sql = "SELECT intSupplierID,vcSupplierName,vcAddress,vcContactNo,decCreditLimit,decAvailableCredit,rv FROM supplier WHERE intSupplierID = ? AND IsActive = 1";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT intSupplierID,vcSupplierName,vcAddress,vcContactNo,decCreditLimit,decAvailableCredit,rv FROM supplier WHERE IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function remove($id)
    {
        if ($id) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intSupplierID', $id);
            $delete = $this->db->update('supplier', $data);
            return ($delete == true) ? true : false;
        }
    }

    public function chkexists($id = null)
    {
        if ($id) {
            $sql = "SELECT EXISTS(SELECT intSupplierID  FROM grnheader WHERE intSupplierID = ?) AS value";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
    }

    public function chkRv($id = null)
    {
        if ($id) {
            $sql = "SELECT rv FROM `supplier` WHERE intSupplierID = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }
    }

    //-----------------------------------
    // Supplier Credit Settlement
    //-----------------------------------

    public function getSupplierWiseInvoiceAndGRNno($supplierID)
    {
        if ($supplierID) {
            $sql = "SELECT G.intGRNHeaderID, CONCAT(G.vcInvoiceNo,' ( ', G.vcGRNNo,' ) ') AS vcGRNNo , G.intSupplierID  , SUM(IFNULL(SD.decPaidAmount,0)) AS decPaidAmount ,G.decGrandTotal
            FROM grnheader AS G
            LEFT OUTER JOIN suppliersettlementdetail AS SD ON G.intGRNHeaderID = SD.intGRNHeaderID
            WHERE  G.intSupplierID = ? AND  G.intPaymentTypeID = 2 AND G.intApprovedBy is not null AND G.intRejectedBy is null
            GROUP BY 
            G.intGRNHeaderID
            HAVING 
            G.decGrandTotal > SUM(IFNULL(SD.decPaidAmount,0))";
            $query = $this->db->query($sql, array($supplierID));
            return $query->result_array();
        }
    }

    public function getGRNPaymentDetails($GRNHeaderID)
    {
        $sql = "
        SELECT G.intGRNHeaderID,
        SUM(IFNULL(SU.decPaidAmount,0)) AS decPaidAmount,
        G.decGrandTotal ,REPLACE(G.rv,' ','-') as rv
        FROM 
        grnheader AS G
        LEFT OUTER JOIN suppliersettlementdetail AS SU ON G.intGRNHeaderID = SU.intGRNHeaderID
        WHERE G.intGRNHeaderID = ? AND G.intPaymentTypeID = 2";

        $query = $this->db->query($sql, array($GRNHeaderID));
        return $query->row_array();
    }

    public function SaveSupplierCreditSettlement()
    {
        $this->db->trans_begin();
        $anotherUserAccess = false;

        // $query = $this->db->query("SELECT fnGenerateSupplierSettlementNo() AS SupplierSettlementNo");
        // $ret = $query->row();
        // $SupplierSettlementNo = $ret->SupplierSettlementNo;

        $response = array();

        $SupplierSettlementNo = "SS-001";

        $cmbPayMode = $this->input->post('cmbPayMode');

        $data = array(
            'vcSupplierSettlementNo' =>  $SupplierSettlementNo,
            'intSupplierID' => $this->input->post('cmbsupplier'),
            'decAmount' => $this->input->post('txtAmount'),
            'intPayModeID' =>  $this->input->post('cmbPayMode'),
            'dtPaidDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('dtSettlementDate')))),
            'intUserID' =>  $this->session->userdata('user_id'),
            'vcChequeNo' => $cmbPayMode == 1 ? NULL : $this->input->post('txtChequeNo'),
            'intBankID' =>  $cmbPayMode == 1 ? NULL : $this->input->post('cmbBank'),
            'dtPDDate' => $cmbPayMode == 1 ? NULL : date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('dtPDDate')))),
            'vcRemark' => $this->input->post('txtRemark') == "" ? NULL : $this->input->post('txtRemark'),
        );


        $this->db->insert('suppliersettlementheader', $data);
        $SupplierSettlementHeaderID = $this->db->insert_id();

        $grn_count = count($this->input->post('GRNHeaderID'));

        for ($i = 0; $i < $grn_count; $i++) {

            $currentRV = $this->model_grn->getGRNHeaderData($this->input->post('GRNHeaderID')[$i], NULL, NULL, NULL);
            $previousRV =  $this->input->post('Rv')[$i];


            if ($currentRV['rv'] != $previousRV) {
                $anotherUserAccess = true;
            }

            $items = array(
                'intSupplierSettlementHeaderID' => $SupplierSettlementHeaderID,
                'intGRNHeaderID' => $this->input->post('GRNHeaderID')[$i],
                'decPaidAmount' => $this->input->post('txtPayAmount')[$i]
            );
            $insertDetails = $this->db->insert('suppliersettlementdetail', $items);
        }

        // if ($cmbPayMode == 1) //Cash
        // {
        $sql = "UPDATE supplier AS S
            SET S.decAvailableCredit = (S.decAvailableCredit + " . $this->input->post('txtAmount') . ")
            WHERE S.intSupplierID = ?";
        // }

        $this->db->query($sql, array($this->input->post('cmbsupplier')));


        if ($anotherUserAccess == true) {
            $response['success'] = false;
            $response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
            $this->db->trans_rollback();
            // return $response;
            // } else if ($exceedStockQty == true) {
            //     $response['success'] = false;
            //     $response['messages'] = 'Stock quantity over exceeds error, please refresh the page and try again !';
            //     $this->db->trans_rollback();
            // } else if ($exceedCreditLimit == true) {
            //     $response['success'] = false;
            //     $response['messages'] = 'You cannot exceed cutomer credit limit !';
            //     $this->db->trans_rollback();
        } else {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['success'] = false;
                $response['messages'] = 'Error in the database while create the issue details';
            } else {

                $this->db->trans_commit();
                $response['success'] = true;
                $response['messages'] = 'Succesfully created !';
            }
        }

        return $response;
    }

    //-----------------------------------
    // View Supplier Credit Settlement
    //-----------------------------------

    public function GetSupplierCreditSettlementHeaderData($SupplierSettlementID = null, $PayModeID = null, $SupplierID = null, $FromDate = null, $ToDate = null)
    {
        if ($SupplierSettlementID) {
            $sql = "SELECT 
                        SS.intSupplierSettlementHeaderID,
                        SS.vcSupplierSettlementNo,
                        S.vcSupplierName,
                        P.vcPayMode,
                        SS.decAmount,
                        CAST(SS.dtPaidDate AS DATE) AS dtPaidDate,
                        U.vcFullName,
                        SS.dtCreatedDate,
                        IFNULL(B.vcBankName,'N/A') AS vcBankName,
                        IFNULL(SS.vcChequeNo,'N/A') AS vcChequeNo,
                        IFNULL(SS.dtPDDate,'N/A') AS dtPDDate,
                        IFNULL(SS.vcRemark,'N/A') AS vcRemark
                FROM suppliersettlementheader AS SS
                INNER JOIN supplier AS S ON SS.intSupplierID = S.intSupplierID
                INNER JOIN paymode AS P ON SS.intPayModeID = P.intPayModeID
                INNER JOIN user AS U ON SS.intUserID = U.intUserID
                LEFT OUTER JOIN bank AS B ON SS.intBankID = B.intBankID
                WHERE SS.intSupplierSettlementHeaderID = ?";

            $query = $this->db->query($sql, array($SupplierSettlementID));
            return $query->row_array();
        }


        $sql = " SELECT 
                SS.intSupplierSettlementHeaderID,
                SS.vcSupplierSettlementNo,
                S.vcSupplierName,
                P.vcPayMode,
                SS.decAmount,
                CAST(SS.dtPaidDate AS DATE) AS dtPaidDate,
                U.vcFullName,
                SS.dtCreatedDate,
                IFNULL(B.vcBankName,'N/A') AS vcBankName,
                IFNULL(SS.vcChequeNo,'N/A') AS vcChequeNo,
                IFNULL(SS.dtPDDate,'N/A') AS dtPDDate,
                IFNULL(SS.vcRemark,'N/A') AS vcRemark
        FROM suppliersettlementheader AS SS
        INNER JOIN supplier AS S ON SS.intSupplierID = S.intSupplierID
        INNER JOIN paymode AS P ON SS.intPayModeID = P.intPayModeID
        INNER JOIN user AS U ON SS.intUserID = U.intUserID
        LEFT OUTER JOIN bank AS B ON SS.intBankID = B.intBankID";

        $dateFilter = " WHERE CAST(SS.dtCreatedDate AS DATE) BETWEEN ? AND ? ";

        $customerFilte = "";
        $paymentTypeFilte = "";

        $sqlParam = array();

        array_push($sqlParam, $FromDate);
        array_push($sqlParam, $ToDate);

        if ($PayModeID != 0) {

            $paymentTypeFilte = " AND P.intPayModeID = ? ";
            array_push($sqlParam, $PayModeID);
        }

        if ($SupplierID != 0) {

            $customerFilte = " AND S.intSupplierID = ? ";
            array_push($sqlParam, $SupplierID);
        }

        $sql  = $sql . $dateFilter  . $paymentTypeFilte . $customerFilte . " ORDER BY SS.dtCreatedDate DESC";

        $query = $this->db->query($sql, $sqlParam);
        return $query->result_array();
    }

    public function getSettlementDetailsToModal($SupplierSettlementHeaderID)
    {
        if ($SupplierSettlementHeaderID) {
            $sql = "SELECT  GH.intGRNHeaderID, CONCAT(GH.vcInvoiceNo,' ( ', GH.vcGRNNo,' ) ') AS vcGRNNo , GH.decGrandTotal AS TotalAmount , SD.decPaidAmount AS PaidAmount
            FROM suppliersettlementdetail AS SD
            INNER JOIN suppliersettlementheader AS SH ON SD.intSupplierSettlementHeaderID = SH.intSupplierSettlementHeaderID
            INNER JOIN supplier AS S ON SH.intSupplierID = S.intSupplierID
            INNER JOIN paymode AS P ON SH.intPayModeID = P.intPayModeID
            INNER JOIN user AS U ON SH.intUserID = U.intUserID
            INNER JOIN grnheader AS GH ON SD.intGRNHeaderID = GH.intGRNHeaderID
            WHERE SD.intSupplierSettlementHeaderID = ?";
            $query = $this->db->query($sql, array($SupplierSettlementHeaderID));
            return $query->result_array();
        }
    }

    public function getGRNWiseSettlementDetailsToModal($GRNHeaderID)
    {
        if ($GRNHeaderID) {
            $sql = "SELECT SH.vcSupplierSettlementNo,CAST(SH.dtPaidDate AS DATE) as dtPaidDate, PM.vcPayMode,IFNULL(CONCAT(SH.vcChequeNo,' - ',B.vcBankName),'N/A') AS vcChequeNo ,IFNULL(CAST(SH.dtPDDate AS DATE),'N/A') AS dtPDDate, SD.decPaidAmount  FROM suppliersettlementheader AS SH
            INNER JOIN suppliersettlementdetail AS SD ON SH.intSupplierSettlementHeaderID = SD.intSupplierSettlementHeaderID
            INNER JOIN paymode AS PM ON SH.intPayModeID = PM.intPayModeID
            LEFT OUTER JOIN bank AS B ON SH.intBankID = B.intBankID
            WHERE SD.intGRNHeaderID = ?";
            $query = $this->db->query($sql, array($GRNHeaderID));
            return $query->result_array();
        }
    }

    //-----------------------------------
    // Cancel Supplier Credit Settlement
    //-----------------------------------

    public function getSupplierCreditSettlementNo()
    {
        $sql = "SELECT SH.intSupplierSettlementHeaderID,SH.vcSupplierSettlementNo 
        FROM suppliersettlementheader SH";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function saveCancelSupplierCreditSettlement()
    {
        $this->db->trans_start();
        $SupplierSettlementHeaderID = $this->input->post('cmbSupplierSettlementNo');
        $UserID = $this->session->userdata('user_id');
        $Reason =  $this->input->post('Reason') == "" ? NULL : $this->input->post('Reason');

        $sql = "INSERT INTO `cancelsuppliersettlementheader`(`intSupplierSettlementHeaderID`,`vcSupplierSettlementNo`,`intSupplierID`,`decAmount`,`intPayModeID`,`dtPaidDate`,`intUserID`,`dtCreatedDate`,`vcChequeNo`,`intBankID`,`dtPDDate`,`vcRemark`,`intEnteredBy`,`vcReason`)
                SELECT intSupplierSettlementHeaderID, vcSupplierSettlementNo, intSupplierID, decAmount, intPayModeID, dtPaidDate, intUserID, dtCreatedDate, vcChequeNo, intBankID, dtPDDate, vcRemark , $UserID ,' $Reason '
                FROM suppliersettlementheader WHERE intSupplierSettlementHeaderID = ?;";
         $query = $this->db->query($sql, array($SupplierSettlementHeaderID));


         $sql = "INSERT INTO `cancelsuppliersettlementdetail`(`intSupplierSettlementDetailID`,`intSupplierSettlementHeaderID`,`intGRNHeaderID`,`decPaidAmount`)
         SELECT intSupplierSettlementDetailID, intSupplierSettlementHeaderID, intGRNHeaderID, decPaidAmount
         FROM suppliersettlementdetail WHERE intSupplierSettlementHeaderID = ?;";
         $query = $this->db->query($sql, array($SupplierSettlementHeaderID));

         $sql = "UPDATE supplier S
         INNER JOIN suppliersettlementheader as SS ON S.intSupplierID = SS.intSupplierID 
         SET S.decAvailableCredit =  (S.decAvailableCredit - SS.decAmount)
         WHERE SS.intSupplierSettlementHeaderID = ?;";
         $query = $this->db->query($sql, array($SupplierSettlementHeaderID));

         $this->db->where('intSupplierSettlementHeaderID', $SupplierSettlementHeaderID);
         $this->db->delete('suppliersettlementdetail');
 
         $this->db->where('intSupplierSettlementHeaderID', $SupplierSettlementHeaderID);
         $this->db->delete('suppliersettlementheader');

        $this->db->trans_complete();

        return ($query == true) ? true : false;
    }
}
