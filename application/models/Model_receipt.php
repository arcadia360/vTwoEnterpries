<?php
class Model_receipt extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCustomerToBeSettleIssueNos($CustomerID)
    {
        ///Commented On 2021-07-25

        // $sql = "SELECT 
        //             IH.intIssueHeaderID,
        //             IH.vcIssueNo,
        //             IH.decGrandTotal,
        //             SUM(IFNULL(RD.decPaidAmount,0)) AS decPaidAmount 
        //         FROM 
        //             IssueHeader AS IH
        //             LEFT OUTER JOIN ReceiptDetail AS RD ON IH.intIssueHeaderID = RD.intIssueHeaderID
        //         WHERE 
        //             IH.intCustomerID = ?
        //         GROUP BY
        //             IH.intIssueHeaderID
        //         HAVING
        //             IH.decGrandTotal > SUM(IFNULL(RD.decPaidAmount,0)) ";

        $sql = "SELECT 
                    IH.intIssueHeaderID,
                    IH.vcIssueNo,
                    IH.decGrandTotal,
                    SUM(IFNULL(RD.decPaidAmount,0)) AS decPaidAmount,
                    IFNULL(IRH.decTotal,0) AS decReturnTotal
                FROM 
                    IssueHeader AS IH
                    LEFT OUTER JOIN ReceiptDetail AS RD ON IH.intIssueHeaderID = RD.intIssueHeaderID
                    LEFT OUTER JOIN IssueReturnHeader AS IRH ON IH.intIssueHeaderID = IRH.intIssueHeaderID
                WHERE 
                    IH.intCustomerID = ?
                GROUP BY
                    IH.intIssueHeaderID
                HAVING
                    IH.decGrandTotal > SUM(IFNULL(RD.decPaidAmount,0))";

        $query = $this->db->query($sql, array($CustomerID));
        return $query->result_array();
    }

    public function getIssueNotePaymentDetails($IssueHeaderID)
    {
        $sql = "SELECT 
                IH.decGrandTotal,
                SUM(IFNULL(RD.decPaidAmount,0)) AS decPaidAmount,
                REPLACE(IH.rv,' ','-') AS rv
            FROM 
                IssueHeader AS IH
                LEFT OUTER JOIN ReceiptDetail AS RD ON IH.intIssueHeaderID = RD.intIssueHeaderID
            WHERE 
                IH.intIssueHeaderID = ?";

        $query = $this->db->query($sql, array($IssueHeaderID));
        return $query->row_array();
    }

    public function saveReceipt()
    {
        $this->db->trans_begin();

        $query = $this->db->query("SELECT fnGenerateCustomerReceiptNo() AS ReceiptNo");
        $ret = $query->row();
        $ReceiptNo = $ret->ReceiptNo;

        $response = array();

        // $ReceiptNo = "Receipt-001";

        $customerID = $this->input->post('cmbCustomer');
        $payMode = $this->input->post('cmbPayMode');
        (float)$receiptTotal = $this->input->post('txtAmount');

        $ReceiptHeaderData = array(
            'vcReceiptNo' => $ReceiptNo,
            'intCustomerID' => $customerID,
            'intPayModeID' =>  $payMode,
            'decAmount' => $receiptTotal,
            'dtReceiptDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('ReceiptDate')))),
            'vcRemark' => $this->input->post('txtRemark') == "" ? NULL : $this->input->post('txtRemark'),
            'intUserID' => $this->session->userdata('user_id'),
        );

        $this->db->insert('ReceiptHeader', $ReceiptHeaderData);
        $ReceiptHeaderID = $this->db->insert_id();

        if ($payMode == 2) { // Cheque
            $ChequeData = array(
                'intBankID' => $this->input->post('cmbBank'),
                'intReceiptHeaderID' => $ReceiptHeaderID,
                'vcChequeNo' => $this->input->post('txtChequeNo'),
                'dtPDDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('PDDate'))))
            );
            $this->db->insert('CustomerCheque', $ChequeData);
        } else { // Cash
            $sql = "UPDATE Customer 
                SET decAvailableCredit = (decAvailableCredit + " . $receiptTotal . ")
                WHERE intCustomerID = ?";

            $this->db->query($sql, array($customerID));
        }

        $item_count = count($this->input->post('issueHeaderID'));

        $anotherUserAccess = false;
        $userRestrictionsExceeded = false;

        (float)$payAmount = 0;

        for ($i = 0; $i < $item_count; $i++) {
            // $currentRV = $this->model_issue->chkRv($this->input->post('issueHeaderID')[$i]);
            // $previousRV =  $this->input->post('Rv')[$i];

            // if ($currentRV['rv'] != $previousRV) {
            //     $anotherUserAccess = true;
            // }

            $payAmount += (float)$this->input->post('payAmount')[$i];

            // $IssueNotePaymentData = $this->getIssueNotePaymentDetails($this->input->post('issueHeaderID')[$i]);
            // $PaidAmount = $IssueNotePaymentData['decPaidAmount'];
            // $IssueGrandTotal = $IssueNotePaymentData['decGrandTotal'];

            // if ((float)$this->input->post('payAmount')[$i] > (float)($IssueGrandTotal - $PaidAmount)) {
            //     $userRestrictionsExceeded = true;
            // }

            $receiptDetailsData = array(
                'intReceiptHeaderID' => $ReceiptHeaderID,
                'intIssueHeaderID' => $this->input->post('issueHeaderID')[$i],
                'decPaidAmount' => (float)$this->input->post('payAmount')[$i]
            );

            $this->db->insert('ReceiptDetail', $receiptDetailsData);
        }

        if ($receiptTotal != $payAmount) {
            $userRestrictionsExceeded = true;
        }

        $this->db->trans_commit();
        $response['success'] = true;
        $response['messages'] = 'Succesfully created !';

        return $response;
    }

    //-----------------------------------
    // View Customer Credit Settlement
    //-----------------------------------

    public function GetCustomerReceiptHeaderData($ReceiptHeaderID = null, $PayModeID = null, $CustomerID = null, $FromDate = null, $ToDate = null)
    {
        if ($ReceiptHeaderID) {
            $sql = "SELECT 
                    SS.intReceiptHeaderID,
                    C.intCustomerChequeID,
                    SS.vcReceiptNo,
                    SS.intCustomerID,
                    S.vcCustomerName,
                    P.vcPayMode,
                    P.intPayModeID,
                    SS.decAmount,
                    CAST(SS.dtReceiptDate AS DATE) AS dtPaidDate,
                    U.vcFullName,
                    SS.dtCreatedDate,
                    IFNULL(B.vcBankName,'N/A') AS vcBankName,
                    IFNULL(C.vcChequeNo,'N/A') AS vcChequeNo,
                    IFNULL(C.dtPDDate,'N/A') AS dtPDDate,
                    IFNULL(SS.vcRemark,'N/A') AS vcRemark,
                    C.IsRealized
            FROM receiptheader AS SS
            INNER JOIN customer AS S ON SS.intCustomerID = S.intCustomerID
            INNER JOIN paymode AS P ON SS.intPayModeID = P.intPayModeID
            INNER JOIN user AS U ON SS.intUserID = U.intUserID
            LEFT OUTER JOIN CustomerCheque AS C ON SS.intReceiptHeaderID = C.intReceiptHeaderID
            LEFT OUTER JOIN bank AS B ON C.intBankID = B.intBankID
            WHERE SS.intReceiptHeaderID = ?";

            $query = $this->db->query($sql, array($ReceiptHeaderID));
            return $query->row_array();
        }


        $sql = "SELECT * FROM (
            SELECT 
                                SS.intReceiptHeaderID,
                                C.intCustomerChequeID,
                                SS.vcReceiptNo,
                                SS.intCustomerID,
                                S.vcCustomerName,
                                P.vcPayMode,
                                P.intPayModeID,
                                SS.decAmount,
                                CAST(SS.dtReceiptDate AS DATE) AS dtPaidDate,
                                U.vcFullName,
                                SS.dtCreatedDate,
                                IFNULL(B.vcBankName,'N/A') AS vcBankName,
                                IFNULL(C.vcChequeNo,'N/A') AS vcChequeNo,
                                IFNULL(C.dtPDDate,'N/A') AS dtPDDate,
                                IFNULL(SS.vcRemark,'N/A') AS vcRemark, 
                                C.IsRealized,
                                0 AS ReceiptStatus
                        FROM receiptheader AS SS
                        INNER JOIN customer AS S ON SS.intCustomerID = S.intCustomerID
                        INNER JOIN paymode AS P ON SS.intPayModeID = P.intPayModeID
                        INNER JOIN user AS U ON SS.intUserID = U.intUserID
                        LEFT OUTER JOIN CustomerCheque AS C ON SS.intReceiptHeaderID = C.intReceiptHeaderID
                        LEFT OUTER JOIN bank AS B ON C.intBankID = B.intBankID

                        UNION ALL            

                        SELECT 
                                SS.intReceiptHeaderID,
                                C.intCustomerChequeID,
                                SS.vcReceiptNo,
                                SS.intCustomerID,
                                S.vcCustomerName,
                                P.vcPayMode,
                                P.intPayModeID,
                                SS.decAmount,
                                CAST(SS.dtReceiptDate AS DATE) AS dtPaidDate,
                                U.vcFullName,
                                SS.dtCreatedDate,
                                IFNULL(B.vcBankName,'N/A') AS vcBankName,
                                IFNULL(C.vcChequeNo,'N/A') AS vcChequeNo,
                                IFNULL(C.dtPDDate,'N/A') AS dtPDDate,
                                IFNULL(SS.vcRemark,'N/A') AS vcRemark, 
                                C.IsRealized,
                                1 AS ReceiptStatus
                        FROM receiptheader_his AS SS
                        INNER JOIN customer AS S ON SS.intCustomerID = S.intCustomerID
                        INNER JOIN paymode AS P ON SS.intPayModeID = P.intPayModeID
                        INNER JOIN user AS U ON SS.intUserID = U.intUserID
                        LEFT OUTER JOIN CustomerCheque AS C ON SS.intReceiptHeaderID = C.intReceiptHeaderID
                        LEFT OUTER JOIN bank AS B ON C.intBankID = B.intBankID     
                        WHERE SS.intReceiptHeaderID NOT IN (SELECT intReceiptHeaderID FROM customerreturncheque)  

                        		UNION ALL    
                        
                                SELECT 
                                SS.intReceiptHeaderID,
                                CRC.intCustomerChequeID,
                                SS.vcReceiptNo,
                                SS.intCustomerID,
                                S.vcCustomerName,
                                P.vcPayMode,
                                P.intPayModeID,
                                SS.decAmount,
                                CAST(SS.dtReceiptDate AS DATE) AS dtPaidDate,
                                U.vcFullName,
                                SS.dtCreatedDate,
                                IFNULL(B.vcBankName,'N/A') AS vcBankName,
                                IFNULL(CRC.vcChequeNo,'N/A') AS vcChequeNo,
                                IFNULL(CRC.dtPDDate,'N/A') AS dtPDDate,
                                IFNULL(SS.vcRemark,'N/A') AS vcRemark, 
                                CRC.IsRealized,
                                2 AS ReceiptStatus
                        FROM receiptheader_his AS SS
                        INNER JOIN customer AS S ON SS.intCustomerID = S.intCustomerID
                        INNER JOIN paymode AS P ON SS.intPayModeID = P.intPayModeID
                        INNER JOIN  customerreturncheque AS CRC ON SS.intReceiptHeaderID = CRC.intReceiptHeaderID  
                        INNER JOIN user AS U ON SS.intUserID = U.intUserID
                        LEFT OUTER JOIN CustomerCheque AS C ON SS.intReceiptHeaderID = C.intReceiptHeaderID
                        LEFT OUTER JOIN bank AS B ON CRC.intBankID = B.intBankID 
            ) T ";

        $dateFilter = " WHERE CAST(T.dtCreatedDate AS DATE) BETWEEN ? AND ? ";

        $customerFilte = "";
        $paymentTypeFilte = "";

        $sqlParam = array();

        array_push($sqlParam, $FromDate);
        array_push($sqlParam, $ToDate);

        if ($PayModeID != 0) {

            $paymentTypeFilte = " AND T.intPayModeID = ? ";
            array_push($sqlParam, $PayModeID);
        }

        if ($CustomerID != 0) {

            $customerFilte = " AND T.intCustomerID = ? ";
            array_push($sqlParam, $CustomerID);
        }

        $sql  = $sql . $dateFilter  . $paymentTypeFilte . $customerFilte . " ORDER BY T.dtCreatedDate DESC , T.vcReceiptNo";

        $query = $this->db->query($sql, $sqlParam);
        return $query->result_array();
    }

    public function getSettlementDetailsToModal($ReceiptHeaderID)
    {
        if ($ReceiptHeaderID) {
            $sql = "SELECT RD.intReceiptHeaderID, RD.intIssueHeaderID, IH.vcIssueNo , PY.vcPaymentType,  IH.decGrandTotal  , RD.decPaidAmount , sum(IH.decGrandTotal) - fnGetCustomerIssueWiseReceiptBalance(RD.intIssueHeaderID) as TotalAmountDue
            FROM receiptdetail AS RD
            INNER JOIN receiptheader AS RH ON RD.intReceiptHeaderID = RH.intReceiptHeaderID
            INNER JOIN issueheader AS IH ON RD.intIssueHeaderID = IH.intIssueHeaderID
            INNER JOIN paymenttype AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
            WHERE RD.intReceiptHeaderID = ?
            GROUP BY  RD.intIssueHeaderID";
            $query = $this->db->query($sql, array($ReceiptHeaderID));
            return $query->result_array();
        }
    }

    public function viewCancelledReceiptDetailsHis($ReceiptHeaderID)
    {
        if ($ReceiptHeaderID) {
            $sql = "SELECT RD.intReceiptHeaderID, RD.intIssueHeaderID, IH.vcIssueNo , PY.vcPaymentType,  IH.decGrandTotal  , RD.decPaidAmount , sum(IH.decGrandTotal) - fnGetCustomerIssueWiseReceiptBalance(RD.intIssueHeaderID) as TotalAmountDue
            FROM receiptdetail_his AS RD
            INNER JOIN receiptheader_his AS RH ON RD.intReceiptHeaderID = RH.intReceiptHeaderID
            INNER JOIN issueheader AS IH ON RD.intIssueHeaderID = IH.intIssueHeaderID
            INNER JOIN paymenttype AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
            WHERE RD.intReceiptHeaderID = ?
            GROUP BY  RD.intIssueHeaderID";
            $query = $this->db->query($sql, array($ReceiptHeaderID));
            return $query->result_array();
        }
    }

    public function customerChequeRealized($CustomerChequeID, $ReceiptHeaderID)
    {
        date_default_timezone_set('Asia/Colombo');
        $now = date('Y-m-d H:i:s');

        if ($CustomerChequeID) {
            $this->db->trans_start();
            $data = [
                'IsRealized' => '1',
                'dtRealizedDate' => $now,
                'intRealizedUserID' => $this->session->userdata('user_id'),
            ];
            $this->db->where('intCustomerChequeID', $CustomerChequeID);
            $Realized = $this->db->update('CustomerCheque', $data);


            $CustomerReceiptHeaderData = $this->GetCustomerReceiptHeaderData($ReceiptHeaderID, null, null, null, null);
            $customerID = $CustomerReceiptHeaderData['intCustomerID'];

            $sql = "UPDATE Customer 
            SET decAvailableCredit = (decAvailableCredit + " . $CustomerReceiptHeaderData['decAmount'] . ")
            WHERE intCustomerID = ?";

            $this->db->query($sql, array($customerID));

            $this->db->trans_complete();
            return ($Realized == true) ? true : false;
        }
    }

    public function customerReturnCheque($CustomerChequeID, $ReceiptHeaderID)
    {
        date_default_timezone_set('Asia/Colombo');
        $now = date('Y-m-d H:i:s');
        $reason = 'Return Cheque';

        if ($CustomerChequeID) {
            $this->db->trans_start();
            $sql = "SELECT intCustomerChequeID, intBankID, intReceiptHeaderID, vcChequeNo, dtPDDate, IsRealized, dtRealizedDate, intRealizedUserID,'" . $reason . "'  AS vcReason , '" . $now . "'  AS dtReturnedDate ,  " . $this->session->userdata('user_id') . " AS  intReturnedUserID FROM CustomerCheque WHERE intCustomerChequeID = ? ";
            $query = $this->db->query($sql, array($CustomerChequeID));
            if ($query->num_rows()) {
                $insert = $this->db->insert('customerreturncheque', $query->row_array());
                $this->db->where('intCustomerChequeID', $CustomerChequeID);
                $delete = $this->db->delete('CustomerCheque');

                $sql = "SELECT intReceiptHeaderID, vcReceiptNo, intCustomerID, intPayModeID, decAmount, dtReceiptDate, vcRemark, intUserID, dtCreatedDate, '" . $now . "'  AS dtCancelledDate ,  " . $this->session->userdata('user_id') . " AS  intCancelledUserID FROM receiptheader WHERE intReceiptHeaderID = ? ";
                $query = $this->db->query($sql, array($ReceiptHeaderID));
                $insert = $this->db->insert('receiptheader_his', $query->row_array());

                $sql = "SELECT intReceiptDetailID, intReceiptHeaderID, intIssueHeaderID, decPaidAmount FROM receiptdetail WHERE intReceiptHeaderID = ? ";
                $query = $this->db->query($sql, array($ReceiptHeaderID));
                $insert = $this->db->insert('receiptdetail_his', $query->row_array());

                $this->db->where('intReceiptHeaderID', $ReceiptHeaderID);
                $delete = $this->db->delete('receiptdetail');

                $this->db->where('intReceiptHeaderID', $ReceiptHeaderID);
                $delete = $this->db->delete('receiptheader');

                $this->db->trans_complete();
                return ($delete == true) ? true : false;
            }
        }
    }

    public function customerCancelCheque($CustomerChequeID, $ReceiptHeaderID)
    {
        date_default_timezone_set('Asia/Colombo');
        $now = date('Y-m-d H:i:s');

        if ($CustomerChequeID) {
            $this->db->trans_start();
            $sql = "SELECT intCustomerChequeID, intBankID, intReceiptHeaderID, vcChequeNo, dtPDDate, IsRealized, dtRealizedDate, intRealizedUserID, '" . $now . "'  AS dtCancelledDate ,  " . $this->session->userdata('user_id') . " AS  intCancelledUserID FROM CustomerCheque WHERE intCustomerChequeID = ? ";
            $query = $this->db->query($sql, array($CustomerChequeID));
            if ($query->num_rows()) {
                $insert = $this->db->insert('customercancelcheque', $query->row_array());
                $this->db->where('intCustomerChequeID', $CustomerChequeID);
                $delete = $this->db->delete('CustomerCheque');

                $sql = "SELECT intReceiptHeaderID, vcReceiptNo, intCustomerID, intPayModeID, decAmount, dtReceiptDate, vcRemark, intUserID, dtCreatedDate, '" . $now . "'  AS dtCancelledDate ,  " . $this->session->userdata('user_id') . " AS  intCancelledUserID FROM receiptheader WHERE intReceiptHeaderID = ? ";
                $query = $this->db->query($sql, array($ReceiptHeaderID));
                $insert = $this->db->insert('receiptheader_his', $query->row_array());

                $sql = "SELECT intReceiptDetailID, intReceiptHeaderID, intIssueHeaderID, decPaidAmount FROM receiptdetail WHERE intReceiptHeaderID = ? ";
                $query = $this->db->query($sql, array($ReceiptHeaderID));
                $insert = $this->db->insert('receiptdetail_his', $query->row_array());

                $this->db->where('intReceiptHeaderID', $ReceiptHeaderID);
                $delete = $this->db->delete('receiptdetail');

                $this->db->where('intReceiptHeaderID', $ReceiptHeaderID);
                $delete = $this->db->delete('receiptheader');

                $this->db->trans_complete();
                return ($delete == true) ? true : false;
            }
        }
    }

    public function customerCancelRealized($CustomerChequeID, $ReceiptHeaderID)
    {
        date_default_timezone_set('Asia/Colombo');
        $now = date('Y-m-d H:i:s');

        if ($CustomerChequeID) {
            $this->db->trans_start();
            $Data = array(
                'intCustomerChequeID' => $CustomerChequeID,
                'intReceiptHeaderID' => $ReceiptHeaderID,
                'dtCancelledDate' =>  $now,
                'intCancelledUserID' => $this->session->userdata('user_id')
            );
            $this->db->insert('customerchequerealizedcancellation', $Data);

            $UpdateData = array(
                'IsRealized' => 0,
                'dtRealizedDate' => NULL,
                'intRealizedUserID' =>  NULL,
            );
            $this->db->where('intCustomerChequeID', $CustomerChequeID);
            $update = $this->db->update('customercheque', $UpdateData);

            $CustomerReceiptHeaderData = $this->GetCustomerReceiptHeaderData($ReceiptHeaderID, null, null, null, null);
            $customerID = $CustomerReceiptHeaderData['intCustomerID'];
    
            $sql = "UPDATE Customer 
            SET decAvailableCredit = (decAvailableCredit - " . $CustomerReceiptHeaderData['decAmount'] . ")
            WHERE intCustomerID = ?";
    
            $this->db->query($sql, array($customerID));

            $this->db->trans_complete();
            return ($update == true) ? true : false;
            
        }
    }

    public function customerChequeReceipt($ReceiptHeaderID)
    {
        date_default_timezone_set('Asia/Colombo');
        $now = date('Y-m-d H:i:s');

        $this->db->trans_start();

        $sql = "SELECT intReceiptHeaderID, vcReceiptNo, intCustomerID, intPayModeID, decAmount, dtReceiptDate, vcRemark, intUserID, dtCreatedDate, '" . $now . "'  AS dtCancelledDate ,  " . $this->session->userdata('user_id') . " AS  intCancelledUserID FROM receiptheader WHERE intReceiptHeaderID = ? ";
        $query = $this->db->query($sql, array($ReceiptHeaderID));
        $insert = $this->db->insert('receiptheader_his', $query->row_array());

        $sql = "SELECT intReceiptDetailID, intReceiptHeaderID, intIssueHeaderID, decPaidAmount FROM receiptdetail WHERE intReceiptHeaderID = ? ";
        $query = $this->db->query($sql, array($ReceiptHeaderID));
        $insert = $this->db->insert('receiptdetail_his', $query->row_array());

        $CustomerReceiptHeaderData = $this->GetCustomerReceiptHeaderData($ReceiptHeaderID, null, null, null, null);
        $customerID = $CustomerReceiptHeaderData['intCustomerID'];

        $sql = "UPDATE Customer 
        SET decAvailableCredit = (decAvailableCredit - " . $CustomerReceiptHeaderData['decAmount'] . ")
        WHERE intCustomerID = ?";

        $this->db->query($sql, array($customerID));

        $this->db->where('intReceiptHeaderID', $ReceiptHeaderID);
        $delete = $this->db->delete('receiptdetail');

        $this->db->where('intReceiptHeaderID', $ReceiptHeaderID);
        $delete = $this->db->delete('receiptheader');

        $this->db->trans_complete();
        return ($delete == true) ? true : false;
    }
}
