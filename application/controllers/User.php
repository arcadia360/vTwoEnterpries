<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->load->model('model_groups');
		$this->load->model('model_user');
		$this->load->model('model_branch');

		$user_group_data = $this->model_groups->getUserGroupData();
		$this->data['user_groups_data'] = $user_group_data;
	}

	public function index()
	{

		if (!$this->isAdmin) {
			if (!in_array('viewUser', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$userGroup = $this->model_groups->getComboUserGroups();
		$this->data['userGroup'] = $userGroup;
		$branch = $this->model_branch->getComboBranch();
		$this->data['branch'] = $branch;

		$this->render_template('user/manageUserAccount','Manage User Account', $this->data);
	}
	public function fetchUserDataById($id)
	{
		if ($id) {
			$data = $this->model_user->getUserData($id);
			echo json_encode($data);
		}

		return false;
	}

	public function fetchUserData()
	{

		if (!$this->isAdmin) {
			if (!in_array('viewUser', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$result = array('data' => array());

		$data = $this->model_user->getUserData();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';
			$viewAdmin = '';
			
			$viewAdmin = ($value['IsAdmin'] == 1) ? '<span class="badge badge badge-danger">Admin</span>' : '<span class="badge badge badge-warning">User</span>';

			if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editUser(' . $value['intUserID'] . ')" data-toggle="modal" data-target="#editUserModal"><i class="fas fa-edit"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeUser(' . $value['intUserID'] . ')" data-toggle="modal" data-target="#removeUserModal"><i class="fa fa-trash"></i></button>';
			} else {
				if (in_array('editUser', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-default" onclick="editUser(' . $value['intUserID'] . ')" data-toggle="modal" data-target="#editUserModal"><i class="fas fa-edit"></i></button>';
				}

				if (in_array('deleteUser', $this->permission)) {
					$buttons .= ' <button type="button" class="btn btn-default" onclick="removeUser(' . $value['intUserID'] . ')" data-toggle="modal" data-target="#removeUserModal"><i class="fa fa-trash"></i></button>';
				}
			}
			$result['data'][$key] = array(
				$value['vcUserName'],
				$value['vcFullName'],
				$value['vcEmail'],
				$value['vcContactNo'],
				$value['vcBranchName'],
				$value['vcGroupName'],
				$viewAdmin,
				$buttons
			);
		}

		echo json_encode($result);
	}

	public function password_hash($pass = '')
	{
		if ($pass) {
			$password = password_hash($pass, PASSWORD_DEFAULT);
			return $password;
		}
	}

	public function createUser()
	{

		if (!$this->isAdmin) {
			if (!in_array('createUser', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$response = array();

		$this->form_validation->set_rules('user_name', 'Username', 'trim|required|min_length[5]|max_length[12]|is_unique[user.vcUserName]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.vcEmail]');
		$this->form_validation->set_rules('contact_no', 'Contact No', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('branch', 'Branch', 'required');
		$this->form_validation->set_rules('user_group', 'Group', 'required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {
			$password = $this->password_hash($this->input->post('password'));
			$data = array(
				'vcUserName' => $this->input->post('user_name'),
				'vcPassword' =>  $password,
				'vcFullName' => $this->input->post('full_name'),
				'vcEmail' => $this->input->post('email'),
				'vcContactNo' => $this->input->post('contact_no'),
				'vcCreatedUser' => $this->session->userdata('user_name'),
				'intUserGroupID' => $this->input->post('user_group'),
				'IsAdmin' => $this->input->post('IsAdmin', TRUE) == null ? 0 : 1,
				'intBranchID' => $this->input->post('branch'),
			);
			$create = $this->model_user->createUser($data);
			if ($create == true) {
				$response['success'] = true;
				$response['messages'] = 'Succesfully created !';
			} else {
				$response['success'] = false;
				$response['messages'] = 'Error in the database while creating the brand information';
			}
		} else {
			$response['success'] = false;
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}

		echo json_encode($response);
	}

	public function editUser($id)
	{
		if (!$this->isAdmin) {
			if (!in_array('editUser', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$response = array();

		if ($id) {
			$this->form_validation->set_rules('edit_user_name', 'Username', 'trim|required|min_length[5]|max_length[12]');
			$this->form_validation->set_rules('edit_password', 'Password', 'trim');
			$this->form_validation->set_rules('edit_full_name', 'Full Name', 'trim|required');
			$this->form_validation->set_rules('edit_email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('edit_contact_no', 'Contact No', 'required|min_length[10]|max_length[10]');
			$this->form_validation->set_rules('edit_branch', 'Branch', 'required');
			$this->form_validation->set_rules('edit_user_group', 'Group', 'required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run() == TRUE) {

				if (empty($this->input->post('edit_password'))) {
					$data = array(
						'vcUserName' => $this->input->post('edit_user_name'),
						'vcFullName' => $this->input->post('edit_full_name'),
						'vcEmail' => $this->input->post('edit_email'),
						'vcContactNo' => $this->input->post('edit_contact_no'),
						'intUserGroupID' => $this->input->post('edit_user_group'),
						'IsAdmin' => $this->input->post('edit_IsAdmin', TRUE) == null ? 0 : 1,
						'intBranchID' => $this->input->post('edit_branch'),
					);
				} else {
					$password = $this->password_hash($this->input->post('edit_password'));
					$data = array(
						'vcUserName' => $this->input->post('edit_user_name'),
						'vcPassword' =>  $password,
						'vcFullName' => $this->input->post('edit_full_name'),
						'vcEmail' => $this->input->post('edit_email'),
						'vcContactNo' => $this->input->post('edit_contact_no'),
						'intUserGroupID' => $this->input->post('edit_user_group'),
						'IsAdmin' => $this->input->post('edit_IsAdmin', TRUE) == null ? 0 : 1,
						'intBranchID' => $this->input->post('edit_branch'),
					);
				}

				$update = $this->model_user->updateUser($data, $id);
				if ($update == true) {
					$response['success'] = true;
					$response['messages'] = 'Succesfully updated';
				} else {
					$response['success'] = false;
					$response['messages'] = 'Error in the database while updated the brand information';
				}
			} else {
				$response['success'] = false;
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	public function removeUser($intUserID = null)
	{
		if (!$this->isAdmin) {
			if (!in_array('deleteUser', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$intUserID = $this->input->post('intUserID');
		$response = array();
		if ($intUserID) {

			$delete = $this->model_user->removeUser($intUserID);

			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed !";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the brand information";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}
		echo json_encode($response);
	}
}
