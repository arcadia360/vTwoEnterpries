<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Receipt extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->not_logged_in();
    $this->load->model('model_receipt');
    $this->load->model('model_customer');
    $this->load->model('model_utility');

    // $this->load->model('model_issue');

    // $user_group_data = $this->model_groups->getUserGroupData();
    // $this->data['user_groups_data'] = $user_group_data;
  }

  //-----------------------------------
  // Create Receipt
  //-----------------------------------

  public function CreateReceipt()
  {
    if (!$this->isAdmin) {
      if (!in_array('createReceipt', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $customer_data = $this->model_customer->getCustomerData();
    $paymode_data = $this->model_utility->getPayModes();
    $bank_data = $this->model_utility->getBanks();

    $this->data['customer_data'] = $customer_data;
    $this->data['paymode_data'] = $paymode_data;
    $this->data['bank_data'] = $bank_data;

    $this->render_template('Receipt/CreateReceipt', 'Create Receipt',  $this->data);
  }


  public function getCustomerToBeSettleIssueNos()
  {
    $CustomerID = $this->input->post('intCustomerID');
    $data = $this->model_receipt->getCustomerToBeSettleIssueNos($CustomerID);
    echo json_encode($data);
  }

  public function getIssueNotePaymentDetails()
  {
    $IssueHeaderID = $this->input->post('intIssueHeaderID');
    $data = $this->model_receipt->getIssueNotePaymentDetails($IssueHeaderID);
    echo json_encode($data);
  }

  public function SaveReceipt()
  {
    if (!$this->isAdmin) {
      if (!in_array('createReceipt', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $response = $this->model_receipt->saveReceipt();
    if ($response['success'] == true) {
      // $response['issueNote'] = $this->PrintIssueDiv($response['intIssueHeaderID']);
    }
    // $response['issueNote'] = "ABC";


    echo json_encode($response);
  }


  //-----------------------------------
  // View Receipt
  //-----------------------------------

  public function ViewReceipt()
  {
    if (!$this->isAdmin) {
      if (!in_array('viewReceipt', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $customer_data = $this->model_customer->getCustomerData();
		$payment_data = $this->model_utility->getPayModes();

		$this->data['payment_data'] = $payment_data;
		$this->data['customer_data'] = $customer_data;

		$this->render_template('Receipt/ViewReceipt', 'View Receipt Settlement', $this->data);

  }

  public function FilterCustomerReceiptHeaderData($PayModeID, $CustomerID, $FromDate, $ToDate)
	{
		if (!$this->isAdmin) {
			if (!in_array('viewReceipt', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$result = array('data' => array());

		$settlement_data = $this->model_receipt->GetCustomerReceiptHeaderData(null, $PayModeID, $CustomerID, $FromDate, $ToDate);


		foreach ($settlement_data as $key => $value) {

			$buttons = '';

			if (in_array('viewReceipt', $this->permission) || $this->isAdmin) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="viewSettlementDetails(' . $value['intReceiptHeaderID'] . ')" data-toggle="modal" data-target="#viewModal"><i class="fas fa-eye"></i></button>';
			}


			$result['data'][$key] = array(
				$value['vcReceiptNo'],
				$value['vcCustomerName'],
				$value['vcPayMode'],
				number_format((float)$value['decAmount'], 2, '.', ''),
				$value['dtPaidDate'],
				$value['vcFullName'],
				$value['dtCreatedDate'],
				$value['vcBankName'],
				$value['vcChequeNo'],
				$value['dtPDDate'],
				$value['vcRemark'],
				$buttons
			);
		}

		echo json_encode($result);
	}

  public function ViewSettlementDetailsToModal()
  {
    $ReceiptHeaderID = $this->input->post('intReceiptHeaderID');
		$data = $this->model_receipt->getSettlementDetailsToModal($ReceiptHeaderID);
		echo json_encode($data);
  }
  
}
