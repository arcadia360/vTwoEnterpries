<?php
class Model_user extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function createUser($data)
    {
        if ($data) {
            $insert = $this->db->insert('user', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function updateUser($data, $id)
    {
        if ($data && $id) {
            $this->db->where('intUserID', $id);
            $update = $this->db->update('user', $data);
            return ($update == true) ? true : false;
        }
    }

    /* get the Supplier data */
    public function getUserData($id = null)
    {
        if ($id) {
            $sql = "SELECT intUserID, vcUserName,vcPassword, vcFullName, vcEmail, vcContactNo, intUserGroupID, IsAdmin, intBranchID FROM user WHERE intUserID = ? AND IsActive = 1";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT U.intUserID, U.vcUserName, U.vcFullName, U.vcEmail, U.vcContactNo, U.intUserGroupID,UG.vcGroupName, U.IsAdmin, B.vcBranchName
        FROM user U
        INNER JOIN usergroup UG ON U.intUserGroupID = UG.intUserGroupID
        INNER JOIN branch B ON U.intBranchID = B.intBranchID
        WHERE U.IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();

    }


    public function removeUser($id)
    {
        if ($id) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intUserID', $id);
            $delete = $this->db->update('user', $data);
            return ($delete == true) ? true : false;
        }
    }

   
}
