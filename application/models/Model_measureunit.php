<?php

class Model_measureunit extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('measureunit', $data);
            return ($insert == true) ? true : false;
        }
    }

    /* get the get Measure Unit Data data */
    public function getMeasureUnitData($id = null, $isArray)
    {

        if ($id) {
            $sql = "SELECT intMeasureUnitID,vcMeasureUnit FROM measureunit WHERE intMeasureUnitID = ?";
            $query = $this->db->query($sql, array($id));
            if ($isArray == true) {
                return    $query->row_array();
            } else {
                return  $result = $query->result();
            }
        }

        $sql = "SELECT intMeasureUnitID,vcMeasureUnit FROM measureunit";
        $query = $this->db->query($sql);
        if ($isArray == true) {
            return $query->result_array();
        } else {
            $result = $query->result();
            return $result;
        }
    }

    public function getMeasureUnitByItemID($id)
    {
        $sql = "SELECT mu.intMeasureUnitID, mu.vcMeasureUnit from item IT inner join measureunit MU on IT.intMeasureUnitID = MU.intMeasureUnitID  where IT.intItemID = ?";
        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }

    /* get the get Item Type Data data */
    public function getItemTypeData($id = null, $isArray)
    {
        $sql = "SELECT intItemTypeID,vcItemTypeName FROM itemtype";
        $query = $this->db->query($sql);
        if ($isArray == true) {
            return $query->result_array();
        } else {
            $result = $query->result();
            return $result;
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('intMeasureUnitID', $id);
            $update = $this->db->update('measureunit', $data);
            return ($update == true) ? true : false;
        }
    }

    public function remove($id)
    {
        if ($id) {
            $this->db->where('intMeasureUnitID', $id);
            $delete = $this->db->delete('measureunit');
            return ($delete == true) ? true : false;
        }
    }

    public function chkexists($id = null)
    {
        if ($id) {
            $sql = "SELECT EXISTS(SELECT intMeasureUnitID  FROM item WHERE intMeasureUnitID = ?) AS value";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
    }
}
