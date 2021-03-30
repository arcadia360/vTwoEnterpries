<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MeasureUnit extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->load->model('model_measureunit');
        $this->load->model('model_groups');

        // $user_group_data = $this->model_groups->getUserGroupData();
        // $this->data['user_groups_data'] = $user_group_data;
    }

    public function MeasureUnit()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->load->view('partials/header');
        $this->load->view('measureunit/manageMeasureUnit', $this->data);
        $this->load->view('partials/footer');
    }

    public function create()
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
                'vcMeasureUnit	' => $this->input->post('Unit_name'),

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
}
