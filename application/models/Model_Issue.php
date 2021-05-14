<?php

class Model_issue extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_customer');
        $this->load->model('model_item');
    }

    public function chkRv($id = null)
    {
        if ($id) {
            $sql = "SELECT REPLACE(rv,' ','-') as rv FROM `IssueHeader` WHERE intIssueHeaderID = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }
    }

    public function saveIssue()
    {
        $this->db->trans_begin();

        $query = $this->db->query("SELECT fnGenerateIssueNo() AS IssueNo");
        $ret = $query->row();
        $IssueNo = $ret->IssueNo;

        $response = array();

        // $IssueNo = "Issue-001";

        $insertDetails = false;

        $paymentType = $this->input->post('cmbpayment');
        $GrandTotal = str_replace(',', '', $this->input->post('grandTotal'));

        $customerData = $this->model_customer->getCustomerData($this->input->post('cmbcustomer'));
        $customerAvailableCredit = (float)$customerData['decAvailableCredit'];
        $exceedCreditLimit = false;

        if ($paymentType == 2) //Credit
        {
            $IsAdvancePayment = (bool)$this->input->post('IsAdvancePayment');
            $advance_payment = (float)$customerData['decAdvanceAmount'];

            if ($IsAdvancePayment == true) {
                if ((float)$customerData['decCreditBuyAmount'] < $GrandTotal) {
                    $exceedCreditLimit = true;
                }
            } else {
                if ($customerAvailableCredit < $GrandTotal) {
                    $exceedCreditLimit = true;
                }
            }
        }


        $data = array(
            'vcIssueNo' => $IssueNo,
            'intCustomerID' => $this->input->post('cmbcustomer'),
            'intSalesRepID' => $this->input->post('cmbSalesRep') == null ? null : $this->input->post('cmbSalesRep'),
            'dtIssueDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('issuedDate')))),
            'intUserID' => $this->session->userdata('user_id'),
            'intPaymentTypeID' =>  $paymentType,
            'decSubTotal' => str_replace(',', '', $this->input->post('subTotal')),
            'decDiscount' => $this->input->post('txtDiscount'),
            'decGrandTotal' => $GrandTotal,
            'vcRemark' => $this->input->post('txtRemark'),
        );

        $this->db->insert('IssueHeader', $data);
        $IssueHeaderID = $this->db->insert_id();

        $IsAdvancePayment = (bool)$this->input->post('IsAdvancePayment');
        if ($IsAdvancePayment == true) {
            if ((float)$customerData['decAdvanceAmount'] > 0) {
                $sql = "UPDATE customeradvancepayment AS C
                SET C.intIssueHeaderID  = " . $IssueHeaderID . "
                WHERE C.intCustomerID = ? AND C.intIssueHeaderID IS NULL";

                $this->db->query($sql, array($this->input->post('cmbcustomer')));
            }
        }

        if ($paymentType == 2) //Credit
        {
            if ($IsAdvancePayment == true) {
                if ((float)$customerData['decAdvanceAmount'] > 0) {

                    $sql = "UPDATE customer AS C
                    SET C.decAvailableCredit = (C.decAvailableCredit - " . ($GrandTotal - (float)$customerData['decAdvanceAmount']) . ")
                    WHERE C.intCustomerID = ?";
                    $this->db->query($sql, array($this->input->post('cmbcustomer')));
                }
            } else {
                $sql = "UPDATE customer AS C
                   SET C.decAvailableCredit = (C.decAvailableCredit - " . $GrandTotal . ")
                   WHERE C.intCustomerID = ?";
                $this->db->query($sql, array($this->input->post('cmbcustomer')));
            }
        }


        $item_count = count($this->input->post('itemID'));

        $anotherUserAccess = false;
        $exceedStockQty = false;

        for ($i = 0; $i < $item_count; $i++) {

            $currentRV = $this->model_item->chkStockViewRv($this->input->post('grnDetailID')[$i]);
            $previousRV =  $this->input->post('Rv')[$i];


            if ($currentRV['rv'] != $previousRV) {
                $anotherUserAccess = true;
            }
            $decIssuQty = $this->input->post('itemQty')[$i];
            $itemID  = $this->input->post('itemID')[$i];
            $itemData = $this->model_item->getItemData($this->input->post('itemID')[$i]);
            $itemCustomerWiseUnitPrice = $this->model_item->getItemDetailsByCustomerID($this->input->post('itemID')[$i], $this->input->post('cmbcustomer'));
            $UnitPrice = $itemCustomerWiseUnitPrice['decUnitPrice'];
            if ($itemData['decStockInHand'] < $decIssuQty) {
                $exceedStockQty = true;
            }
            $items = array(
                'intIssueHeaderID' => $IssueHeaderID,
                'intGRNDetailID' => $this->input->post('grnDetailID')[$i],
                'intItemID' => $this->input->post('itemID')[$i],
                'decIssueQty' => $this->input->post('itemQty')[$i],
                'decUnitPrice' => $UnitPrice,
                'decDiscountPercentage' => $this->input->post('discountPercentage')[$i],
                'decDiscountedPrice' => $this->input->post('totalPrice')[$i]
            );
            $insertDetails = $this->db->insert('IssueDetail', $items);
            $IssueDetailID = $this->db->insert_id();

            // $Logdata = array(
            //     'intItemID' => $itemData['intItemID'],
            //     'intTransactionLogTypeID' => 3, //Item Issue
            //     'vcPerformColumn' => 'intIssueDetailID',
            //     'intPerformID' => $IssueDetailID,
            //     'decPreviousQty' => $itemData['decStockInHand'],
            //     'decCurrentQty' => $itemData['decStockInHand'] - $decIssuQty,
            //     'intLoggedBy' => $this->session->userdata('user_id'),
            // );

            // $insertLog = $this->db->insert('itemtransactionlog', $Logdata);

            $sql = "UPDATE grndetail AS GD
                    SET GD.decAvailableQty = (GD.decAvailableQty - " . $decIssuQty . ")
                    WHERE GD.intGRNDetailID = ?";

            $this->db->query($sql, array($this->input->post('grnDetailID')[$i]));
        }


        if ($anotherUserAccess == true) {
            $response['success'] = false;
            $response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
            $this->db->trans_rollback();
        } else 
        if ($exceedStockQty == true) {
            $response['success'] = false;
            $response['messages'] = 'Stock quantity over exceeds error, please refresh the page and try again !';
            $this->db->trans_rollback();
        } else if ($exceedCreditLimit == true) {
            $response['success'] = false;
            $response['messages'] = 'You cannot exceed cutomer credit limit !';
            $this->db->trans_rollback();
        } else {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['success'] = false;
                $response['messages'] = 'Error in the database while create the issue details';
            } else {

                $IssueHeaderData = $this->GetIssueHeaderData($IssueHeaderID, null, null, null, null);

                $response['vcIssueNo'] =  $IssueHeaderData['vcIssueNo'];
                $response['intIssueHeaderID'] =  $IssueHeaderData['intIssueHeaderID'];

                $this->db->trans_commit();
                $response['success'] = true;
                $response['messages'] = 'Succesfully created !';
            }
        }

        return $response;
    }

    public function GetIssueHeaderData($IssueHeaderID = null, $PaymentType = null, $CustomerID = null, $FromDate = null, $ToDate = null)
    {
        if ($IssueHeaderID) {
            $sql = "
            SELECT  
                                IH.intIssueHeaderID,
                                IH.intCustomerID,
                                IFNULL(SR.vcSalesRepName,'N/A') AS vcSalesRepName,
                                IH.vcIssueNo,
                                CU.vcCustomerName,
                                CU.vcBuildingNumber,
                                CU.vcStreet,
                                CU.vcCity,
                                CU.vcContactNo1,
                                CU.vcContactNo2,
                                CAST(IH.dtIssueDate AS DATE) AS dtIssueDate,
                                IH.dtCreatedDate,
                                U.vcFullName,
                                IH.intPaymentTypeID,
                                PY.vcPaymentType,
                                IH.decSubTotal,
                                IH.decDiscount,
                                IH.decGrandTotal,
                                CU.decCreditLimit,
                                CU.decAvailableCredit,
                                IFNULL(CA.decAmount,'N/A') AS decAdvanceAmount,
                                IFNULL(IH.vcRemark,'N/A') AS vcRemark,
                                IFNULL(RD.intIssueHeaderID,'N/A') AS PaymentViewButton
                        FROM Issueheader AS IH
                        INNER JOIN customer AS CU ON IH.intCustomerID = CU.intCustomerID
                        INNER JOIN user as U ON IH.intUserID = U.intUserID
                        INNER JOIN paymenttype AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
                        LEFT OUTER JOIN customeradvancepayment AS CA ON IH.intIssueHeaderID = CA.intIssueHeaderID
                        LEFT OUTER JOIN receiptdetail AS RD ON IH.intIssueHeaderID = RD.intIssueHeaderID
                        LEFT OUTER JOIN salesrep AS SR ON IH.intSalesRepID = SR.intSalesRepID
            WHERE IH.intIssueHeaderID = ?";

            $query = $this->db->query($sql, array($IssueHeaderID));
            return $query->row_array();
        }



        $sql = "
        SELECT  IH.intIssueHeaderID,
               IH.vcIssueNo,
               CU.vcCustomerName,
               CU.vcBuildingNumber,
               CU.vcStreet,
               CU.vcCity,
               CAST(IH.dtIssueDate AS DATE) AS dtIssueDate,
               IH.dtCreatedDate,
               U.vcFullName,
               IH.intPaymentTypeID,
               PY.vcPaymentType,
               IH.decSubTotal,
               IH.decDiscount,
               IH.decGrandTotal,
               IFNULL(CA.decAmount,'N/A') AS decAdvanceAmount,
               IFNULL(IH.vcRemark,'N/A') AS vcRemark,
               IFNULL(RD.intIssueHeaderID,'N/A') AS PaymentViewButton
       FROM Issueheader AS IH
       INNER JOIN customer AS CU ON IH.intCustomerID = CU.intCustomerID
       INNER JOIN user as U ON IH.intUserID = U.intUserID
       INNER JOIN paymenttype AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
       LEFT OUTER JOIN customeradvancepayment AS CA ON IH.intIssueHeaderID = CA.intIssueHeaderID
       LEFT OUTER JOIN receiptdetail AS RD ON IH.intIssueHeaderID = RD.intIssueHeaderID";

        $dateFilter = " WHERE CAST(IH.dtCreatedDate AS DATE) BETWEEN ? AND ? ";

        $customerFilte = "";
        $paymentTypeFilte = "";

        $sqlParam = array();

        array_push($sqlParam, $FromDate);
        array_push($sqlParam, $ToDate);

        if ($PaymentType != 0) {

            $paymentTypeFilte = " AND IH.intPaymentTypeID = ? ";
            array_push($sqlParam, $PaymentType);
        }

        if ($CustomerID != 0) {

            $customerFilte = " AND IH.intCustomerID = ? ";
            array_push($sqlParam, $CustomerID);
        }

        $sql  = $sql . $dateFilter  . $paymentTypeFilte . $customerFilte . "GROUP BY IH.intIssueHeaderID ORDER BY IH.dtCreatedDate DESC";

        $query = $this->db->query($sql, $sqlParam);
        return $query->result_array();
    }

    public function GetIssueDetailsData($IssueHeaderID = null)
    {

        $sql = "CALL spGetIssueDetailsData(?);";

        $query = $this->db->query($sql, array($IssueHeaderID));
        return $query->result_array();
    }

    public function getPaymentTypes()
    {
        $sql = "SELECT intPaymentTypeID,vcPaymentType FROM paymenttype;";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //-----------------------------------
    // Issue Return
    //-----------------------------------

    public function getReturnIssueNo()
    {
        $sql = "SELECT IH.intIssueHeaderID,IH.vcIssueNo 
        FROM issueheader IH
        WHERE IH.intIssueHeaderID NOT IN (select intIssueHeaderID from receiptdetail);";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function chkNullCustomerAdvancePayment($CustomerID)
    {
        $sql = "
        SELECT  intCustomerAdvancePaymentID
        FROM customeradvancepayment 
        where intCustomerID = ? and intIssueHeaderID is null;";

        $query = $this->db->query($sql, array($CustomerID));
        if ($query->result_array() == null) {
            return true;
        } else {
            return false;
        }
    }

    public function chkExistsReceiptDetails($id = null)
    {
        if ($id) {
            $sql = "SELECT EXISTS(SELECT intIssueHeaderID  FROM receiptdetail WHERE intIssueHeaderID = ?) AS value";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
    }


    public function saveIssueReturn()
    {
        $this->db->trans_begin();

        $IssueHeaderID = $this->input->post('cmbIssueNo');

        // $query = $this->db->query("SELECT fnGenerateIssueNo() AS IssueNo");
        // $ret = $query->row();
        // $IssueNo = $ret->IssueNo;

        $response = array();

        $ReturnNo = "Return-001";

        $Reason = "";
        $UserID = 0;
        $Reason = $this->input->post('Reason');
        $UserID = $this->session->userdata('user_id');


        $sql = "UPDATE Item AS I
        INNER JOIN issuedetail AS ID ON I.intItemID = ID.intItemID
        SET I.decStockInHand = (I.decStockInHand + ID.decIssueQty)
        WHERE ID.intIssueHeaderID = ? ;";
        $this->db->query($sql, array($IssueHeaderID));


        $sql = "UPDATE customer AS C
        INNER JOIN issueheader AS I ON C.intCustomerID = I.intCustomerID 
        LEFT OUTER JOIN customeradvancepayment AS A ON I.intIssueHeaderID = A.intIssueHeaderID
        SET C.decAvailableCredit =  CASE WHEN A.intIssueHeaderID IS NULL THEN (C.decAvailableCredit + I.decGrandTotal) ELSE (C.decAvailableCredit + (I.decGrandTotal - A.decAmount)) END 
        WHERE I.intIssueHeaderID = ? AND I.intPaymentTypeID = 2;";
        $this->db->query($sql, array($IssueHeaderID));

        $sql = "UPDATE customeradvancepayment
        SET intIssueHeaderID = NULL
        WHERE intIssueHeaderID = ? ;";
        $this->db->query($sql, array($IssueHeaderID));

        $sql = "INSERT INTO issuereturnheader(vcIssueReturnNo,intIssueHeaderID, vcIssueNo, intCustomerID, dtIssueDate, dtCreatedDate, intUserID, intPaymentTypeID, decSubTotal, decDiscount, decGrandTotal, IsActive, vcReason, intReturnedUserID)
        SELECT  '$ReturnNo', IH.intIssueHeaderID, IH.vcIssueNo, IH.intCustomerID, IH.dtIssueDate, IH.dtCreatedDate, IH.intUserID, IH.intPaymentTypeID, IH.decSubTotal, IH.decDiscount, IH.decGrandTotal, IH.IsActive , ' $Reason ' , $UserID 
        FROM    issueheader AS IH
        WHERE   IH.intIssueHeaderID = ?;";
        $this->db->query($sql, array($IssueHeaderID));
        $IssueReturnHeaderID = $this->db->insert_id();

        $sql = "INSERT INTO issuereturndetail(intIssueReturnHeaderID, intIssueDetailID, intIssueHeaderID, intItemID, decIssueQty, decUnitPrice, decTotalPrice,decReturnQty)
        SELECT  $IssueReturnHeaderID , ID.intIssueDetailID, ID.intIssueHeaderID, ID.intItemID, ID.decIssueQty, ID.decUnitPrice, ID.decTotalPrice , ID.decIssueQty
        FROM    issuedetail AS ID
        WHERE   ID.intIssueHeaderID = ?;";
        $this->db->query($sql, array($IssueHeaderID));

        $this->db->where('intIssueHeaderID', $IssueHeaderID);
        $this->db->delete('issuedetail');

        $this->db->where('intIssueHeaderID', $IssueHeaderID);
        $this->db->delete('issueheader');

        $this->db->trans_complete();

        $response['success'] = true;
        $response['messages'] = 'Succesfully Returned !';

        return $response;
    }
}
