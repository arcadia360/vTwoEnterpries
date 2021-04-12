<?php
class Model_utility extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPaymentTypes()
    {
        $sql = "SELECT intPaymentTypeID,vcPaymentType FROM paymenttype;";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function getPayModes()
    {
        $sql = "SELECT intPayModeID, vcPayMode FROM paymode";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getBanks()
    {
        $sql = "SELECT intBankID, vcBankName FROM bank";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getMainCategoryData($id = null, $isArray)
    {
        if ($id) {
            $sql = "SELECT intMainCategoryID, vcMainCategory FROM maincategory WHERE intMainCategoryID = ?";
            $query = $this->db->query($sql, array($id));
            if ($isArray == true) {
                return    $query->row_array();
            } else {
                return  $result = $query->result();
            }
        }

        $sql = "SELECT intMainCategoryID, vcMainCategory FROM maincategory";
        $query = $this->db->query($sql);
        if ($isArray == true) {
            return $query->result_array();
        } else {
            $result = $query->result();
            return $result;
        }
    }

    public function createMainCategory($data)
    {
        if ($data) {
            $insert = $this->db->insert('maincategory', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function updateMainCategory($data,$id)
    {
        if ($data && $id) {
            $this->db->where('intMainCategoryID', $id);
            $update = $this->db->update('maincategory', $data);
            return ($update == true) ? true : false;
        }
    }

    public function updateSubCategory($data,$id)
    {
        if ($data && $id) {
            $this->db->where('intSubcategoryID', $id);
            $update = $this->db->update('subcategory', $data);
            return ($update == true) ? true : false;
        }
    }

    public function createSubCategory($data)
    {
        if ($data) {
            $insert = $this->db->insert('subcategory', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function getSubCategoryData($id = null, $isArray)
    {
        if ($id) {
            $sql = "SELECT S.intSubCategoryID ,M.vcMainCategory,S.vcSubCategory FROM maincategory AS M
            INNER JOIN subcategory AS S ON M.intMainCategoryID = S.intMainCategoryID WHERE S.intSubCategoryID = ?";
            $query = $this->db->query($sql, array($id));
            if ($isArray == true) {
                return    $query->row_array();
            } else {
                return  $result = $query->result();
            }
        }

        $sql = "SELECT S.intSubCategoryID ,M.vcMainCategory,S.vcSubCategory FROM maincategory AS M
        INNER JOIN subcategory AS S ON M.intMainCategoryID = S.intMainCategoryID";
        $query = $this->db->query($sql);
        if ($isArray == true) {
            return $query->result_array();
        } else {
            $result = $query->result();
            return $result;
        }
    }

    
}
