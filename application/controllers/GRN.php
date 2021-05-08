<?php

class GRN extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();
        $this->data['page_title'] = 'Goods Receive Note';
        $this->load->model('model_supplier');
        $this->load->model('model_item');
        $this->load->model('model_grn');
        $this->load->model('model_issue');
        $this->load->model('model_measureunit');
    }

    //-----------------------------------
    // Create GRN
    //-----------------------------------

    public function CreateGRN()
    {
        if (!$this->isAdmin) {
            if (!in_array('createGRN',$this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $supplier_data = $this->model_supplier->getSupplierData();
        $item_data = $this->model_item->getItemData();
        $payment_data = $this->model_issue->getPaymentTypes();
        $this->data['payment_data'] = $payment_data;
        $this->data['supplier_data'] = $supplier_data;
        $this->data['item_data'] = $item_data;

        $this->render_template('GRN/createGRN', 'Create GRN',  $this->data);
    }

    public function SaveGRN()
    {
        if (!$this->isAdmin) {
            if (!in_array('createGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        $this->form_validation->set_rules('supplier', 'supplier', 'trim|required');
        $this->form_validation->set_rules('invoice_no', 'invoice no', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $result = $this->model_grn->saveGRN();
            if ($result == true) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
            }
        } else {
            $response['success'] = false;
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($response);
    }

    public function getMeasureUnitByItemID($ItemID)
    {
        $data = $this->model_measureunit->getMeasureUnitByItemID($ItemID);
        echo json_encode($data);
    }

    //-----------------------------------
    // View GRN
    //-----------------------------------


    // public function ViewGRN($StatusType = 0, $FromDate = "", $ToDate = "2020-10-10")
    public function ViewGRN()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->render_template('GRN/viewGRN','View GRN');
    }

    public function ViewGRNDetails($GRNHeaderID)
    {
        if (!$this->isAdmin) {
            if (!in_array('viewGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        if (!$GRNHeaderID) {
            redirect('dashboard', 'refresh');
        }

        $grn_header_data = $this->model_grn->getGRNHeaderData($GRNHeaderID);

        if (isset($grn_header_data)) {
            $grn_detail_data = $this->model_grn->getGRNDetailData($GRNHeaderID);
            $supplier_data = $this->model_supplier->getSupplierData();
            $item_data = $this->model_item->getOnlyRawItemData();

            $this->data['supplier_data'] = $supplier_data;
            $this->data['item_data'] = $item_data;

            $this->data['grn_detail_data'] = $grn_detail_data;
            $this->data['grn_header_data'] = $grn_header_data;

            $this->render_template('GRN/viewGRNDetail', 'View GRN', $this->data);
        }else{
            redirect(base_url() . 'GRN/viewGRNDetail', 'refresh');
        }

       
    }

    public function FilterGRNHeaderData($StatusType, $FromDate, $ToDate)
    {
        if (!$this->isAdmin) {
            if (!in_array('viewGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());


        $grn_data = $this->model_grn->getGRNHeaderData(null, $StatusType, $FromDate, $ToDate);

        // $this->data['grn_data'] = $grn_data;

        foreach ($grn_data as $key => $value) {

            $buttons = '';
            $badge = '';

            if (in_array('viewGRN', $this->permission) || $this->isAdmin) {
                $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/ViewGRNDetails/" . $value['intGRNHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-eye"></i></a>';
            }

            if ($value['intSupplierSettlementHeaderID'] != 'N/A') {
                if (in_array('viewSupplierCreditSettlement', $this->permission) || $this->isAdmin) {
                    $buttons .= ' <button type="button" class="btn btn-default" onclick="viewGRNWiseSettlementDetails(' . $value['intGRNHeaderID'] . ')" data-toggle="modal" data-target="#viewModal"><i class="fas fa-money-bill-alt"></i></button>';
                }
            }    

            if ($value['ApprovedUser'] == null && $value['RejectedUser'] == null) { // Pending 
                if (in_array('editGRN', $this->permission) || $this->isAdmin) {
                    $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/EditGRN/" . $value['intGRNHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-edit"></i></a>';
                }
                if (in_array('deleteGRN', $this->permission) || $this->isAdmin) {
                    $buttons .= '<a class="button btn btn-default" onclick="removeGRN(' . $value['intGRNHeaderID'] . ')"><i class="fa fa-trash"></i></a>';
                }
                if (in_array('approveGRN', $this->permission) || $this->isAdmin) {
                    $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/ApproveOrRejectGRN/" . $value['intGRNHeaderID']) . '"><i class="far fa-thumbs-up"></i></a>';
                }
            }

            
            if($value['TotalPaidAmount'] <  $value['decGrandTotal'] &&  $value['intPaymentTypeID'] == 2) 
            {
                $badge = '<span class="badge badge-secondary" style="padding: 4px 10px; float:right; margin-right:10px;">Partially Paid</span>';
            }
        
            if ($value['RejectedUser'] != null) {
                $badge = '<span class="badge badge-danger" style="padding: 4px 10px; float:right; margin-right:10px;">Rejected GRN</span>';
            }
            else if($value['TotalPaidAmount'] == 0 &&  $value['intPaymentTypeID'] == 2) 
            {
                $badge = '<span class="badge badge-warning" style="padding: 4px 10px; float:right; margin-right:10px;">Total Pending</span>';
            }  

            if($value['TotalPaidAmount'] ==  $value['decGrandTotal'] || $value['intPaymentTypeID'] == 1) 
            {
                $badge = '<span class="badge badge-success" style="padding: 4px 10px; float:right; margin-right:10px;">Fully Paid</span>';
            }

         

            $result['data'][$key] = array(
                $value['vcGRNNo'],
                $value['vcInvoiceNo'],
                $value['vcSupplierName'],
                $value['vcPaymentType'],
                number_format((float)$value['decSubTotal'], 2, '.', ''),
                number_format((float)$value['decDiscount'], 2, '.', ''),
                number_format((float)$value['decGrandTotal'], 2, '.', ''),
                $value['dtReceivedDate'],
                $value['vcRemark'],
                $value['dtCreatedDate'],
                $value['CreatedUser'],
                ($value['dtApprovedOn'] == NULL) ? "N/A" : $value['dtApprovedOn'],
                ($value['ApprovedUser'] == NULL) ? "N/A" : $value['ApprovedUser'],
                ($value['dtRejectedOn'] == NULL) ? "N/A" : $value['dtRejectedOn'],
                ($value['RejectedUser'] == NULL) ? "N/A" : $value['RejectedUser'],
                $badge,
                $buttons
            );
        }

        echo json_encode($result);
    }

    //-----------------------------------
    // Edit GRN
    //-----------------------------------

    public function EditGRN($GRNHeaderID)
    {
        if (!$this->isAdmin) {
            if (!in_array('editGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        if (!$GRNHeaderID) {
            redirect('dashboard', 'refresh');
        }

        $grn_header_data = $this->model_grn->getGRNHeaderData($GRNHeaderID);

        if ($grn_header_data['dtApprovedOn'] != NULL || $grn_header_data['dtRejectedOn'] != NULL) {
            // Notify To Admin & Redirect
            redirect(base_url(). 'GRN/ViewGRN', 'refresh');
        }

        $grn_detail_data = $this->model_grn->getGRNDetailData($GRNHeaderID);
        $supplier_data = $this->model_supplier->getSupplierData();
        $item_data = $this->model_item->getOnlyRawItemData();

        $this->data['supplier_data'] = $supplier_data;
        $this->data['item_data'] = $item_data;

        $this->data['grn_detail_data'] = $grn_detail_data;
        $this->data['grn_header_data'] = $grn_header_data;


        $this->render_template('GRN/editGRN','Edit GRN', $this->data);
    }

    public function EditGRNDetails($GRNHeaderID){
        if (!$this->isAdmin) {
            if (!in_array('editGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        $this->form_validation->set_rules('supplier', 'supplier', 'trim|required');
        $this->form_validation->set_rules('invoice_no', 'invoice no', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $result = $this->model_grn->editGRN($GRNHeaderID);
            if ($result == true) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while editing the GRN idetails. Please contact service provider.';
            }
        } else {
            $response['success'] = false;
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($response);
    }

// Remove GRN

public function removeGRN(){
        if (!$this->isAdmin) {
            if (!in_array('deleteGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $intGRNHeaderID = $this->input->post('intGRNHeaderID');
        $response = array();
        if ($intGRNHeaderID) {

            $canRemove = $this->model_grn->canRemoveGRN($intGRNHeaderID);

            if ($canRemove) {
                
                $delete = $this->model_grn->removeGRN($intGRNHeaderID);

                if ($delete == true) {
                    $response['success'] = true;
                    $response['messages'] = "Deleted !";
                } else {
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while removing the GRN information !";
                }

            }else{
                $response['success'] = false;
                $response['messages'] = "You can't remove this GRN, Please check and try again !";
            }

           
        } else {
            $response['success'] = false;
            $response['messages'] = "Please refersh the page again !!";
        }
        echo json_encode($response);
}

public function ApproveOrRejectGRN($GRNHeaderID){
        if (!$this->isAdmin) {
            if (!in_array('approveGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        if (!$GRNHeaderID) {
            redirect('dashboard', 'refresh');
        }

        $grn_header_data = $this->model_grn->getGRNHeaderData($GRNHeaderID);

        if ($grn_header_data['dtApprovedOn'] != NULL || $grn_header_data['dtRejectedOn'] != NULL) {
            // Notify To Admin & Redirect
            redirect(base_url() . 'GRN/ViewGRN', 'refresh');
        }

        $grn_detail_data = $this->model_grn->getGRNDetailData($GRNHeaderID);
        // $supplier_data = $this->model_supplier->getSupplierData();
        $item_data = $this->model_item->getOnlyRawItemData();

        // $this->data['supplier_data'] = $supplier_data;
        $this->data['item_data'] = $item_data;

        $this->data['grn_detail_data'] = $grn_detail_data;
        $this->data['grn_header_data'] = $grn_header_data;


        $this->render_template('GRN/approveGRN', 'Approve / Reject GRN', $this->data);
}

public function ApproveGRN(){
        if (!$this->isAdmin) {
            if (!in_array('approveGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $intGRNHeaderID = $this->input->post('intGRNHeaderID');

        $response = array();
        if ($intGRNHeaderID) { 

            $canApproveOrReject = $this->model_grn->canRemoveGRN($intGRNHeaderID);

            if ($canApproveOrReject) {

                $approved = $this->model_grn->approveGRN($intGRNHeaderID);

                if ($approved == true) {
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while approving the GRN information !";
                }
            } else {
                $response['success'] = false;
                $response['messages'] = "You can't approve this GRN, Please check and try again !";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Please refersh the page again !!";
        }
        echo json_encode($response);
}

    public function RejectGRN()
    {
        if (!$this->isAdmin) {
            if (!in_array('approveGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $intGRNHeaderID = $this->input->post('intGRNHeaderID');
        $response = array();
        if ($intGRNHeaderID) {

            $canApproveOrReject = $this->model_grn->canRemoveGRN($intGRNHeaderID);

            if ($canApproveOrReject) {

                $approved = $this->model_grn->rejectGRN($intGRNHeaderID);

                if ($approved == true) {
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while rejecting the GRN information !";
                }
            } else {
                $response['success'] = false;
                $response['messages'] = "You can't reject this GRN, Please check and try again !";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Please refersh the page again !!";
        }
        echo json_encode($response);
    }

}
