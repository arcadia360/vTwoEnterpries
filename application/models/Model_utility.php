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
}
