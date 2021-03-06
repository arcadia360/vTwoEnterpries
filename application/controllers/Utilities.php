<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Utilities extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();
        $this->data['page_title'] = 'User Group';
        $this->load->model('model_groups');
        $this->load->model('model_measureunit');
        $this->load->model('model_item');
        $this->load->model('model_cuttingorder');
        $this->load->model('model_utility');

        // $user_group_data = $this->model_groups->getUserGroupData();
        // $this->data['user_groups_data'] = $user_group_data;
    }

    public function fetchUserGroupData()
    {

        if (!$this->isAdmin) {
            if (!in_array('viewUserGroupData', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());

        $data = $this->model_groups->getUserGroupData();
        foreach ($data as $key => $value) {


            $buttons = '';

            if ($this->isAdmin) {
                $buttons .= '<button type="button" class="btn btn-default" onclick="editUserGroup(' . $value['intUserGroupID'] . ')" data-toggle="modal" data-target="#editUserGroupModal"><i class="fas fa-edit"></i></button>';
                $buttons .= ' <button type="button" class="btn btn-default" onclick="removeUserGroup(' . $value['intUserGroupID'] . ')" data-toggle="modal" data-target="#removeUserGroupModal"><i class="fa fa-trash"></i></button>';
            } else {
                if (in_array('editUserGroup', $this->permission)) {
                    $buttons .= '<button type="button" class="btn btn-default" onclick="editUserGroup(' . $value['intUserGroupID'] . ')" data-toggle="modal" data-target="#editUserGroupModal"><i class="fas fa-edit"></i></button>';
                }

                if (in_array('deleteUserGroup', $this->permission)) {
                    $buttons .= ' <button type="button" class="btn btn-default" onclick="removeUserGroup(' . $value['intUserGroupID'] . ')" data-toggle="modal" data-target="#removeUserGroupModal"><i class="fa fa-trash"></i></button>';
                }
            }

            $result['data'][$key] = array(
                $value['vcGroupName'],
                $buttons
            );
        }

        echo json_encode($result);
    }

    public function fetchUserGroupDataById($id)
    {
        if ($id) {
            $data = $this->model_groups->getUserGroupData($id);
            echo json_encode($data);
        }

        return false;
    }


    public function removeUserGroup($intUserGroupID = null)
    {
        if (!$this->isAdmin) {
            if (!in_array('deleteUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $intUserGroupID = $this->input->post('intUserGroupID');
        $response = array();
        if ($intUserGroupID) {

            $result = $this->model_groups->userGroupChkExists($intUserGroupID);

            if ($result <> '') {
                if ($result[0]['value'] == 1) {
                    $response['success'] = false;
                    $response['messages'] = "Record already received for the system, can't remove this supplier !";
                } else {
                    $delete = $this->model_groups->removeUserGroup($intUserGroupID);
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
    public function UserGroup()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->render_template('utilities/userGroup', 'User Group', $this->data);
    }

    public function createUserGroup()
    {
        if (!$this->isAdmin) {
            if (!in_array('createUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        $this->form_validation->set_rules('group_name', 'Group name', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            // true case
            $permission = serialize($this->input->post('permission'));

            $data = array(
                'vcGroupName' => $this->input->post('group_name'),
                'vcPermission' => $permission
            );


            $create = $this->model_groups->createUserGroup($data);
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

    public function updateUserGroup($id)
    {

        if (!$this->isAdmin) {
            if (!in_array('editUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_group_name', 'group name', 'trim|required');

            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {

                $permission = serialize($this->input->post('permission'));

                $data = array(
                    'vcGroupName' => $this->input->post('edit_group_name'),
                    'vcPermission' => $permission
                );
                $update = $this->model_groups->updateUserGroup($data, $id);
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

    public function MeasureUnit()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        // $this->load->view('partials/header');
        // $this->load->view('measureunit/manageMeasureUnit');
        // $this->load->view('partials/footer');

        $this->render_template('measureunit/manageMeasureUnit', 'Manage Measure Unit', $this->data);
    }

    public function fetchMeasureUnitDataById($id)
    {
        if ($id) {
            $data = $this->model_measureunit->getMeasureUnitData($id, true);
            echo json_encode($data);
        }

        return false;
    }

    public function updateMeasureUnit($id)
    {

        if (!$this->isAdmin) {
            if (!in_array('editMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }


        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_unit_name', 'unit name', 'trim|required');


            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $data = array(
                    'vcMeasureUnit' => $this->input->post('edit_unit_name'),
                );
                $update = $this->model_measureunit->update($data, $id);
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

    public function createMeasureUnit()
    {
        if (!$this->isAdmin) {
            if (!in_array('createMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }


        $response = array();

        $this->form_validation->set_rules('Unit_name', 'Measure Unit Name', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'vcMeasureUnit' => $this->input->post('Unit_name'),

            );
            $create = $this->model_measureunit->create($data);
            if ($create == true) {
                $response['success'] = true;
                $response['messages'] = 'Succesfully created';
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



    public function fetchMeasureUnitData()
    {

        if (!$this->isAdmin) {
            if (!in_array('viewMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());

        $data = $this->model_measureunit->getMeasureUnitData(null, true);
        foreach ($data as $key => $value) {

            // button
            $buttons = '';

            if ($this->isAdmin) {
                $buttons .= '<button type="button" class="btn btn-default" onclick="editMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#editMeasureUnitModal"><i class="fas fa-edit"></i></button>';
                $buttons .= ' <button type="button" class="btn btn-default" onclick="removeMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#removeMeasureUnithModal"><i class="fa fa-trash"></i></button>';
            } else {
                if (in_array('editMeasureUnit', $this->permission)) {
                    $buttons .= '<button type="button" class="btn btn-default" onclick="editMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#editMeasureUnitModal"><i class="fas fa-edit"></i></button>';
                }

                if (in_array('deleteMeasureUnit', $this->permission)) {
                    $buttons .= ' <button type="button" class="btn btn-default" onclick="removeMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#removeMeasureUnithModal"><i class="fa fa-trash"></i></button>';
                }
            }

            $result['data'][$key] = array(
                $value['vcMeasureUnit'],
                $buttons
            );
        }

        echo json_encode($result);
    }

    public function removeMeasureUnit($intMeasureUnitID = null)
    {

        if (!$this->isAdmin) {
            if (!in_array('deleteMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }


        $intMeasureUnitID = $this->input->post('intMeasureUnitID');
        $response = array();
        if ($intMeasureUnitID) {

            $result = $this->model_measureunit->chkexists($intMeasureUnitID);

            if ($result <> '') {
                if ($result[0]['value'] == 1) {
                    $response['success'] = false;
                    $response['messages'] = "Record already assigned for the system, can't remove this mmeasure unit !";
                } else {
                    $delete = $this->model_measureunit->remove($intMeasureUnitID);
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
    // Create Cutting Order
    //-----------------------------------

    public function CuttingOrder()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewCuttingOrder', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $item_data = $this->model_item->getOnlyFinishItemData();


        $this->data['item_data'] = $item_data;
        $this->render_template('Utilities/CuttingOrder', 'Manage Cutting Order',  $this->data);
    }

    public function SaveCuttingOrder()
    {
        if (!$this->isAdmin) {
            if (!in_array('createCuttingOrder', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        $result = $this->model_cuttingorder->SaveCuttingOrder();
        if ($result == true) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
        }
        echo json_encode($response);
    }

    public function GetCuttingOrderHeaderData($intCuttingOrderHeaderID = null)
    {

        if (!$this->isAdmin) {
            if (!in_array('viewCuttingOrder', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());

        $data = $this->model_cuttingorder->getCuttingOrderHeaderData($intCuttingOrderHeaderID = null);
        foreach ($data as $key => $value) {


            $buttons = '';

            if ($this->isAdmin) {
                $buttons .= ' <a href="' . base_url('Utilities/EditCuttingOrder/' . $value['intCuttingOrderHeaderID']) . '" class="btn btn-default"><i class="fa fa-edit"></i></a>';
                $buttons .= ' <button type="button" class="btn btn-default" id="btnRemoveCuttingOrder" onclick="RemoveCuttingOrder(' . $value['intCuttingOrderHeaderID'] . ')"><i class="fa fa-trash"></i></button>';
                $buttons .= ' <a href="' . base_url('Utilities/ViewCuttingOrder/' . $value['intCuttingOrderHeaderID']) . '" class="btn btn-default"><i class="fa fa-eye"></i></a>';
            } else {
                if (in_array('viewCuttingOrder', $this->permission)) {
                    $buttons .= ' <a href="' . base_url('Utilities/ViewCuttingOrder/' . $value['intCuttingOrderHeaderID']) . '" class="btn btn-default"><i class="fa fa-eye"></i></a>';
                }
                if (in_array('editCuttingOrder', $this->permission)) {
                    $buttons .= ' <a href="' . base_url('Utilities/EditCuttingOrder/' . $value['intCuttingOrderHeaderID']) . '" class="btn btn-default"><i class="fa fa-edit"></i></a>';
                }
                if (in_array('deleteCuttingOrder', $this->permission)) {
                    $buttons .= ' <button type="button" class="btn btn-default" id="btnRemoveCuttingOrder" onclick="RemoveCuttingOrder(' . $value['intCuttingOrderHeaderID'] . ')"><i class="fa fa-trash"></i></button>';
                }
            }

            $result['data'][$key] = array(
                $value['vcOrderName'],
                $value['dtCreatedDate'],
                $value['vcFullName'],
                $buttons
            );
        }

        echo json_encode($result);
    }

    public function ViewCuttingOrder($intCuttingOrderHeaderID = null)
    {
        if (!$this->isAdmin) {
            if (!in_array('viewCuttingOrder', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        // $canEdit = $this->model_cuttingorder->canModifiedCuttingOrder($intCuttingOrderHeaderID);

        if (!$intCuttingOrderHeaderID) {
            redirect(base_url() . 'Utilities/cuttingOrder', 'refresh');
        }

        $cuttingorder_header_data = $this->model_cuttingorder->getCuttingOrderHeaderData($intCuttingOrderHeaderID);
        if (!$cuttingorder_header_data) {
            redirect(base_url() . 'Utilities/cuttingOrder', 'refresh');
        }
        $cuttingorder_detail_data = $this->model_cuttingorder->getCuttingOrderDetailData($intCuttingOrderHeaderID);


        $this->data['cuttingorder_header_data'] = $cuttingorder_header_data;
        $this->data['cuttingorder_detail_data'] = $cuttingorder_detail_data;


        $this->render_template('Utilities/viewCuttingOrder', 'View Cutting Order', $this->data);
    }

    public function EditCuttingOrder($intCuttingOrderHeaderID = null)
    {
        if (!$this->isAdmin) {
            if (!in_array('editCuttingOrder', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        // $canEdit = $this->model_cuttingorder->canModifiedCuttingOrder($intCuttingOrderHeaderID);

        if (!$intCuttingOrderHeaderID) {
            redirect(base_url() . 'Utilities/cuttingOrder', 'refresh');
        }

        $cuttingorder_header_data = $this->model_cuttingorder->getCuttingOrderHeaderData($intCuttingOrderHeaderID);
        if (!$cuttingorder_header_data) {
            redirect(base_url() . 'Utilities/cuttingOrder', 'refresh');
        }
        $cuttingorder_detail_data = $this->model_cuttingorder->getCuttingOrderDetailData($intCuttingOrderHeaderID);

        // $item_data = $this->model_item->getOnlyFinishItemData();
        $item_data = $this->model_item->getOnlyFinishItemDataByNotConfig($intCuttingOrderHeaderID);
        $this->data['item_data'] = $item_data;
        $this->data['cuttingorder_header_data'] = $cuttingorder_header_data;
        $this->data['cuttingorder_detail_data'] = $cuttingorder_detail_data;


        $this->render_template('Utilities/editCuttingOrder', 'Edit Cutting Order', $this->data);
    }

    public function RemoveCuttingOrder()
    {
        if (!$this->isAdmin) {
            if (!in_array('deleteRequest', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $intCuttingOrderHeaderID = $this->input->post('intCuttingOrderHeaderID');
        $response = array();
        if ($intCuttingOrderHeaderID) {

            $canRemove = $this->model_cuttingorder->chkCanRemoveCuttingOrder($intCuttingOrderHeaderID);

            if ($canRemove <> '') {
                if ($canRemove[0]['value'] == 1) {
                    $response['success'] = false;
                    $response['messages'] = "You can't remove this Cutting Order, Already Configured !";
                } else {

                    $delete = $this->model_cuttingorder->removeCuttingOrder($intCuttingOrderHeaderID);

                    if ($delete == true) {
                        $response['success'] = true;
                        $response['messages'] = "Deleted !";
                    } else {
                        $response['success'] = false;
                        $response['messages'] = "Error in the database while removing the Request information !";
                    }
                }
            }
            echo json_encode($response);
        }
    }

    public function EditCuttingOrderDetails($intCuttingOrderHeaderID)
    {
        if (!$this->isAdmin) {
            if (!in_array('editCuttingOrder', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        $result = $this->model_cuttingorder->editCuttingOrder($intCuttingOrderHeaderID);
        if ($result == true) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
        }
        echo json_encode($response);
    }

    public function getCuttingOrderDetailsByCuttingOrderHeaderID()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewCuttingOrder', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $intCuttingOrderHeaderID = $this->input->post('intCuttingOrderHeaderID');
        $cuttingorder_detail_data = $this->model_cuttingorder->getCuttingOrderDetailData($intCuttingOrderHeaderID);
        echo json_encode($cuttingorder_detail_data);
    }


    //-----------------------------------
    // Create Cutting Order - Configuration
    //-----------------------------------

    public function ViewCuttingOrderConfiguration()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewCuttingOrderConfiguration', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->render_template('Utilities/viewCuttingOrderConfiguration', 'Manage Cutting Order Configuration');
    }

    public function CuttingOrderConfiguration()
    {
        if (!$this->isAdmin) {
            if (!in_array('createCuttingOrderConfiguration', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $item_data = $this->model_item->getOnlyRawItemData();
        $this->data['item_data'] = $item_data;

        $this->render_template('Utilities/manageCuttingOrderConfiguration', 'Cutting Order Configuration');
    }

    public function chkAlreadyDispatched($ItemID)
    {
        $response = array();

        $canEdit = $this->model_cuttingorder->chkAlreadyDispatched($ItemID);

        if ($canEdit <> '') {
            if ($canEdit[0]['value'] == 1) {
                $response['success'] = false;
                $response['messages'] = "You can't edit this Cutting Order, Already Dispatched !";
            } else {
                $response['success'] = true;
                // $response['messages'] = "Error in the database while removing the Request information !";
            }
        }
        echo json_encode($response);
    }

    public function SaveConfigCuttingOrderUsingFunction($ItemID, $CuttingOrderHeaderID)
    {
        $response = array();

        $result = $this->model_cuttingorder->SaveConfigCuttingOrderUsingFunction($ItemID, $CuttingOrderHeaderID);
        if ($result == true) {
            $response['success'] = true;
            $response['messages'] = 'Cutting Order Saved.';
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
        }
        echo json_encode($response);
    }

    public function DeleteConfigCuttingOrderUsingFunction($ItemID, $CuttingOrderHeaderID)
    {
        $response = array();

        $canDelete = $this->model_cuttingorder->chkCanRemoveCuttingOrderConfig($ItemID, $CuttingOrderHeaderID);

        if ($canDelete <> '') {
            if ($canDelete[0]['value'] == 1) {
                $response['success'] = false;
                $response['messages'] = "You can't delete this Cutting Order config, Already Dispatched !";
            } else {
                $result = $this->model_cuttingorder->DeleteConfigCuttingOrderUsingFunction($ItemID, $CuttingOrderHeaderID);
                if ($result == true) {
                    $response['success'] = true;
                    $response['messages'] = 'Cutting Order Deleted.';
                } else {
                    $response['success'] = false;
                    $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
                }
            }
        }
        echo json_encode($response);
    }

    public function SaveCuttingOrderConfiguration()
    {
        if (!$this->isAdmin) {
            if (!in_array('createCuttingOrderConfiguration', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        $result = $this->model_cuttingorder->SaveCuttingOrderConfiguration();
        if ($result == true) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
        }
        echo json_encode($response);
    }

    public function getCuttingOrdersByItemID($ItemID)
    {
        $data = $this->model_cuttingorder->getCuttingOrdersByItemID($ItemID);
        echo json_encode($data);
    }

    public function fetchCuttingConfigDataByItemID($ItemID)
    {
        $data = $this->model_cuttingorder->fetchCuttingConfigDataByItemID($ItemID);
        echo json_encode($data);
    }

    public function fetchCuttingOrderHeaderData()
    {
        $data = $this->model_cuttingorder->getCuttingOrderHeaderData($intCuttingOrderHeaderID = null);
        echo json_encode($data);
    }

    //-----------------------------------
    // Main Category
    //-----------------------------------
    public function MainCategory()
    {

        if (!$this->isAdmin) {
            if (!in_array('viewMainCategory', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        // $this->load->view('partials/header');
        // $this->load->view('measureunit/manageMeasureUnit');
        // $this->load->view('partials/footer');

        $this->render_template('utilities/manageMainCategory', 'Manage Main Category', $this->data);
    }

    public function createMainCategory()
    {
        if (!$this->isAdmin) {
            if (!in_array('createMainCategory', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        $this->form_validation->set_rules('MainCat_name', 'Main Category Name', 'trim|required|is_unique[maincategory.vcMainCategory]');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'vcMainCategory	' => $this->input->post('MainCat_name'),

            );
            $create = $this->model_utility->createMainCategory($data);
            if ($create == true) {
                $response['success'] = true;
                $response['messages'] = 'Succesfully created';
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

    public function updateMainCategory($id)
    {
        if (!$this->isAdmin) {
            if (!in_array('editMainCategory', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }


        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_mainCat_name', 'Main Category Name', 'trim|required|is_unique[maincategory.vcMainCategory]');


            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $data = array(
                    'vcMainCategory' => $this->input->post('edit_mainCat_name'),
                );
                $update = $this->model_utility->updateMainCategory($data, $id);
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

    public function fetchMainCategoryDataById($id)
    {
        if ($id) {
            $data = $this->model_utility->getMainCategoryData($id, true);
            echo json_encode($data);
        }

        return false;
    }

    public function fetchMainCategoryData()
    {

        if (!$this->isAdmin) {
            if (!in_array('viewMainCategory', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());

        $data = $this->model_utility->getMainCategoryData(null, true);
        foreach ($data as $key => $value) {

            // button
            $buttons = '';

            if ($this->isAdmin) {
                $buttons .= '<button type="button" class="btn btn-default" onclick="editMainCategory(' . $value['intMainCategoryID'] . ')" data-toggle="modal" data-target="#editMainCategoryModal"><i class="fas fa-edit"></i></button>';
                // $buttons .= ' <button type="button" class="btn btn-default" onclick="removeMainCategory(' . $value['intMainCategoryID'] . ')" data-toggle="modal" data-target="#removeMainCategoryModal"><i class="fa fa-trash"></i></button>';
            } else {
                if (in_array('editMainCategory', $this->permission)) {
                    $buttons .= '<button type="button" class="btn btn-default" onclick="editMainCategory(' . $value['intMainCategoryID'] . ')" data-toggle="modal" data-target="#editMainCategoryModal"><i class="fas fa-edit"></i></button>';
                }

                if (in_array('deleteMainCategory', $this->permission)) {
                    // $buttons .= ' <button type="button" class="btn btn-default" onclick="removeMainCategory(' . $value['intMainCategoryID'] . ')" data-toggle="modal" data-target="#removeMainCategoryModal"><i class="fa fa-trash"></i></button>';
                }
            }

            $result['data'][$key] = array(
                $value['vcMainCategory'],
                $buttons
            );
        }

        echo json_encode($result);
    }

    //-----------------------------------
    // Sub Category
    //-----------------------------------
    public function SubCategory()
    {

        if (!$this->isAdmin) {
            if (!in_array('viewSubCategory', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        // $this->load->view('partials/header');
        // $this->load->view('measureunit/manageMeasureUnit');
        // $this->load->view('partials/footer');


        $main_cat_data = $this->model_utility->getMainCategoryData(null, false);
        $this->data['main_cat_data'] = $main_cat_data;


        $this->render_template('utilities/manageSubCategory', 'Manage Sub Category', $this->data);
    }

    public function fetchSubCategoryData()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewSubCategory', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());

        $data = $this->model_utility->getSubCategoryData(null, true);
        foreach ($data as $key => $value) {

            // button
            $buttons = '';

            if ($this->isAdmin) {
                $buttons .= '<button type="button" class="btn btn-default" onclick="editSubCategory(' . $value['intSubCategoryID'] . ')" data-toggle="modal" data-target="#editSubCategoryModal"><i class="fas fa-edit"></i></button>';
                // $buttons .= ' <button type="button" class="btn btn-default" onclick="removeMainCategory(' . $value['intMainCategoryID'] . ')" data-toggle="modal" data-target="#removeMainCategoryModal"><i class="fa fa-trash"></i></button>';
            } else {
                if (in_array('editSubCategory', $this->permission)) {
                    $buttons .= '<button type="button" class="btn btn-default" onclick="editSubCategory(' . $value['intSubCategoryID'] . ')" data-toggle="modal" data-target="#editSubCategoryModal"><i class="fas fa-edit"></i></button>';
                }

                if (in_array('deleteSubCategory', $this->permission)) {
                    // $buttons .= ' <button type="button" class="btn btn-default" onclick="removeMainCategory(' . $value['intMainCategoryID'] . ')" data-toggle="modal" data-target="#removeMainCategoryModal"><i class="fa fa-trash"></i></button>';
                }
            }

            $result['data'][$key] = array(
                $value['vcMainCategory'],
                $value['vcSubCategory'],
                $buttons
            );
        }

        echo json_encode($result);
    }

    public function fetchSubCategoryDataById($id)
    {
        if ($id) {
            $data = $this->model_utility->getSubCategoryData($id, true);
            echo json_encode($data);
        }

        return false;
    }

    public function createSubCategory()
    {
        if (!$this->isAdmin) {
            if (!in_array('createSubCategory', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        $this->form_validation->set_rules('subCat_name', 'Sub category Name', 'trim|required|is_unique[subcategory.vcSubcategory]');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'intMainCategoryID' => $this->input->post('main_cat'),
                'vcSubcategory' => $this->input->post('subCat_name'),

            );
            $create = $this->model_utility->createSubCategory($data);
            if ($create == true) {
                $response['success'] = true;
                $response['messages'] = 'Succesfully created';
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

    public function updateSubCategory($id)
    {
        if (!$this->isAdmin) {
            if (!in_array('editSubCategory', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_SubCat_name', 'Sub Category Name', 'trim|required');

            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $data = array(
                    'intMainCategoryID' => $this->input->post('edit_main_cat'),
                    'vcSubcategory' => $this->input->post('edit_SubCat_name'),
                );
                $update = $this->model_utility->updateSubCategory($data, $id);
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
    //-----------------------------------
    // Manage Sales Rep
    //-----------------------------------

    public function manageSalesRep()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewSalesRep', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->render_template('Utilities/manageSalesRep', 'Manage Sales Rep',  $this->data);
    }

    public function saveSalesRep()
    {
        if (!$this->isAdmin) {
            if (!in_array('createSalesRep', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        date_default_timezone_set('Asia/Colombo');
        $nowDateTime = date('Y-m-d h:i:s');

        $data = array(
            'vcSalesRepName' => $this->input->post('salesRep_name'),
            'vcContactNo' => $this->input->post('contact_no'),
            'vcAddress' => $this->input->post('address'),
            'dtCreatedDate' => $nowDateTime,
        );
        $create = $this->model_utility->saveSalesRep($data);
        if ($create == true) {
            $response['success'] = true;
            $response['messages'] = 'Succesfully created';
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the brand information';
        }
        echo json_encode($response);
    }

    public function updateSalesRep($intSalesRepID)
    {
        if (!$this->isAdmin) {
            if (!in_array('updateSalesRep', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        $data = array(
            'vcSalesRepName' => $this->input->post('edit_salesRep_name'),
            'vcContactNo' => $this->input->post('edit_contact_no'),
            'vcAddress' => $this->input->post('edit_address'),
        );
        $create = $this->model_utility->updateSalesRep($data,$intSalesRepID);
        if ($create == true) {
            $response['success'] = true;
            $response['messages'] = 'Update Succesfully !';
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the brand information';
        }
        echo json_encode($response);
    }

    public function removeSalesRep($intSalesRepID = null)
    {
        if (!$this->isAdmin) {
            if (!in_array('deleteSalesRep', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $intSalesRepID = $this->input->post('intSalesRepID');
        $response = array();
        if ($intSalesRepID) {

            $delete = $this->model_utility->removeSalesRep($intSalesRepID);

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

    public function fetchSalesRepData($intSalesRepID = null)
    {
        $result = array('data' => array());

        $data = $this->model_utility->getfetchSalesRepData($intSalesRepID);
        foreach ($data as $key => $value) {

            $buttons = '';

            if ($this->isAdmin) {
                $buttons .= '<button type="button" class="btn btn-default" onclick="editSalesRep(' . $value['intSalesRepID'] . ')" data-toggle="modal" data-target="#editSalesRepModal"><i class="fas fa-edit"></i></button>';
                $buttons .= ' <button type="button" class="btn btn-default" onclick="removeSalesRep(' . $value['intSalesRepID'] . ')" data-toggle="modal" data-target="#removeSalesRepModal"><i class="fa fa-trash"></i></button>';
            } else {
                if (in_array('editBranch', $this->permission)) {
                    $buttons .= '<button type="button" class="btn btn-default" onclick="editSalesRep(' . $value['intSalesRepID'] . ')" data-toggle="modal" data-target="#editSalesRepModal"><i class="fas fa-edit"></i></button>';
                }
                if (in_array('deleteBranch', $this->permission)) {
                    $buttons .= ' <button type="button" class="btn btn-default" onclick="removeSalesRep(' . $value['intSalesRepID'] . ')" data-toggle="modal" data-target="#removeSalesRepModal"><i class="fa fa-trash"></i></button>';
                }
            }

            $result['data'][$key] = array(
                $value['vcSalesRepName'],
                $value['vcContactNo'],
                $value['vcAddress'],
                $buttons
            );
        }

        echo json_encode($result);
    }

    public function fetchSalesRepDataById($intSalesRepID)
    {
        if ($intSalesRepID) {
            $data = $this->model_utility->getfetchSalesRepData($intSalesRepID);
            echo json_encode($data);
        }

        return false;
    }


    //-----------------------------------
    // Database Backup
    //-----------------------------------

    public function Backup()
    {
        $this->render_template('Utilities/backup', 'Backup');
    }
    public function DownLoadBackup()
    {
        $this->load->dbutil();

        $prefs = array(
            'format'      => 'gzip',
            'filename'    => 'my_db_backup.sql',
            'foreign_key_checks' => false
        );

        $backup = $this->dbutil->backup($prefs);

        $db_name = 'backup-on-' . date("Y-m-d-H-i-s") . '.zip';
        $save = 'pathtobkfolder/' . $db_name;

        $this->load->helper('file');
        write_file($save, $backup);


        $this->load->helper('download');
        force_download($db_name, $backup);
    }
}
