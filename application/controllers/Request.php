<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->load->model('model_branch');
        $this->load->model('model_groups');
        $this->load->model('model_item');
        $this->load->model('model_measureunit');
        $this->load->model('model_request');

        // $user_group_data = $this->model_groups->getUserGroupData();
        // $this->data['user_groups_data'] = $user_group_data;
    }

    //-----------------------------------
    // Create Request
    //-----------------------------------
    public function RequestItem($BranchID = null)
    {
        if ($_SESSION['Is_main_branch'] == 1) {
            redirect('dashboard', 'refresh');
        }
       
            if (!$this->isAdmin) {
                if (!in_array('viewRequestItem', $this->permission)) {
                    redirect('dashboard', 'refresh');
                }
            }
            $item_data = $this->model_item->getOnlyFinishItemData();
            $this->data['item_data'] = $item_data;

            $this->render_template('request/requestItem', 'Request Item', $this->data);
     


    }

    public function getRequestFinishedByItemID($ItemID)
    {
        $data = $this->model_request->getRequestFinishedByItemID($ItemID);
        echo json_encode($data);
    }

    public function SaveRequestItem()
    {
        if ($_SESSION['Is_main_branch'] == 1) {
            redirect('dashboard', 'refresh');
        }

        if (!$this->isAdmin) {
            if (!in_array('createRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        $response = $this->model_request->SaveRequestItem();
        if (!empty($response)) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
        }
        echo json_encode($response);
    }

    //-----------------------------------
    // View Request
    //-----------------------------------
    public function ViewRequest()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->render_template('request/viewRequestItem', 'View Request Item');
    }

    public function FilterRequestHeaderData($StatusType, $FromDate, $ToDate)
    {
        if (!$this->isAdmin) {
            if (!in_array('viewRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());

        $data = $this->model_request->getRequestHeaderData(null, $StatusType, $FromDate, $ToDate);
        foreach ($data as $key => $value) {

            $buttons = '';
            $Pending = '';
            $Rejected  = '';


            // if ($this->isAdmin) {
            //     $buttons .= '<a class="button btn btn-default" href="' . base_url("Request/EditRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-edit"></i></a>';
            //     $buttons .= '<button type="button" class="btn btn-default" id="btnRemoveRequestItem" onclick="RemoveRequest(' . $value['intRequestHeaderID'] . ')"><i class="fa fa-trash"></i></button>';
            //     $buttons .= '<a class="button btn btn-default" href="' . base_url("Request/ApprovalRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="far fa-thumbs-up"></i></button>';
            //     $buttons .= '<a class="button btn btn-default" href="' . base_url("Request/AcceptRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-ambulance"></i></button>';
            //     $buttons .= '<a class="button btn btn-default" href="' . base_url("Request/IssuedRequestItemCancel/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-exchange-alt"></i></button>';
            // } else {
                if ($this->session->userdata('Is_main_branch') == false) {
                    if (in_array('editRequestItem', $this->permission)|| ($this->isAdmin)) {
                        $buttons .= '<a class="button btn btn-default" href="' . base_url("Request/EditRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-edit"></i></a>';
                    }
                    if (in_array('deleteRequestItem', $this->permission)|| ($this->isAdmin)) {
                        $buttons .= ' <button type="button" class="btn btn-default" id="btnRemoveRequestItem" onclick="RemoveRequest(' . $value['intRequestHeaderID'] . ')"><i class="fa fa-trash"></i></button>';
                    }
                    if (in_array('acceptRequestItem', $this->permission)|| ($this->isAdmin)) {
                        $buttons .= '<a class="button btn btn-default" href="' . base_url("Request/AcceptRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-ambulance"></i></button>';
                    }
                } else {
                    if (in_array('approveRequestItem', $this->permission) || ($this->isAdmin)) {
                        $buttons .= '<a class="button btn btn-default" href="' . base_url("request/ApprovalRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="far fa-thumbs-up"></i></button>';
                    }
                    if (in_array('issuedRequestItemCancel', $this->permission) || ($this->isAdmin)) {
                        $buttons .= '<a class="button btn btn-default" href="' . base_url("Request/IssuedRequestItemCancel/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-exchange-alt"></i></button>';
                    }
                // }
            }

            $Pending .= ($value['Pending'] == 0) ? "N/A"  : $value['Pending'];
            $Rejected .= ($value['Rejected'] == 0) ? "N/A"  : $value['Rejected'];

            $result['data'][$key] = array(
                $value['vcRequestNo'],
                $value['vcBranchName'],
                $value['dtCreatedDate'],
                $value['Created_User'],
                $value['Total_Items'],
                $Pending,
                $Rejected,
                $buttons
            );
        }

        echo json_encode($result);
    }


    //-----------------------------------
    // Edit Request
    //-----------------------------------
    public function EditRequestItem($intRequestHeaderID = null)
    {

        if ($_SESSION['Is_main_branch'] == 1) {
            redirect('dashboard', 'refresh');
        }

        if (!$this->isAdmin) {
            if (!in_array('editRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $canEdit = $this->model_request->canModifiedRequest($intRequestHeaderID);

        if (!$intRequestHeaderID || !$canEdit) {
            redirect(base_url() . 'request/ViewRequest', 'refresh');
        }

        $request_header_data = $this->model_request->getRequestHeaderData($intRequestHeaderID);
        if (!$request_header_data) {
            redirect(base_url() . 'request/ViewRequest', 'refresh');
        }
        $request_detail_data = $this->model_request->getRequestDetailData($intRequestHeaderID);
        $item_data = $this->model_item->getOnlyFinishItemData();

        $this->data['item_data'] = $item_data;

        $this->data['request_header_data'] = $request_header_data;
        $this->data['request_detail_data'] = $request_detail_data;


        $this->render_template('request/editRequestItem', 'Edit Request Item', $this->data);
    }

    public function EditRequestDetails($intRequestHeaderID)
    {
        if ($_SESSION['Is_main_branch'] == 1) {
            redirect('dashboard', 'refresh');
        }


        if (!$this->isAdmin) {
            if (!in_array('editRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        $result = $this->model_request->EditRequest($intRequestHeaderID);
        if ($result == true) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
        }
        echo json_encode($response);
    }


    //-----------------------------------
    // Remove Request
    //-----------------------------------

    public function RemoveRequest()
    {
        if ($_SESSION['Is_main_branch'] == 1) {
            redirect('dashboard', 'refresh');
        }


        if (!$this->isAdmin) {
            if (!in_array('deleteRequest', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $intRequestHeaderID = $this->input->post('intRequestHeaderID');
        $response = array();
        if ($intRequestHeaderID) {

            $canRemove = $this->model_request->canModifiedRequest($intRequestHeaderID);

            if ($canRemove) {

                $delete = $this->model_request->removeRequest($intRequestHeaderID);

                if ($delete == true) {
                    $response['success'] = true;
                    $response['messages'] = "Deleted !";
                } else {
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while removing the Request information !";
                }
            } else {
                $response['success'] = false;
                $response['messages'] = "You can't remove this Request, Please check and try again !";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Please refersh the page again !!";
        }
        echo json_encode($response);
    }


    //-----------------------------------
    // Approval Request 
    //-----------------------------------


    public function ApprovalRequestItem($intRequestHeaderID = null)
    {
        if ($_SESSION['Is_main_branch'] != 1) {
            redirect('dashboard', 'refresh');
        }

        if (!$this->isAdmin) {
            if (!in_array('approveRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $request_header_data = $this->model_request->getRequestHeaderData($intRequestHeaderID);
        if (!$request_header_data) {
            redirect(base_url() . 'request/ViewRequest', 'refresh');
        }
        $request_detail_data = $this->model_request->getRequestDetailData($intRequestHeaderID);

        $this->data['request_header_data'] = $request_header_data;
        $this->data['request_detail_data'] = $request_detail_data;

        $this->render_template('request/ApprovalRequestItem', 'Approval Request Item', $this->data);
    }

    public function RejectRequestByDetailID($RequestDetailID, $ItemID, $rv)
    {
        if ($_SESSION['Is_main_branch'] != 1) {
            redirect('dashboard', 'refresh');
        }


        if (!$this->isAdmin) {
            if (!in_array('approveRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        // $uid =$this->input->post('RequestDetailID');
        // $ItemID =$this->input->post('ItemID');
        // $rv = $this->input->post('rv');
        $response = array();

        $previousRV = $this->model_item->chkRv($ItemID);

        if ($previousRV['rv']  != $rv) {
            $response['success'] = false;
            $response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
        } else {
            $result = $this->model_request->RejectRequestByDetailID($RequestDetailID);

            if ($result == true) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
            }
        }

        echo json_encode($response);
    }

    public function ApprovalRequestByDetailID($RequestDetailID, $ItemID, $rv)
    {
        if ($_SESSION['Is_main_branch'] != 1) {
            redirect('dashboard', 'refresh');
        }

        if (!$this->isAdmin) {
            if (!in_array('approveRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        // $uid =$this->input->post('RequestDetailID');
        // $ItemID =$this->input->post('ItemID');
        // $rv = $this->input->post('rv');
        $response = array();

        $previousRV = $this->model_item->chkRv($ItemID);
        $chkCanApproval = $this->model_request->GetRequestDetailByID($RequestDetailID);


        if ($previousRV['rv']  != $rv) {
            $response['success'] = false;
            $response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
        } else {
            if ($chkCanApproval['decQty'] > $chkCanApproval['decStockInHand']) {
                $response['success'] = false;
                $response['messages'] = 'Cannot Approval this Item Please Check Stock Qty !';
            } else {
                $result = $this->model_request->ApprovalRequestByDetailID($RequestDetailID);

                if ($result == true) {
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
                }
            }
        }
        echo json_encode($response);
    }

    public function ApprovalOrRejectRequestItems($isApproved)
    {
        if ($_SESSION['Is_main_branch'] != 1) {
            redirect('dashboard', 'refresh');
        }


        if (!$this->isAdmin) {
            if (!in_array('approveRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $isApproved = $this->input->post('isApproved');
        $CanApprovalOrRejectAll = true;
        $CheckQty = true;
        if ($isApproved == 0) //Reject All
        {
            $item_count = count($this->input->post('itemID'));

            for ($i = 0; $i < $item_count; $i++) {
                $previousRV = $this->model_item->chkRv($this->input->post('itemID')[$i]);

                if ($previousRV['rv']  != $this->input->post('rv')[$i]) {
                    $CanApprovalOrRejectAll = false;
                }
            }

            if ($CanApprovalOrRejectAll == false) {
                $response['success'] = false;
                $response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
            } else {
                $result = $this->model_request->ApprovalOrRejectRequestAllItems($isApproved);
                $response['success'] = true;
            }
        }

        if ($isApproved == 1) //Approval All
        {
            $item_count = count($this->input->post('itemID'));

            for ($i = 0; $i < $item_count; $i++) {
                $previousRV = $this->model_item->chkRv($this->input->post('itemID')[$i]);

                if ($previousRV['rv']  != $this->input->post('rv')[$i]) {
                    $CanApprovalOrRejectAll = false;
                } else {
                    $chkCanApproval = $this->model_request->GetRequestDetailByIDApprovalAndRejectNull($this->input->post('intRequestDetailID')[$i]);
                    if ($chkCanApproval > 0) {
                        if ($chkCanApproval['decQty'] > $chkCanApproval['decStockInHand']) {
                            $CheckQty = false;
                        }
                    }
                }
            }

            if ($CanApprovalOrRejectAll == false || $CheckQty == false) {
                $response['success'] = false;
                $response['messages'] = 'Another user tries to edit this Item details Or Please Check Stock Qty..! please refresh the page and try again !';
            } else {
                $result = $this->model_request->ApprovalOrRejectRequestAllItems($isApproved);
                $response['success'] = true;
            }
        }

        echo json_encode($response);
    }


    //-----------------------------------
    // Accept Request Items
    //-----------------------------------

    public function AcceptRequestItem($intRequestHeaderID = null)
    {
        if ($_SESSION['Is_main_branch'] == 1) {
            redirect('dashboard', 'refresh');
        }


        if (!$this->isAdmin) {
            if (!in_array('approveRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $request_header_data = $this->model_request->getRequestHeaderData($intRequestHeaderID);
        if (!$request_header_data) {
            redirect(base_url() . 'request/ViewRequest', 'refresh');
        }
        $request_detail_data = $this->model_request->getRequestDetailData($intRequestHeaderID);

        $this->data['request_header_data'] = $request_header_data;
        $this->data['request_detail_data'] = $request_detail_data;

        $this->render_template('request/AcceptRequestItem', 'Accept Requested Item', $this->data);
    }

    public function AcceptRequestByDetailID($RequestDetailID, $ItemID)
    {
        if ($_SESSION['Is_main_branch'] == 1) {
            redirect('dashboard', 'refresh');
        }


        if (!$this->isAdmin) {
            if (!in_array('acceptRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        $canAccept = $this->model_request->canAcceptRequest($RequestDetailID);

        if ($canAccept) {
            $result = $this->model_request->AcceptRequestByDetailID($RequestDetailID, $ItemID);
            if ($result == true) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'This Item Cannt Accept.';
        }

        echo json_encode($response);
    }

    public function AcceptAllRequestItems()
    {
        if ($_SESSION['Is_main_branch'] == 1) {
            redirect('dashboard', 'refresh');
        }


        if (!$this->isAdmin) {
            if (!in_array('acceptRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        // $canAcceptAll = true;
        // $item_count = count($this->input->post('intRequestDetailID'));

        // for ($i = 0; $i < $item_count; $i++) {
        //     // $canAccept = $this->model_request->canAcceptRequest($this->input->post('intRequestDetailID')[$i]);
        //     // if(!$canAccept)
        //     // {
        //     //     $canAcceptAll = false;
        //     // }
        // }

        // if($canAcceptAll == false)
        // {
        //     $response['success'] = false;
        //     $response['messages'] = 'All Items Cannot Accepted, Please Try One by One..!';
        // }
        // else{
        $result = $this->model_request->AcceptAllRequestItems();
        if ($result == true) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while Accept idetails. Please contact service provider.';
        }
        // }

        echo json_encode($response);
    }

    //-----------------------------------
    // Issued Request Item Cancel
    //-----------------------------------

    public function IssuedRequestItemCancel($intRequestHeaderID = null)
    {
        if ($_SESSION['Is_main_branch'] != 1) {
            redirect('dashboard', 'refresh');
        }

        if (!$this->isAdmin) {
            if (!in_array('cancelRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $request_header_data = $this->model_request->getRequestHeaderData($intRequestHeaderID);
        if (!$request_header_data) {
            redirect(base_url() . 'request/ViewRequest', 'refresh');
        }
        $request_detail_data = $this->model_request->getRequestDetailData($intRequestHeaderID);

        $this->data['request_header_data'] = $request_header_data;
        $this->data['request_detail_data'] = $request_detail_data;

        $this->render_template('request/IssuedRequestItemCancel', 'Accept Requested Item', $this->data);
    }

    public function IssuedRequestCancelByDetailID($RequestDetailID, $ItemID, $rv)
    {
        if (!$this->isAdmin) {
            if (!in_array('cancelRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        // $uid =$this->input->post('RequestDetailID');
        // $ItemID =$this->input->post('ItemID');
        // $rv = $this->input->post('rv');
        $response = array();

        $previousRV = $this->model_item->chkRv($ItemID);

        if ($previousRV['rv']  != $rv) {
            $response['success'] = false;
            $response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
        } else {
            $result = $this->model_request->IssuedRequestCancelByDetailID($RequestDetailID, $ItemID);

            if ($result == true) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
            }
        }

        echo json_encode($response);
    }

    public function IssuedCancelAllRequestItems()
    {
        if ($_SESSION['Is_main_branch'] != 1) {
            redirect('dashboard', 'refresh');
        }

        if (!$this->isAdmin) {
            if (!in_array('cancelRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = $this->model_request->issuedCancelAllRequestItems();


        if ($result == true) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
        }

        echo json_encode($response);
    }
}
