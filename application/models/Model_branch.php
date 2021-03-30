<?php

class Model_branch extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('branch', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('intBranchID', $id);
            $update = $this->db->update('branch', $data);
            return ($update == true) ? true : false;
        }
    }

    /* get the Supplier data */
    public function getBranchData($id = null)
    {
        if ($id) {
            $sql = "SELECT intBranchID,vcBranchName,vcAddress,vcContactNo FROM branch WHERE intBranchID = ? AND IsActive = 1";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT intBranchID,vcBranchName,vcAddress,vcContactNo FROM branch WHERE IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();

    }

    public function getComboBranch() 
	{
		$sql = "SELECT intBranchID,vcBranchName FROM branch WHERE IsActive = 1";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}


    public function remove($id)
    {
        if ($id) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intBranchID', $id);
            $delete = $this->db->update('branch', $data);
            return ($delete == true) ? true : false;
        }
    }

   
}
