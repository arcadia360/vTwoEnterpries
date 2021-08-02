<?php

class Model_grn extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveGRN()
    {
        $this->db->trans_start();

        $query = $this->db->query("SELECT fnGenerateGRNNo() AS GRNNo");
        $ret = $query->row();
        $GRNNo = $ret->GRNNo;


        // $GRNNo = "Test-001";

        $insertDetails = false;


        $data = array(
            'vcGRNNo' => $GRNNo,
            'vcInvoiceNo' => $this->input->post('invoice_no'),
            'intSupplierID' => $this->input->post('supplier'),
            'intPaymentTypeID' => $this->input->post('cmbpayment'),
            'dtReceivedDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('receivedDate')))),
            'vcRemark' => $this->input->post('txtRemark'),
            'intUserID' => $this->session->userdata('user_id'),
            'decSubTotal' => str_replace(',', '', $this->input->post('subTotal')),
            'decDiscount' => $this->input->post('txtDiscount'),
            'decGrandTotal' => str_replace(',', '', $this->input->post('grandTotal')),
        );

        $insert = $this->db->insert('GRNHeader', $data);
        $GRNHeaderID = $this->db->insert_id();

        $item_count = count($this->input->post('itemID'));

        for ($i = 0; $i < $item_count; $i++) {
            $qty = $this->input->post('itemQty')[$i];
            $items = array(
                'intGRNHeaderID' => $GRNHeaderID,
                'intItemID' => $this->input->post('itemID')[$i],
                'decQty' => $qty,
                'decUnitPrice' => $this->input->post('unitPrice')[$i],
                'decTotalPrice' => $this->input->post('totalPrice')[$i],
                'decAvailableQty' => $qty
            );
            $insertDetails = $this->db->insert('GRNDetail', $items);


        }

        $this->db->trans_complete();

        return ($insertDetails == true) ? true : false;
    }

    public function getGRNHeaderData($GRNID = null, $Status = null, $FromDate = null, $ToDate = null)
    {
        if ($GRNID) {
            $sql = "
                    SELECT 
                        GH.intGRNHeaderID, 
                        GH.vcGRNNo, 
                        GH.vcInvoiceNo, 
                        GH.intSupplierID,
                        S.vcSupplierName, 
                        P.vcPaymentType,
                        P.intPaymentTypeID,
                        GH.decSubTotal, 
                        IFNULL(GH.decDiscount,0.00) AS decDiscount, 
                        GH.decGrandTotal, 
                        CAST(GH.dtReceivedDate AS DATE) AS dtReceivedDate, 
                        GH.vcRemark,
                        GH.dtCreatedDate, 
                        GH.intUserID AS CreatedUserID,
                        CreatedUser.vcFullName AS CreatedUser, 
                        GH.intApprovedBy,
                        ApprovedUser.vcFullName AS ApprovedUser, 
                        GH.dtApprovedOn,
                        RejectedUser.vcFullName AS RejectedUser,
                        GH.dtRejectedOn,
                        REPLACE(GH.rv,' ','-') as rv 
                    FROM 
                        GRNHeader AS GH
                        INNER JOIN Supplier AS S ON GH.intSupplierID = S.intSupplierID
                        INNER JOIN User AS CreatedUser ON GH.intUserID = CreatedUser.intUserID
                        INNER JOIN paymenttype AS P ON GH.intPaymentTypeID = P.intPaymentTypeID
                        LEFT OUTER JOIN User AS ApprovedUser ON GH.intApprovedBy = ApprovedUser.intUserID
                        LEFT OUTER JOIN User AS RejectedUser ON GH.intRejectedBy = RejectedUser.intUserID
                    WHERE GH.IsActive = 1 AND GH.intGRNHeaderID = ? 
                    ORDER BY GH.intGRNHeaderID ";

            $query = $this->db->query($sql, array($GRNID));
            return $query->row_array();
        }



        $sql = "
                SELECT 
                GH.intGRNHeaderID, 
                GH.vcGRNNo, 
                GH.vcInvoiceNo, 
                GH.intSupplierID,
                S.vcSupplierName, 
                P.vcPaymentType,
                P.intPaymentTypeID,
                GH.decSubTotal, 
                IFNULL(GH.decDiscount,0.00) AS decDiscount, 
                GH.decGrandTotal, 
                CAST(GH.dtReceivedDate AS DATE) AS dtReceivedDate,  
                GH.vcRemark,
                GH.dtCreatedDate, 
                GH.intUserID AS CreatedUserID,
                CreatedUser.vcFullName AS CreatedUser, 
                GH.intApprovedBy,
                ApprovedUser.vcFullName AS ApprovedUser, 
                GH.dtApprovedOn,
                RejectedUser.vcFullName AS RejectedUser,
                GH.dtRejectedOn,
                IFNULL(SD.intSupplierSettlementHeaderID,'N/A') AS intSupplierSettlementHeaderID,
                SUM(SD.decPaidAmount) AS TotalPaidAmount
            FROM 
                GRNHeader AS GH
                INNER JOIN Supplier AS S ON GH.intSupplierID = S.intSupplierID
                INNER JOIN User AS CreatedUser ON GH.intUserID = CreatedUser.intUserID
                INNER JOIN paymenttype AS P ON GH.intPaymentTypeID = P.intPaymentTypeID
                LEFT OUTER JOIN User AS ApprovedUser ON GH.intApprovedBy = ApprovedUser.intUserID
                LEFT OUTER JOIN User AS RejectedUser ON GH.intRejectedBy = RejectedUser.intUserID
                LEFT OUTER JOIN suppliersettlementdetail AS SD ON GH.intGRNHeaderID = SD.intGRNHeaderID";


        $dateFilter = " WHERE GH.IsActive = 1 AND CAST(GH.dtCreatedDate AS DATE) BETWEEN ? AND ? ";


        if ($Status == 1) { // Approved
            $statusFilter = " AND GH.intApprovedBy IS NOT NULL ";
        } else if ($Status == 2) { // Pending
            $statusFilter = " AND GH.intApprovedBy IS NULL AND GH.intRejectedBy IS NULL ";
        } else if ($Status == 3) { // Rejected
            $statusFilter = " AND GH.intRejectedBy IS NOT NULL ";
        } else {  // All
            $statusFilter = "";
        }


        $sql  = $sql . $dateFilter . $statusFilter . "GROUP BY GH.intGRNHeaderID  ORDER BY GH.intGRNHeaderID DESC";

        $query = $this->db->query($sql, array($FromDate, $ToDate));
        return $query->result_array();
    }

    public function getGRNDetailData($GRNHeaderID)
    {
        $sql = "
                SELECT 
                    GD.intGRNDetailID,
                    GD.intGRNHeaderID,
                    GD.decQty,
                    GD.decUnitPrice,
                    GD.decTotalPrice,
                    I.intItemID,
                    I.vcItemName,
                    MU.intMeasureUnitID,
                    MU.vcMeasureUnit
                FROM 
                    GRNDetail AS GD
                    INNER JOIN Item AS I ON GD.intItemID = I.intItemID
                    INNER JOIN MeasureUnit AS MU ON I.intMeasureUnitID = MU.intMeasureUnitID
                WHERE 
                    GD.intGRNHeaderID = ?";

        $query = $this->db->query($sql, array($GRNHeaderID));
        return $query->result_array();
    }

    public function getItemWiseGrnPriceData($ItemID)
    {
        $sql = "SELECT  GH.vcGRNNo,GH.vcInvoiceNo,SU.vcSupplierName,GH.dtReceivedDate,GD.decQty,GD.decUnitPrice FROM grndetail AS GD
        INNER JOIN grnheader AS GH ON GD.intGRNHeaderID = GH.intGRNHeaderID 
        INNER JOIN supplier AS SU ON GH.intSupplierID = SU.intSupplierID
        where GD.intItemID = ? AND  GH.intApprovedBy IS NOT NULL
        ORDER BY GH.dtReceivedDate desc";
        $query = $this->db->query($sql, array($ItemID));
        return $query->result_array();
    }


    // Edit GRN

    public function editGRN($GRNHeaderID)
    {
        $this->db->trans_start();

        // $query = $this->db->query("SELECT fnGenerateGRNNo() AS GRNNo");
        // $ret = $query->row();
        // $GRNNo = $ret->GRNNo;


        $editDetails = false;
        $now = new DateTime();

        $data = array(
            'vcInvoiceNo' => $this->input->post('invoice_no'),
            'intSupplierID' => $this->input->post('supplier'),
            'dtReceivedDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('receivedDate')))),
            'intUserID' => $this->session->userdata('user_id'),
            'decSubTotal' => str_replace(',', '', $this->input->post('subTotal')),
            'decDiscount' => $this->input->post('txtDiscount'),
            'decGrandTotal' => str_replace(',', '', $this->input->post('grandTotal'))
        );

        $this->db->where('intGRNHeaderID', $GRNHeaderID);
        $this->db->update('GRNHeader', $data);

        $this->db->where('intGRNHeaderID', $GRNHeaderID);
        $this->db->delete('GRNDetail');

        $item_count = count($this->input->post('itemID'));

        for ($i = 0; $i < $item_count; $i++) {
            $qty = $this->input->post('itemQty')[$i];
            $items = array(
                'intGRNHeaderID' => $GRNHeaderID,
                'intItemID' => $this->input->post('itemID')[$i],
                'decQty' => $qty,
                'decUnitPrice' => $this->input->post('unitPrice')[$i],
                'decTotalPrice' => $this->input->post('totalPrice')[$i],
                'decAvailableQty' => $qty
            );
            $editDetails = $this->db->insert('GRNDetail', $items);
        }

        $this->db->trans_complete();

        return ($editDetails == true) ? true : false;
    }

    public function canRemoveGRN($intGRNHeaderID)
    {
        $sql = "
                SELECT * FROM GRNHeader WHERE intGRNHeaderID = ? AND intApprovedBy IS NULL AND intRejectedBy IS NULL";
        $query = $this->db->query($sql, array($intGRNHeaderID));
        if ($query->result_array() != null) {
            return true;
        } else {
            return false;
        }
    }

    public function removeGRN($intGRNHeaderID) 
    {
        if ($intGRNHeaderID) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intGRNHeaderID', $intGRNHeaderID);
            $delete = $this->db->update('GRNHeader', $data);
            return ($delete == true) ? true : false;
        }
    }

    public function approveGRN($intGRNHeaderID)
    {
        $this->db->trans_start();
        date_default_timezone_set('Asia/Colombo');
        $now = date('Y-m-d H:i:s');

        $data = array(
            'intApprovedBy' => $this->session->userdata('user_id'),
            'dtApprovedOn' => $now
        );
        $this->db->where('intGRNHeaderID', $intGRNHeaderID);
        $update = $this->db->update('GRNHeader', $data);

        // $sql = "UPDATE Item AS I
        //         INNER JOIN GRNDetail AS GD ON GD.intItemID = I.intItemID
        //         SET I.decStockInHand = (I.decStockInHand + GD.decQty)
        //         WHERE GD.intGRNHeaderID = ?";

        // $update = $this->db->query($sql, array($intGRNHeaderID));

        $this->db->trans_complete();
        return ($update == true) ? true : false;
    }

    public function rejectGRN($intGRNHeaderID)
    {
        $this->db->trans_start();
        date_default_timezone_set('Asia/Colombo');
        $now = date('Y-m-d H:i:s');

        $data = array(
            'intRejectedBy' => $this->session->userdata('user_id'),
            'dtRejectedOn' => $now
        );
        $this->db->where('intGRNHeaderID', $intGRNHeaderID);
        $update = $this->db->update('GRNHeader', $data);

        $this->db->trans_complete();
        return ($update == true) ? true : false;
    }
}
