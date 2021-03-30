<?php 

class Model_auth extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* 
		This function checks if the email exists in the database
	*/
	public function check_email($email) 
	{
		if($email) {
			$sql = 'SELECT * FROM User WHERE vcEmail = ?';
			$query = $this->db->query($sql, array($email));
			$result = $query->num_rows();
			return ($result == 1) ? true : false;
		}

		return false;
	}

	/* 
		This function checks if the user name exists in the database
	*/
	public function check_username($username)
	{
		if ($username) {
			$sql = 'SELECT * FROM User WHERE vcUserName = ?';
			$query = $this->db->query($sql, array($username));
			$result = $query->num_rows();
			return ($result == 1) ? true : false;
		}

		return false;
	}

	/* 
		This function checks if the user name and password matches with the database
	*/
	public function login($email, $password) {
		if($email && $password) {
			$sql = "SELECT 
						U.intUserID,
						U.vcUserName,
						U.vcPassword,
						U.vcFullName,
						U.vcEmail,
						U.vcContactNo,
						U.dtCreatedDate,
						U.vcCreatedUser,
						U.IsActive,
						U.intBranchID,
						UG.intUserGroupID,
						UG.vcGroupName,
						B.IsMainBranch
					FROM 
						User AS U
						INNER JOIN UserGroup AS UG ON U.intUserGroupID = UG.intUserGroupID
						INNER JOIN Branch AS B ON U.intBranchID = B.intBranchID
					WHERE 
						U.vcUserName = ?";
						
			$query = $this->db->query($sql, array($email));

			if($query->num_rows() == 1) {
				$result = $query->row_array();

				$hash_password = password_verify($password, $result['vcPassword']);
				if($hash_password === true) {
					return $result;	
				}
				else {
					return false;
				}				
			}
			else {
				return false;
			}
		}
	}

	public function getUserGroupByUserId($user_id)
	{
		$sql = "SELECT U.intUserID,U.isAdmin,UG.intUserGroupID,UG.vcGroupName,UG.vcPermission FROM User AS U
				INNER JOIN UserGroup AS UG ON U.intUserGroupID = UG.intUSerGroupID WHERE U.intUserID = ?";
		$query = $this->db->query($sql, array($user_id));
		$result = $query->row_array();

		return $result;
	}
}