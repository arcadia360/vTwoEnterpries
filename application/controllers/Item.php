<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->load->model('model_item');
		$this->load->model('model_measureunit');
		$this->load->model('model_groups');
		$this->load->model('model_utility');
		// $item_data = $this->model_item->getItemData();
		// $this->data['item_data'] = $item_data;
		// $user_group_data = $this->model_groups->getUserGroupData();
		// $this->data['user_groups_data'] = $user_group_data;

	}

	public function index()
	{
		if (!$this->isAdmin) {
			if (!in_array('viewItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$this->data["measureUnit"] = $this->model_measureunit->getMeasureUnitData(null, false);
		$this->data["main_cat"] = $this->model_utility->getMainCategoryData(null, false);
		// $this->data["itemTypeAll"] = $this->Model_measureunit->getItemTypeData(null, true);

		$this->render_template('item/manageItem', 'Manage Item', $this->data);
	}

	public function fetchItemDataById($id)
	{
		if ($id) {
			$data = $this->model_item->getItemData($id);
			echo json_encode($data);
		}

		return false;
	}

	public function fetchItemDataByItemTypeID($itemTypeID)
	{

		$data = $this->model_item->getItemDataByItemTypeID($itemTypeID);
		foreach ($data as $key => $value) {

			// button
			$buttons = '';
			$ReorderLevl = '';
			$UnitPrice = '';

			if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#editItemModal"><i class="fas fa-edit"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#removeItemModal"><i class="fa fa-trash"></i></button>';
			} else {
				if (in_array('editItem', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-default" onclick="editItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#editItemModal"><i class="fas fa-edit"></i></button>';
				}

				if (in_array('deleteItem', $this->permission)) {
					$buttons .= ' <button type="button" class="btn btn-default" onclick="removeItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#removeItemModal"><i class="fa fa-trash"></i></button>';
				}
			}

			$ReorderLevl =  '<p class="text-right">' . $value['decReOrderLevel'] . '</p>';
			$UnitPrice =  '<p class="text-right">' . $value['decUnitPrice'] . '</p>';

			$result['data'][$key] = array(
				$value['vcItemName'],
				$value['vcMeasureUnit'],
				$value['decStockInHand'],
				$ReorderLevl,
				$UnitPrice,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function fetchItemDetailsByCustomerID($ItemID, $customerID)
	{
		$data = $this->model_item->getItemDetailsByCustomerID($ItemID, $customerID);
		echo json_encode($data);
	}

	public function getfirstInFirstOut()
	{
		$intItemID = $this->input->post('intItemID');
		$data = $this->model_item->getfirstInFirstOut($intItemID);
		echo json_encode($data);
	}

	public function getStocktItemData()
	{
		$data = $this->model_item->getStocktItemData();
		echo json_encode($data);
	}

	public function getStockAvailableItemData()
	{

		if (!$this->isAdmin) {
			if (!in_array('viewItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$result = array('data' => array());

		$data = $this->model_item->getStocktItemData();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';
			$ReorderLevl = '';
			$UnitPrice = '';

			if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#editItemModal"><i class="fas fa-edit"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#removeItemModal"><i class="fa fa-trash"></i></button>';
			} else {
				if (in_array('editItem', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-default" onclick="editItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#editItemModal"><i class="fas fa-edit"></i></button>';
				}

				if (in_array('deleteItem', $this->permission)) {
					$buttons .= ' <button type="button" class="btn btn-default" onclick="removeItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#removeItemModal"><i class="fa fa-trash"></i></button>';
				}
			}

			$ReorderLevl =  '<p class="text-right">' . $value['decReOrderLevel'] . '</p>';
			$UnitPrice =  '<p class="text-right">' . $value['decUnitPrice'] . '</p>';

			$result['data'][$key] = array(
				$value['vcItemName'],
				$value['vcMeasureUnit'],
				$value['vcMainCategory'],
				$value['vcSubCategory'],
				$value['decStockInHand'],
				$ReorderLevl,
				$UnitPrice,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function remove($intItemID = null)
	{
		if (!$this->isAdmin) {
			if (!in_array('deleteItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$inItemID = $this->input->post('intItemID');
		$response = array();
		if ($inItemID) {

			$result = $this->model_item->chkexists($inItemID);

			if ($result <> '') {
				if ($result[0]['value'] == 1) {
					$response['success'] = false;
					$response['messages'] = "Record already received for the system, can't remove this Item !";
				} else {
					$delete = $this->model_item->remove($inItemID);
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

	public function create()
	{

		if (!$this->isAdmin) {
			if (!in_array('createItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$response = array();

		$this->form_validation->set_rules('Item_name', 'Item Name', 'trim|required|is_unique[item.vcItemName]');
		$this->form_validation->set_rules('measure_unit', 'Measure Unit', 'trim|required');
		$this->form_validation->set_rules('unit_price', 'Unit Price', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'vcItemName' => $this->input->post('Item_name'),
				'intMeasureUnitID' => $this->input->post('measure_unit'),
				'intSubCategoryID' => $this->input->post('sub_cat'),
				'decReOrderLevel' => $this->input->post('re_order'),
				'decUnitPrice' => $this->input->post('unit_price'),
				'intUserID' => $this->session->userdata('user_id'),
			);
			$create = $this->model_item->create($data);
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

	public function update($id)
	{

		if (!$this->isAdmin) {
			if (!in_array('editItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$response = array();

		if ($id) {
			$this->form_validation->set_rules('edit_item_name', 'Item Name', 'trim|required');
			$this->form_validation->set_rules('edit_measure_unit', 'Measure Unit', 'trim|required');

			$this->form_validation->set_rules('edit_unit_price', 'Measure Unit', 'trim|required');



			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'vcItemName' => $this->input->post('edit_item_name'),
					'intMeasureUnitID' => $this->input->post('edit_measure_unit'),
					'intSubCategoryID' => $this->input->post('edit_sub_cat'),
					'decReOrderLevel' => $this->input->post('edit_re_order'),
					'decUnitPrice' => $this->input->post('edit_unit_price'),
					'intUserID' => $this->session->userdata('user_id'),
				);
				// $currentRV = '';
				// $currentRV =  $this->input->post('edit_rv');

				// $previousRV = $this->model_item->chkRv($id);

				// if ($previousRV['rv'] != $currentRV) {
				// 	$response['success'] = false;
				// 	$response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
				// } else {

					$intEnteredBy = array(
						'intEnteredBy' => $this->session->userdata('user_id'),
					);

					$insertItemHitory = $this->model_item->insertItemHitory($intEnteredBy, $id);
					$update = $this->model_item->update($data, $id);

					if ($update == true && $insertItemHitory == true) {
						$response['success'] = true;
						$response['messages'] = 'Succesfully updated !';
					} else {
						$response['success'] = false;
						$response['messages'] = 'Error in the database while updated the brand information';
					}
				// }
			} else {
				$response['success'] = false;
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}
		}

		echo json_encode($response);
	}

	public function getMainCatWiseSubCat($MainCatID)
	{
		$response = array();

		$response = $this->model_item->getMainCatWiseSubCat($MainCatID);

		echo json_encode($response);
	}
}
