<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        // $this->load->model('model_branch'); 
        // $this->load->model('model_groups');

        // $user_group_data = $this->model_groups->getUserGroupData();
        // $this->data['user_groups_data'] = $user_group_data;
    }

    public function IssueWiseCostAndProfitReport(){
        if (!$this->isAdmin) {
            if (!in_array('issueWiseCostAndProfitReport', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->render_template('report/issueWiseCostAndProfitReport', 'Cost & Profit Report', $this->data);
    }
}