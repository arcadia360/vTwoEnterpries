<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('model_auth');
    }

    /* 
		Check if the login form is submitted, and validates the user credential
		If not submitted it redirects to the login page
	*/
    public function login()
    {

        $this->logged_in();

        $this->form_validation->set_rules('username', 'user name', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'required');


        if ($this->form_validation->run() == TRUE) {
            // true case
            $username_exists = $this->model_auth->check_username($this->input->post('username'));

            if ($username_exists == TRUE) {
                $login = $this->model_auth->login($this->input->post('username'), $this->input->post('password'));

                if ($login) {
                    if ($login['IsActive'] == 1) {
                        $logged_in_sess = array(
                            'user_id' => $login['intUserID'],
                            'user_name' => $login['vcUserName'],
                            'full_name' => $login['vcFullName'],
                            'email' => $login['vcEmail'],
                            'group_id' => $login['intUserGroupID'],
                            'group_name' => $login['vcGroupName'],
                            'branch_id' => $login['intBranchID'],
                            'Is_main_branch'  => $login['IsMainBranch'],
                            'logged_in' => TRUE
                        );

                        $this->session->set_userdata($logged_in_sess);
                        redirect('dashboard', 'refresh');

                    }else{
                        $this->data['errors'] = 'Your account has been deactivated. Please contact administrator !';
                        $this->load->view('login', $this->data);
                    }              

                } else {
                    $this->data['errors'] = 'Incorrect user name / password !';
                    $this->load->view('login', $this->data);
                }
            } else {
                $this->data['errors'] = 'User name does not exists !';
                $this->load->view('login', $this->data);
            }
        } else {
            // false case
            $this->load->view('login');
        }
    }

    /*
		clears the session and redirects to login page
	*/
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login', 'refresh');
    }
}
