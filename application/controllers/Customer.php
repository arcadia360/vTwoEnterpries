<?php
class Customer extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->load->model('model_customer');
		$this->load->model('model_groups');
		$this->load->model('model_item');

		// $user_group_data = $this->model_groups->getUserGroupData();
		// $this->data['user_groups_data'] = $user_group_data;
	}

	public function CustomersList()
	{
		if (!$this->isAdmin) {
			if (!in_array('viewCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		// $this->load->view('partials/header');
		// $this->load->view('customer/manageCustomer',$this->data);
		// $this->load->view('partials/footer');

		$this->render_template('customer/manageCustomer', 'Manage Customer', $this->data);
	}


	public function create()
	{
		if (!$this->isAdmin) {
			if (!in_array('createCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$response = array();

		$this->form_validation->set_rules('customer_name', 'customer name', 'trim|required');
		$this->form_validation->set_rules('building_number', 'building number', 'trim|required');
		$this->form_validation->set_rules('street', 'street', 'trim|required');
		$this->form_validation->set_rules('city', 'city', 'trim|required');
		$this->form_validation->set_rules('contact_no_1', 'contact no', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('credit_limit', 'credit limit', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'vcCustomerName' => $this->input->post('customer_name'),
				'vcBuildingNumber' => $this->input->post('building_number'),
				'vcStreet' => $this->input->post('street'),
				'vcCity' => $this->input->post('city'),
				'vcContactNo1' => $this->input->post('contact_no_1'),
				'vcContactNo2' => $this->input->post('contact_no_2'),
				'decCreditLimit' => $this->input->post('credit_limit'),
				'decAvailableCredit' => $this->input->post('credit_limit'),
				'intUserID' => $this->session->userdata('user_id'),
			);
			$create = $this->model_customer->create($data);
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

	public function fetchCustomerDataById($id)
	{
		if ($id) {
			$data = $this->model_customer->getCustomerData($id);
			echo json_encode($data);
		}

		return false;
	}

	public function fetchCustomerData()
	{

		if (!$this->isAdmin) {
			if (!in_array('viewCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$result = array('data' => array());

		$data = $this->model_customer->getCustomerData();
		foreach ($data as $key => $value) {

			$buttons = '';

			if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#editCustomerModal"><i class="fas fa-edit"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#removeCustomerModal"><i class="fa fa-trash"></i></button>';
			} else {
				if (in_array('editCustomer', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-default" onclick="editCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#editCustomerModal"><i class="fas fa-edit"></i></button>';
				}

				if (in_array('deleteCustomer', $this->permission)) {
					$buttons .= ' <button type="button" class="btn btn-default" onclick="removeCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#removeCustomerModal"><i class="fa fa-trash"></i></button>';
				}
			}

			$result['data'][$key] = array(
				$value['vcCustomerName'],
				$value['vcBuildingNumber'],
				$value['vcStreet'],
				$value['vcCity'],
				$value['vcContactNo1'],
				$value['vcContactNo2'],
				$value['decCreditLimit'],
				$value['decAvailableCredit'],
				$buttons
			);
		}

		echo json_encode($result);
	}

	public function update($id)
	{

		if (!$this->isAdmin) {
			if (!in_array('editCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$response = array();

		if ($id) {
			$this->form_validation->set_rules('edit_customer_name', 'branch name', 'trim|required');
			$this->form_validation->set_rules('edit_building_number', 'building number', 'trim|required');
			$this->form_validation->set_rules('edit_street', 'street', 'trim|required');
			$this->form_validation->set_rules('edit_city', 'city', 'trim|required');
			$this->form_validation->set_rules('edit_contact_no_1', 'contact no', 'required|min_length[10]|max_length[10]');
			$this->form_validation->set_rules('edit_credit_limit', 'credit limit', 'trim|required');


			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			$CurrentData = $this->model_customer->getCustomerData($id);



			if ($this->form_validation->run() == TRUE) {

				$decCreditLimit = $CurrentData['decCreditLimit'];
				$decAvailableCredit = $CurrentData['decAvailableCredit'];
				$inputLimit = $this->input->post('edit_credit_limit');
				$ChangingValue = 0;

				if ($inputLimit >= $decCreditLimit) {
					$ChangingValue = $inputLimit - $decCreditLimit;
					$decCreditLimit =  $decCreditLimit + $ChangingValue;
					$decAvailableCredit = $decAvailableCredit + $ChangingValue;
				} else {
					$ChangingValue = $decCreditLimit - $inputLimit;
					$decCreditLimit = $decCreditLimit - $ChangingValue;
					$decAvailableCredit = $decAvailableCredit - $ChangingValue;
				}

				$data = array(
					'vcCustomerName' => $this->input->post('edit_customer_name'),
					'vcBuildingNumber' => $this->input->post('edit_building_number'),
					'vcStreet' => $this->input->post('edit_street'),
					'vcCity' => $this->input->post('edit_city'),
					'vcContactNo1' => $this->input->post('edit_contact_no_1'),
					'vcContactNo2' => $this->input->post('edit_contact_no_2'),
					'decCreditLimit' => $decCreditLimit,
					'decAvailableCredit' => $decAvailableCredit,
				);

				$intEnteredBy = array(
					'intEnteredBy' => $this->session->userdata('user_id'),
				);

				$insertCustomerHitory = $this->model_customer->insertCustomerHitory($intEnteredBy, $id);

				$update = $this->model_customer->update($data, $id);

				if ($update == true && $insertCustomerHitory == true) {
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

	public function remove($intCustomerID = null)
	{

		if (!$this->isAdmin) {
			if (!in_array('deleteCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$intCustomerID = $this->input->post('intCustomerID');
		$response = array();
		if ($intCustomerID) {

		 $result = $this->model_customer->chkexists($intCustomerID);
			$result = '';
			if ($result <> '') {
				if ($result[0]['value'] == 1) {
					$response['success'] = false;
					$response['messages'] = "Record already received for the system, can't remove this customer !";
				} else {
					$delete = $this->model_customer->removeCustomer($intCustomerID);
					if ($delete == true) {
						$response['success'] = true;
						$response['messages'] = "Successfully removed !";
					} else {
						$response['success'] = false;
						$response['messages'] = "Error in the database while removing the Supplier information";
					}
				}
			}
			echo json_encode($response);
		}
	}

	//-----------------------------------
	// Customer Unit Price - Configuration
	//-----------------------------------

	public function manageCustomerUnitPriceConfig()
	{
		if (!$this->isAdmin) {
			if (!in_array('createIssue', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$customer_data = $this->model_customer->getCustomerData();
		$item_data = $this->model_item->getOnlyCmbItemData();


		$this->data['customer_data'] = $customer_data;
		$this->data['item_data'] = $item_data;

		$this->render_template('Customer/ManageCustomerUnitPriceConfig', 'Customer Unit Price Config',  $this->data);
	}

	public function fetchCustomerPriceConfigData($CustomerID = null)
	{

		if (!$this->isAdmin) {
			if (!in_array('viewCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$result = array('data' => array());

		$data = $this->model_customer->getCustomerPriceConfigData(null, $CustomerID);
		foreach ($data as $key => $value) {

			$buttons = '';

			if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editCustomerUnitPrice(' . $value['intCustomerPriceConfigID'] . ')" data-toggle="modal" data-target="#editCustomerModal"><i class="fas fa-edit"></i></button>';
				// $buttons .= ' <button type="button" class="btn btn-default" onclick="removeCustomerUnitPrice(' . $value['intCustomerPriceConfigID'] . ')" data-toggle="modal" data-target="#removeCustomerModal"><i class="fa fa-trash"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" id="btnRemoveCustomerUnitPrice" onclick="RemoveCustomerUnitPrice(' . $value['intCustomerPriceConfigID'] . ')"><i class="fa fa-trash"></i></button>';
			} else {
				if (in_array('editCustomer', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-default" onclick="editCustomerUnitPrice(' . $value['intCustomerPriceConfigID'] . ')" data-toggle="modal" data-target="#editCustomerModal"><i class="fas fa-edit"></i></button>';
				}

				if (in_array('deleteCustomer', $this->permission)) {
					// $buttons .= ' <button type="button" class="btn btn-default" onclick="removeCustomerUnitPrice(' . $value['intCustomerPriceConfigID'] . ')" data-toggle="modal" data-target="#removeCustomerModal"><i class="fa fa-trash"></i></button>';
					$buttons .= ' <button type="button" class="btn btn-default" id="btnRemoveCustomerUnitPrice" onclick="RemoveCustomerUnitPrice(' . $value['intCustomerPriceConfigID'] . ')"><i class="fa fa-trash"></i></button>';
				}
			}

			$result['data'][$key] = array(
				$value['vcCustomerName'],
				$value['vcItemName'],
				$value['decUnitPrice'],
				$buttons
			);
		}

		echo json_encode($result);
	}

	public function fetchCustomerPriceConfigById($CustomerPriceConfigID)
	{
		if ($CustomerPriceConfigID) {
			$data = $this->model_customer->getCustomerPriceConfigData($CustomerPriceConfigID);
			echo json_encode($data);
		}

		return false;
	}

	public function getNotConfiguredItems($CustomerID)
	{
		$response = array();

		$response = $this->model_customer->getNotConfiguredItems($CustomerID);

		echo json_encode($response);
	}

	public function SaveCustomerPriceConfig()
	{

		if (!$this->isAdmin) {
			if (!in_array('createCustomerPriceConfig', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$response = array();

		$result = $this->model_customer->SaveCustomerPriceConfig();
		if ($result == true) {
			$response['success'] = true;
		} else {
			$response['success'] = false;
			$response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
		}
		echo json_encode($response);
	}

	public function UpdateCustomerPriceConfig($CustomerPriceConfigID)
	{
		if (!$this->isAdmin) {
			if (!in_array('editCustomerPriceConfig', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$response = array();

		if ($CustomerPriceConfigID) {

			$this->form_validation->set_rules('edit_unit_price', 'Unit Price', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run() == TRUE) {

				$update = $this->model_customer->UpdateCustomerPriceConfig($CustomerPriceConfigID);

				if ($update == true) {
					$response['success'] = true;
					$response['messages'] = 'Succesfully updated !';
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
		}

		echo json_encode($response);
	}

	public function RemoveCustomerUnitPrice()
	{
		if (!$this->isAdmin) {
			if (!in_array('deleteCustomerPriceConfig', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$intCustomerPriceConfigID = $this->input->post('intCustomerPriceConfigID');
		$response = array();

		if ($intCustomerPriceConfigID) {

			$delete = $this->model_customer->RemoveCustomerUnitPrice($intCustomerPriceConfigID);

			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Deleted !";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the Request information !";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Please refersh the page again !!";
		}
		echo json_encode($response);
	}

	//-----------------------------------
	// Customer Advance Payemnt
	//-----------------------------------

	public function manageCustomerAdvancePayment()
	{
		if (!$this->isAdmin) {
			if (!in_array('createCustomerAdvancePayment', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$customer_data = $this->model_customer->getCustomerData();
		$advance_customer_data = $this->model_customer->getAdvanceAllowCustomers();


		$this->data['customer_data'] = $customer_data;
		$this->data['advance_customer_data'] = $advance_customer_data;

		$this->render_template('Customer/ManageCustomerAdvancePayment', 'Customer Advance Payment',  $this->data);
	}

	public function fetchCustomerAdvancePaymentData($CustomerID)
	{

		if (!$this->isAdmin) {
			if (!in_array('viewCustomerAdvancePayment', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$result = array('data' => array());

		$data = $this->model_customer->getCustomerAdvancePaymentData(null, $CustomerID);
		foreach ($data as $key => $value) {

			$buttons = '';

			if ($this->isAdmin) {
				if ($value['vcIssueNo'] == 'N/A') {
					// $buttons .= '<button type="button" class="btn btn-default" onclick="editCustomerAdvancePayment(' . $value['intCustomerAdvancePaymentID'] . ')" data-toggle="modal" data-target="#editCustomerModal"><i class="fas fa-edit"></i></button>';
					$buttons .= ' <button type="button" class="btn btn-default" id="btnRemoveCustomerAdvancePayment" onclick="RemoveCustomerAdvancePayment(' . $value['intCustomerAdvancePaymentID'] . ',\'' . $value['rv'] . '\')"><i class="fa fa-trash"></i></button>';
				}
			} else {
				if ($value['vcIssueNo'] == 'N/A') {
					if (in_array('editCustomerAdvancePayment', $this->permission)) {
						// $buttons .= '<button type="button" class="btn btn-default" onclick="editCustomerAdvancePayment(' . $value['intCustomerAdvancePaymentID'] . ')" data-toggle="modal" data-target="#editCustomerModal"><i class="fas fa-edit"></i></button>';
					}

					if (in_array('deleteCustomerAdvancePayment', $this->permission)) {
						$buttons .= ' <button type="button" class="btn btn-default" id="btnRemoveCustomerAdvancePayment" onclick="RemoveCustomerAdvancePayment(' . $value['intCustomerAdvancePaymentID'] . ',\'' . $value['rv'] . '\')"><i class="fa fa-trash"></i></button>';
					}
				}
			}

			$result['data'][$key] = array(
				$value['vcCustomerName'],
				$value['dtAdvanceDate'],
				$value['decAmount'],
				$value['vcRemark'],
				$value['vcIssueNo'],
				$value['dtCreatedDate'],
				$value['vcFullName'],
				$buttons,
			);
		}

		echo json_encode($result);
	}

	public function RemoveCustomerAdvancePayment()
	{
		if (!$this->isAdmin) {
			if (!in_array('deleteCustomerPriceConfig', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$intCustomerAdvancePaymentID = $this->input->post('intCustomerAdvancePaymentID');
		$rv = $this->input->post('rv');
		$response = array();

		$CurrentRV = $this->model_customer->getCustomerAdvancePaymentData($intCustomerAdvancePaymentID);

		if ($CurrentRV['rv']  != $rv) {
			$response['success'] = false;
			$response['messages'] = 'Another user tries to edit this Data, please refresh the page and try again !';
		} else {
			if ($intCustomerAdvancePaymentID) {

				$delete = $this->model_customer->RemoveCustomerAdvancePayment($intCustomerAdvancePaymentID);

				if ($delete == true) {
					$response['success'] = true;
					$response['messages'] = "Deleted !";
				} else {
					$response['success'] = false;
					$response['messages'] = "Error in the database while removing the Request information !";
				}
			} else {
				$response['success'] = false;
				$response['messages'] = "Please refersh the page again !!";
			}
		}

		echo json_encode($response);
	}

	public function SaveCustomerAdvancePayment()
	{
		if (!$this->isAdmin) {
			if (!in_array('createCustomerPriceConfig', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$response = array();

		$canAdd = $this->model_customer->chkIssueIdIsnull($this->input->post('cmbCustomer'));

		if ($canAdd) {
			$response['success'] = false;
			$response['messages'] = 'Already Added Advance Payment.';
		} else {
			$result = $this->model_customer->SaveCustomerAdvancePayment();

			if ($result == true) {
				$response['success'] = true;
			} else {
				$response['success'] = false;
				$response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
			}
		}

		echo json_encode($response);
	}
}
