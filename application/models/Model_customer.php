<?php
class Model_customer extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    
    }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('customer', $data);
            return ($insert == true) ? true : false;
        }
    }

    /* get the Customer data */
    public function getCustomerData($id = null)
    {
        if ($id) {
            $sql = "SELECT cs.intCustomerID,cs.vcCustomerName,cs.vcBuildingNumber,cs.vcStreet,cs.vcCity,cs.vcContactNo1,cs.vcContactNo2,cs.decCreditLimit,cs.decAvailableCredit,IFNULL(ca.decAmount,0.00) as decAdvanceAmount, (cs.decAvailableCredit + IFNULL(ca.decAmount,0.00)) AS decCreditBuyAmount
            FROM customer cs
            LEFT OUTER JOIN customeradvancepayment as ca on cs.intCustomerID = ca.intCustomerID AND ca.intIssueHeaderID IS NULL WHERE cs.intCustomerID = ? AND cs.IsActive = 1";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT intCustomerID,vcCustomerName,vcBuildingNumber,vcStreet,vcCity,vcContactNo1,IFNULL(vcContactNo2,'N/A') AS vcContactNo2,decCreditLimit,decAvailableCredit FROM customer WHERE IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function insertCustomerHitory($intEnteredBy, $id)
    {
        $this->db->trans_start();
        $sql = "SELECT intCustomerID, vcCustomerName, vcBuildingNumber, vcStreet, vcCity, vcContactNo1, vcContactNo2, dtCreatedDate, decCreditLimit, decAvailableCredit, IsActive, intUserID FROM customer WHERE intCustomerID = ? ";
        $query = $this->db->query($sql, array($id));
        if ($query->num_rows()) {
            $this->db->insert('customer_his', $query->row_array());
            $insert_id = $this->db->insert_id();
            $this->db->where('intCustomer_hisID', $insert_id);
            $update = $this->db->update('customer_his', $intEnteredBy);
            $this->db->trans_complete();
            return ($update == true) ? true : false;
        }
       
    }
    public function chkexists($id = null)
    {
        if ($id) {
            $sql = "SELECT EXISTS(SELECT intCustomerID  FROM issu WHERE intCustomerID = ?) AS value";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
	}

    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('intCustomerID', $id);
            $update = $this->db->update('customer', $data);
            return ($update == true) ? true : false;
        }
    }

    //-----------------------------------
    // Customer Unit Price - Configuration
    //-----------------------------------


    public function getCustomerPriceConfigData($CustomerPriceConfigID = null, $CustomerID = null)
    {

        if ($CustomerPriceConfigID) {
            $sql = "SELECT cc.intCustomerPriceConfigID, cc.intItemID,it.vcItemName,c.vcCustomerName,cc.decUnitPrice
            from customerpriceconfig as cc
            inner join customer as c on cc.intCustomerID = c.intCustomerID
            inner join item as it on cc.intItemID = it.intItemID
            WHERE cc.intCustomerPriceConfigID = ? ";
            $query = $this->db->query($sql, array($CustomerPriceConfigID));
            return $query->row_array();
        }
        if ($CustomerID) {
            $sql = "SELECT cc.intCustomerPriceConfigID, cc.intItemID,it.vcItemName,c.vcCustomerName,cc.decUnitPrice
            from customerpriceconfig as cc
            inner join customer as c on cc.intCustomerID = c.intCustomerID
            inner join item as it on cc.intItemID = it.intItemID
            where c.intCustomerID = ? ";
            $query = $this->db->query($sql, array($CustomerID));
            return $query->result_array();
        }

        $sql = "SELECT cc.intCustomerPriceConfigID, cc.intItemID,it.vcItemName,c.vcCustomerName,cc.decUnitPrice
                from customerpriceconfig as cc
                inner join customer as c on cc.intCustomerID = c.intCustomerID
                inner join item as it on cc.intItemID = it.intItemID";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getNotConfiguredItems($CustomerID)
    {
        $sql = "SELECT it.intItemID , it.vcItemName
        from item as it
        left outer join customerpriceconfig as cc on it.intItemID = cc.intItemID
        where it.intItemID not in (select intItemID from  customerpriceconfig where intCustomerID = ? )";
        $query = $this->db->query($sql, array($CustomerID));
        return $query->result_array();
    }

    public function SaveCustomerPriceConfig()
    {
        $this->db->trans_start();

        $insert = false;


        $item_count = count($this->input->post('itemID'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intCustomerID' => $this->input->post('cmbCustomer'),
                'intItemID' => $this->input->post('itemID')[$i],
                'decUnitPrice' => $this->input->post('unitPrice')[$i],
                'intUserID' => $this->session->userdata('user_id'),
            );
            $insert = $this->db->insert('customerpriceconfig', $items);
        }

        $this->db->trans_complete();

        return ($insert == true) ? true : false;
    }

    public function UpdateCustomerPriceConfig($CustomerPriceConfigID)
    {
        $this->db->trans_start();
        $sql = "SELECT intCustomerPriceConfigID, intCustomerID, intItemID, decUnitPrice, dtCreatedDate, intUserID FROM customerpriceconfig WHERE intCustomerPriceConfigID = ? ";
        $query = $this->db->query($sql, array($CustomerPriceConfigID));
        if ($query->num_rows()) {
            $this->db->insert('customerpriceconfig_his', $query->row_array());
            $insert_id = $this->db->insert_id();
            $this->db->where('intCustomerPriceConfig_hisID', $insert_id);
            $update = $this->db->update('customerpriceconfig_his', array('intEnteredBy' => $this->session->userdata('user_id')));

            $this->db->where('intCustomerPriceConfigID', $CustomerPriceConfigID);
            $update = $this->db->update('customerpriceconfig', array('decUnitPrice' => $this->input->post('edit_unit_price')));

            $this->db->trans_complete();
            return ($update == true) ? true : false;
        }
    }

    public function RemoveCustomerUnitPrice($intCustomerPriceConfigID)
    {
        if ($intCustomerPriceConfigID) {
            $this->db->trans_start();
            $sql = "SELECT intCustomerPriceConfigID, intCustomerID, intItemID, decUnitPrice, dtCreatedDate, intUserID FROM customerpriceconfig WHERE intCustomerPriceConfigID = ? ";
            $query = $this->db->query($sql, array($intCustomerPriceConfigID));
            if ($query->num_rows()) {
                $this->db->insert('customerpriceconfig_his', $query->row_array());
                $insert_id = $this->db->insert_id();
                $this->db->where('intCustomerPriceConfig_hisID', $insert_id);
                $update = $this->db->update('customerpriceconfig_his', array('intEnteredBy' => $this->session->userdata('user_id')));
            }
            $this->db->where('intCustomerPriceConfigID', $intCustomerPriceConfigID);
            $delete = $this->db->delete('customerpriceconfig');
            $this->db->trans_complete();
            return ($delete == true) ? true : false;
        }
    }

    //-----------------------------------
	// Customer Advance Payemnt
    //-----------------------------------
    
    public function getAdvanceAllowCustomers()
    {
        $sql = "SELECT DISTINCT CS.intCustomerID , CS.vcCustomerName
        FROM customer AS CS
        LEFT OUTER JOIN customeradvancepayment AS CA ON CA.intCustomerID = CS.intCustomerID
        Where CA.intIssueHeaderID is not null or  NOT EXISTS
                (
                SELECT  null 
                FROM    customeradvancepayment c
                WHERE   c.intCustomerID = cs.intCustomerID
                )";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getCustomerAdvancePaymentData($CustomerAdvancePaymentID = null, $CustomerID = null)
    {
        if ($CustomerAdvancePaymentID) {
            $sql = "SELECT intCustomerAdvancePaymentID, intCustomerID, decAmount, vcRemark, CAST(dtAdvanceDate AS DATE) AS dtAdvanceDate, dtCreatedDate, intUserID, intIssueHeaderID, REPLACE(rv,' ','-') as rv 
            FROM customeradvancepayment
            WHERE intCustomerAdvancePaymentID = ?";
            $query = $this->db->query($sql, array($CustomerAdvancePaymentID));
            return $query->row_array();
        }
        if ($CustomerID) {
            $sql = "SELECT CA.intCustomerAdvancePaymentID,C.vcCustomerName,CAST(CA.dtAdvanceDate AS DATE) AS dtAdvanceDate,CA.decAmount,IFNULL(CA.vcRemark,'N/A') AS vcRemark,IFNULL(IH.vcIssueNo,'N/A') AS vcIssueNo,CA.dtCreatedDate,REPLACE(CA.rv,' ','-') as rv,U.vcFullName
            FROM customeradvancepayment AS CA
            INNER JOIN customer AS C ON CA.intCustomerID = C.intCustomerID
            INNER JOIN user AS U ON CA.intUserID = U.intUserID
            LEFT OUTER JOIN issueheader AS IH ON CA.intIssueHeaderID = IH.intIssueHeaderID
            WHERE CA.intCustomerID = ?
            ORDER BY CA.dtCreatedDate DESC";
            $query = $this->db->query($sql, array($CustomerID));
            return $query->result_array();
        }
        else{
            $sql = "SELECT CA.intCustomerAdvancePaymentID,C.vcCustomerName,CAST(CA.dtAdvanceDate AS DATE) AS dtAdvanceDate,CA.decAmount,IFNULL(CA.vcRemark,'N/A') AS vcRemark,IFNULL(IH.vcIssueNo,'N/A') AS vcIssueNo,CA.dtCreatedDate,REPLACE(CA.rv,' ','-') as rv,U.vcFullName
            FROM customeradvancepayment AS CA
            INNER JOIN customer AS C ON CA.intCustomerID = C.intCustomerID
            INNER JOIN user AS U ON CA.intUserID = U.intUserID
            LEFT OUTER JOIN issueheader AS IH ON CA.intIssueHeaderID = IH.intIssueHeaderID
            ORDER BY CA.dtCreatedDate DESC";
            $query = $this->db->query($sql);
            return $query->result_array();
        }

        
    }

    public function RemoveCustomerAdvancePayment($intCustomerAdvancePaymentID)
    {
        if ($intCustomerAdvancePaymentID) {
            $this->db->trans_start();
            $sql = "SELECT * FROM customeradvancepayment WHERE intCustomerAdvancePaymentID = ? ";
            $query = $this->db->query($sql, array($intCustomerAdvancePaymentID));
            if ($query->num_rows()) {
                $this->db->insert('customeradvancepayment_his', $query->row_array());
                $insert_id = $this->db->insert_id();
                $this->db->where('intCustomeradvancepayment_hisID', $insert_id);
                $update = $this->db->update('customeradvancepayment_his', array('intEnteredBy' => $this->session->userdata('user_id')));
            }
            $this->db->where('intCustomerAdvancePaymentID', $intCustomerAdvancePaymentID);
            $delete = $this->db->delete('customeradvancepayment');
            $this->db->trans_complete();
            return ($delete == true) ? true : false;
        }
    }

    public function chkIssueIdIsnull($CustomerID)
    {
        $sql = " SELECT CA.intCustomerAdvancePaymentID FROM customeradvancepayment AS CA WHERE CA.intIssueHeaderID IS NULL AND CA.intCustomerID = ?";
        $query = $this->db->query($sql, array($CustomerID));
        if ($query->result_array() != null) {
            return true;
        }else{
            return false;
        }
    }

    public function SaveCustomerAdvancePayment()
    {
        $this->db->trans_start();

        $insert = false;

            $items = array(
                'intCustomerID' => $this->input->post('cmbCustomer'),
                'decAmount' => $this->input->post('advance_amount'),
                'vcRemark' => $this->input->post('remark') == "" ? NULL : $this->input->post('remark'),
                'dtAdvanceDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('advanceDate')))),
                'intUserID' => $this->session->userdata('user_id'),
            );
            $insert = $this->db->insert('customeradvancepayment', $items);
        
        $this->db->trans_complete();

        return ($insert == true) ? true : false;
    }
}
