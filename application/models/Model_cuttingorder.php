<?php
class Model_cuttingorder extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //-----------------------------------
    // Create Cutting Order
    //-----------------------------------

    public function SaveCuttingOrder()
    {
        $this->db->trans_start();

        $insertDetails = false;
        $insert = false;

        $data = array(
            'vcOrderName' => $this->input->post('cutting_order_name'),
            'intUserID' => $this->session->userdata('user_id'),
        );

        $insert = $this->db->insert('CuttingOrderHeader', $data);
        $CuttingOrderHeaderID = $this->db->insert_id();

        $item_count = count($this->input->post('itemID'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intCuttingOrderHeaderID' => $CuttingOrderHeaderID,
                'intItemID' => $this->input->post('itemID')[$i],
                'decQty' => $this->input->post('qty')[$i]
            );
            $insertDetails = $this->db->insert('CuttingOrderDetail', $items);
        }

        $this->db->trans_complete();

        return ($insertDetails == true && $insert == true) ? true : false;
    }


    public function editCuttingOrder($intCuttingOrderHeaderID)
    {
        $this->db->trans_start();

        $editDetails = false;

        $data = array(
            'vcOrderName' => $this->input->post('edit_order_name'),
            'intUserID' => $this->session->userdata('user_id'),
        );

        $this->db->where('intCuttingOrderHeaderID', $intCuttingOrderHeaderID);
        $this->db->update('CuttingOrderHeader', $data);

        $this->db->where('intCuttingOrderHeaderID', $intCuttingOrderHeaderID);
        $this->db->delete('CuttingOrderDetail');

        $item_count = count($this->input->post('itemID'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intCuttingOrderHeaderID' => $intCuttingOrderHeaderID,
                'intItemID' => $this->input->post('itemID')[$i], 
                'decQty' => $this->input->post('qty')[$i]
            );
            $editDetails = $this->db->insert('CuttingOrderDetail', $items);
        }

        $this->db->trans_complete();

        return ($editDetails == true) ? true : false;
    }


    public function getCuttingOrderHeaderData($intCuttingOrderHeaderID = null)
    {
        if ($intCuttingOrderHeaderID) {
            $sql = "SELECT CH.intCuttingOrderHeaderID , CH.vcOrderName , CH.dtCreatedDate, ltrim(U.vcFullName) AS vcFullName
            FROM CuttingOrderHeader CH
            INNER JOIN User U ON CH.intUserID = U.intUserID
            WHERE CH.IsActive = 1 AND CH.intCuttingOrderHeaderID = ?";
            $query = $this->db->query($sql, array($intCuttingOrderHeaderID));
            return    $query->row_array();
        }
        $sql = "SELECT CH.intCuttingOrderHeaderID , CH.vcOrderName , CH.dtCreatedDate, ltrim(U.vcFullName) AS vcFullName
        FROM CuttingOrderHeader CH
        INNER JOIN User U ON CH.intUserID = U.intUserID
        WHERE CH.IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getCuttingOrderDetailData($intCuttingOrderHeaderID = null)
    {
        if ($intCuttingOrderHeaderID) {
            $sql = "SELECT  CH.intCuttingOrderHeaderID, 
            CH.vcOrderName,
            CH.dtCreatedDate,
            CD.intCuttingOrderDetailID,
            IT.vcItemName,
            CD.decQty,
            IT.intItemID
              FROM CuttingOrderHeader CH
              INNER JOIN cuttingorderdetail CD ON CH.intCuttingOrderHeaderID = CD.intCuttingOrderHeaderID
              INNER JOIN item AS IT ON CD.intItemID = IT.intItemID
              WHERE CH.IsActive = 1 AND CH.intCuttingOrderHeaderID = ?";
            $query = $this->db->query($sql, array($intCuttingOrderHeaderID));
            return  $query->result_array();
        }
    }

    public function chkCanRemoveCuttingOrder($intCuttingOrderHeaderID)
    {
        if ($intCuttingOrderHeaderID) {
            $sql = "SELECT EXISTS(SELECT intCuttingOrderHeaderID  FROM cuttingorderconfiguration WHERE intCuttingOrderHeaderID = ?) AS value";
            $query = $this->db->query($sql, array($intCuttingOrderHeaderID));
            return $query->result_array();
        }
    }

    public function chkAlreadyDispatched($ItemID)
    {
        if ($ItemID) {
            $sql = "SELECT EXISTS(SELECT intDispatchDetailID  FROM dispatchdetail WHERE intItemID = ?) AS value";
            $query = $this->db->query($sql, array($ItemID));
            return $query->result_array();
        }
    }

    public function chkCanRemoveCuttingOrderConfig($ItemID,$CuttingOrderHeaderID)
    {
        if ($CuttingOrderHeaderID) {
            $sql = "SELECT EXISTS(SELECT intDispatchDetailID  FROM dispatchdetail WHERE intItemID = ? AND intCuttingOrderHeaderID = ?)  as value";
            $query = $this->db->query($sql, array($ItemID,$CuttingOrderHeaderID));
            return $query->result_array();
        }
    }

    public function DeleteConfigCuttingOrderUsingFunction($ItemID,$CuttingOrderHeaderID)
    {
        $sql = "UPDATE `cuttingorderconfiguration` SET `IsActive` = '0' 
        WHERE `intItemID` =  $ItemID AND `intCuttingOrderHeaderID` =  $CuttingOrderHeaderID ";

        $update = $this->db->query($sql);
        return ($update == true) ? true : false;
    }

    public function SaveConfigCuttingOrderUsingFunction($ItemID,$CuttingOrderHeaderID)
    {
        $this->db->trans_start();

        $data = [
            'intItemID' =>  $ItemID ,
            'intCuttingOrderHeaderID' => $CuttingOrderHeaderID,
            'intUserID' =>  $this->session->userdata('user_id'),
        ];

        $insertDetails = $this->db->insert('cuttingorderconfiguration', $data);

        $this->db->trans_complete();

        return ($insertDetails == true) ? true : false;
    }

    public function removeCuttingOrder($intCuttingOrderHeaderID = null)
    {
        if ($intCuttingOrderHeaderID) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intCuttingOrderHeaderID', $intCuttingOrderHeaderID);
            $delete = $this->db->update('CuttingOrderHeader', $data);
            return ($delete == true) ? true : false;
        }
    }

    //-----------------------------------
    // Create Cutting Order - Configuration
    //-----------------------------------

    public function cuttingOrderHeaderData()
    {
        $sql = "SELECT OH.intCuttingOrderHeaderID , OH.vcOrderName, OH.dtCreatedDate , U.vcFullName  
        FROM CuttingOrderHeader OH
        INNER JOIN user U ON OH.intUserID = U.intUserID
        WHERE OH.IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function SaveCuttingOrderConfiguration()
    {
        $this->db->trans_start();

        $insertDetails = false;

        $item_count = count($this->input->post('cuttingorderID'));
        for ($i = 0; $i < $item_count; $i++) {
            $item_ID = $this->input->post('itemID')[$i];
        break;
        }
     
        $data = [
            'IsActive' => '0',
        ];
        $this->db->where('intItemID', $item_ID);
        $delete = $this->db->update('cuttingorderconfiguration', $data);

        // $this->db->where('intItemID', $item_ID);
        // $update = $this->db->update('cuttingorderconfiguration', array('IsActive' => '0'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intItemID' =>  $item_ID ,
                'intCuttingOrderHeaderID' => $this->input->post('cuttingorderID')[$i],
                'intUserID' => $this->session->userdata('user_id'),
            );
            $insertDetails = $this->db->insert('cuttingorderconfiguration', $items);
        }

        $this->db->trans_complete();

        return ($insertDetails == true) ? true : false;
    }

    public function getCuttingOrdersByItemID($ItemID)
    {
        $sql = "
                SELECT 
                    COC.intCuttingOrderHeaderID,
                    COH.vcOrderName 
                FROM CuttingOrderConfiguration AS COC
                INNER JOIN CuttingOrderHeader AS COH ON COC.intCuttingOrderHeaderID = COH.intCuttingOrderHeaderID
                WHERE COC.isActive = 1 AND COH.isActive = 1 AND COC.intItemID = ?
                ORDER BY COH.vcOrderName ASC";

        $query = $this->db->query($sql, array($ItemID));
        return $query->result_array();
    }

    public function fetchCuttingConfigDataByItemID($ItemID)
    {
        $sql = "
        SELECT CF.intCuttingOrderHeaderID,CH.vcOrderName 
        FROM cuttingorderconfiguration AS CF
        INNER JOIN cuttingorderheader AS CH ON CF.intCuttingOrderHeaderID = CH.intCuttingOrderHeaderID
        WHERE CF.intItemID = ? AND CF.IsActive = 1";

        $query = $this->db->query($sql, array($ItemID));
        return $query->result_array();
    }

}
