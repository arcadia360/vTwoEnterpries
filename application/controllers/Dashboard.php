<?php
class Dashboard extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();
		$this->load->model('model_dashboard');
	}

	public function index()
	{

		$approval_pending_grn_count = $this->model_dashboard->getApprovalPendingGRNCount();
		// $approval_pending_requesition_count = $this->model_dashboard->getApprovalPendingRequesitionCount();
		// $pending_dispatch_count = $this->model_dashboard->getPendingDispatchCount();
		// $reorder_item_count = $this->model_dashboard->getReOrderItemCount();

		$this->data['page_title'] = 'vTwo Enterpries | Business Management System';
		$this->data['approval_pending_grn_count'] = $approval_pending_grn_count;

		$this->load->view('partials/header', $this->data);
		$this->load->view('partials/navbar');
		$this->load->view('partials/sidebar');
		$this->load->view('Dashboard');
		$this->load->view('dashboard/widgetSet_01', $this->data);
		$this->load->view('partials/footer');
	}

	public function showNotification()
	{
		if (!$this->isAdmin) {
			if (!in_array('approveGRN', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}
		$result = array();

		$notification = '';

		$branch_id = 0;
		if ($_SESSION['Is_main_branch'] == 0) {
			$branch_id = $_SESSION['branch_id'];
		}
		$getSIHExceedItemData = $this->model_dashboard->getSIHExceedItemData($branch_id);

		$ROLCount = 0;
		foreach ($getSIHExceedItemData as $key => $value) {

			$notification .= '<a href="#" class="dropdown-item">
		                    	<p class="notify-title"><i class="fas fa-file mr-2"></i>&nbsp;Re-Order Level Exceeded !</p>
		                    	<p class="notify-message">You have re-order level exceed item ' . $value['vcItemName'] .  '<span class="float-right text-muted text-sm">Your SIH is ' . $value['decStockInHand'] .  '</span></p>
							</a>
							<div class="dropdown-divider"></div>';
			$ROLCount++;
		}



		if ($_SESSION['Is_main_branch'] == 1) { // Main Branch
			$getPendingData = $this->model_dashboard->getMainBranchApprovalPendingData();
		} else { // Other Branch
			$getPendingData = $this->model_dashboard->getMainBranchApprovalPendingData();
		}



		$GRNCount = 0;
		foreach ($getPendingData as $key => $value) {
			$time = '';
			if ($value['Minutes'] <= 60) {
				$time = $value['Minutes'] . ' mins';
			} else if ($value['Hours'] <= 24) {
				$time = $value['Hours'] . ' hours';
			} else {
				$time = $value['Days'] . ' days';
			}

			$notification .= '<a href="' . base_url() . "GRN/ApproveOrRejectGRN/" . $value['intGRNHeaderID'] . '" class="dropdown-item">
		                    	<p class="notify-title"><i class="fas fa-file mr-2"></i>&nbsp;GRN Approval Pending</p>
		                    	<p class="notify-message">You have pending goods received a note to approve "' . $value['vcGRNNo'] . '"<span class="float-right text-muted text-sm">' . $time . '</span></p>
							</a>
							<div class="dropdown-divider"></div>';
			$GRNCount++;
		}

		$totalNotificationsCount = $GRNCount + $ROLCount; // Sum another notifications

		$notificationBadge = '';

		if ($totalNotificationsCount > 0) {
			$notificationBadge = '<span class="badge badge-danger navbar-badge" style="font-weight: 500; border-radius: 50% !important; font-size:0.4em;">' . $totalNotificationsCount . '</span>';
		}

		$btnNotification = '<a class="nav-link" data-toggle="dropdown" href="#" style="font-size: 2.2em; margin-top:0; padding-top:0;">
		                		<i class="far fa-bell"></i>
		                		' . $notificationBadge . '
							</a>';

		$notoficationAreaBody = '<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-height: 80vh; overflow:scroll">
		                			<span class="dropdown-item dropdown-header">' . $totalNotificationsCount . ' Notifications</span>
		                			<div class="dropdown-divider"></div>
									' . $notification .
			'</div>';



		$htmlElement = $btnNotification . $notoficationAreaBody;

		$result['htmlElement'] = $htmlElement;

		echo json_encode($result);
	}
}
