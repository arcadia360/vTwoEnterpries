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
        $this->load->model('model_customer');

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
                number_format((float)$value['IssuedAmount'], 2, '.', ''),
                number_format((float)$value['ReturnedAmount'], 2, '.', ''),
                number_format((float)$value['GRNAmount'], 2, '.', ''),
                number_format((float)$value['ProfitAmount'], 2, '.', ''),
			);
		}

		echo json_encode($result);
    }


    public function CostAndProfitReport()
    {
        if (!$this->isAdmin) {
            if (!in_array('CostAndProfitReport', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $this->render_template('report/CostAndProfitReport', 'Cost & Profit Report', NULL);
    }

    public function FilterGRNWiseCostAndProfitData($FromDate,$ToDate)
    {
        $result = array('data' => array());

		$data = $this->model_report->getGRNWiseCostAndProfitData($FromDate,$ToDate);
		foreach ($data as $key => $value) {

			$result['data'][$key] = array(
                number_format((float)$value['decGrandTotal'], 2, ".", ","),
                number_format((float)$value['decIssueTotal'], 2, ".", ","),
                number_format((float)$value['decProfitTotal'], 2, ".", ","),
			);
		}

		echo json_encode($result);
    }

    public function IssueSummaryReport()
    {
        if (!$this->isAdmin) {
            if (!in_array('IssueSummaryReport', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $customer_data = $this->model_customer->getCustomerData();
        $this->data['customer_data'] = $customer_data;
        $this->render_template('report/issueSummaryReport', 'Issue Summary Report', $this->data);
    }

    public function FilterIssueSummaryReport($FromDate,$ToDate,$CustomerID)
    {
        $result = array('data' => array());

		$data = $this->model_report->getIssueSummaryData($FromDate,$ToDate,$CustomerID);
		foreach ($data as $key => $value) {

			$buttons = '';

			$result['data'][$key] = array(
				$value['vcItemName'],
				$value['GRNValue'],
				$value['IssuedValue'],
				$value['IssuedQty'],
				$value['IssuedDiscountPercentage'],
                number_format((float)$value['IssuedAmount'], 2, '.', ''),
                number_format((float)$value['ReturnedAmount'], 2, '.', ''),
                number_format((float)$value['GRNAmount'], 2, '.', ''),
                number_format((float)$value['ProfitAmount'], 2, '.', ''),
			);
		}

		echo json_encode($result);
    }

    public function CustomerWiseSalesReport(){
        if (!$this->isAdmin) {
            if (!in_array('customerWiseSalesReport', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $customer_Data = $this->model_customer->getCustomerData();
        $this->data['customer_Data'] = $customer_Data;

        $this->render_template('report/customerWiseSalesReport', 'Customer Wise Sales Report', $this->data);
    }

    public function getCustomerWiseSalesReport($CustomerID,$FromDate,$ToDate)
    {
        if (!$this->isAdmin) {
			if (!in_array('customerWiseSalesReport', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$result = array('data' => array());

		$data = $this->model_report->getCustomerWiseSalesReport($CustomerID,$FromDate,$ToDate);
		foreach ($data as $key => $value) {

			$buttons = '';

			$result['data'][$key] = array(
                $value['vcIssueNo'],
				$value['vcItemName'],
				$value['GRNValue'],
				$value['IssuedValue'],
				$value['IssuedQty'],
				$value['IssuedDiscountPercentage'],
                number_format((float)$value['IssuedAmount'], 2, '.', ''),
                number_format((float)$value['ReturnedAmount'], 2, '.', ''),
                number_format((float)$value['GRNAmount'], 2, '.', ''),
                number_format((float)$value['ProfitAmount'], 2, '.', ''),
			);
		}

		echo json_encode($result);
    }
}