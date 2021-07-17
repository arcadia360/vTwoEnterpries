<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->load->model('model_report'); 
        $this->load->model('model_issue');

        // $user_group_data = $this->model_groups->getUserGroupData();
        // $this->data['user_groups_data'] = $user_group_data;
    }

    public function IssueWiseCostAndProfitReport(){
        if (!$this->isAdmin) {
            if (!in_array('issueWiseCostAndProfitReport', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $issue_No = $this->model_issue->getAllIssueNo();
        $this->data['issue_No'] = $issue_No;

        $this->render_template('report/issueWiseCostAndProfitReport', 'Cost & Profit Report', $this->data);
    }

    public function getIssueWiseCostAndProfitData($IssueHeaderID)
    {
        if (!$this->isAdmin) {
			if (!in_array('viewIssueWiseCostAndProfit', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$result = array('data' => array());

		$data = $this->model_report->getIssueWiseCostAndProfitData($IssueHeaderID);
		foreach ($data as $key => $value) {

			$buttons = '';

			$result['data'][$key] = array(
				$value['vcItemName'],
				$value['GRNValue'],
				$value['IssuedValue'],
				$value['IssuedQty'],
				$value['IssuedDiscountPercentage'],
				$value['IssuedAmount'],
                $value['GRNAmount'],
                $value['ProfitAmount'],
			);
		}

		echo json_encode($result);
    }
}