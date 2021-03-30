<?php
class Dispatch extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->not_logged_in();
    $this->load->model('model_item');
    $this->load->model('model_dispatch');
  }

  //-----------------------------------
  // Create Dispatch
  //-----------------------------------

  public function CreateDispatch()
  {
    if (!$this->isAdmin) {
      if (!in_array('createDispatch', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $item_data = $this->model_item->getOnlyRawItemData();
    $this->data['item_data'] = $item_data;
    $this->render_template('Dispatch/createDispatch', 'Create Dispatch',  $this->data);
  }

  public function SaveDispatch()
  {
    if (!$this->isAdmin) {
      if (!in_array('createDispatch', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $response = $this->model_dispatch->saveDispatch();

    echo json_encode($response);
  }

  //-----------------------------------
  // View Dispatch
  //-----------------------------------

  public function ViewDispatch()
  {
    if (!$this->isAdmin) {
      if (!in_array('viewDispatch', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $this->render_template('Dispatch/viewDispatch', 'View Dispatch');
  }

  public function FilterDispatchHeaderData($StatusType, $FromDate, $ToDate)
  {
    if (!$this->isAdmin) {
      if (!in_array('viewDispatch', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $result = array('data' => array());


    $dispatch_data = $this->model_dispatch->getDispatchHeaderData(null, $StatusType, $FromDate, $ToDate);

    // $this->data['grn_data'] = $grn_data;

    foreach ($dispatch_data as $key => $value) {

      $buttons = '';

      if (in_array('viewDispatch', $this->permission) || $this->isAdmin) {
        $buttons .= '<a class="button btn btn-default" href="' . base_url("Dispatch/ViewDispatchDetails/" . $value['intDispatchHeaderID']) . '" style="margin:0px 5px !important;"><i class="fas fa-eye"></i></a>';
      }

      if ($value['CancledUser'] == null && $value['ReceiveCompletedUser'] == null) { // Pending 
        if (in_array('cancelDispatch', $this->permission) || $this->isAdmin) {
          $buttons .= '<a class="button btn btn-default"  onclick="cancelDispatch(' . $value['intDispatchHeaderID'] . ') style="margin:0px 5px !important;"><i class="fas fa-trash-alt"></i></a>';
        }
      }
      // if ($value['ApprovedUser'] == null && $value['RejectedUser'] == null) { // Pending 
      //     if (in_array('editGRN', $this->permission) || $this->isAdmin) {
      //         $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/EditGRN/" . $value['intGRNHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-edit"></i></a>';
      //     }
      //     if (in_array('deleteGRN', $this->permission) || $this->isAdmin) {
      //         $buttons .= '<a class="button btn btn-default" onclick="removeGRN(' . $value['intGRNHeaderID'] . ')"><i class="fa fa-trash"></i></a>';
      //     }
      //     if (in_array('approveGRN', $this->permission) || $this->isAdmin) {
      //         $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/ApproveOrRejectGRN/" . $value['intGRNHeaderID']) . '"><i class="far fa-thumbs-up"></i></a>';
      //     }
      // }


      $result['data'][$key] = array(
        $value['vcDispatchNo'],
        $value['dtDispatchDate'],
        $value['dtCreatedDate'],
        $value['vcRemark'],
        $value['CreatedUser'],
        ($value['dtCancelledDate'] == NULL) ? "N/A" : $value['dtCancelledDate'],
        ($value['CancledUser'] == NULL) ? "N/A" : $value['CancledUser'],
        ($value['dtReceiveCompletedDate'] == NULL) ? "N/A" : $value['dtReceiveCompletedDate'],
        ($value['ReceiveCompletedUser'] == NULL) ? "N/A" : $value['ReceiveCompletedUser'],
        $buttons
      );
    }

    echo json_encode($result);
  }

  public function PrintDispatchDiv($intDispatchHeaderID)
  {
    if ($intDispatchHeaderID) {

      $dispatch_Header_Date =   $this->model_dispatch->getDispatchHeaderData($intDispatchHeaderID);
      $dispatch_Detail_Date =  $this->model_dispatch->getDispatcDetailsData($intDispatchHeaderID);

      $html = '
  
              <div id="myDiv" class="wrapper">
              <section class="invoice">
                <!-- title row -->
                <div class="row">
                  <div class="col-xs-12">
                    <h2 class="page-header">
                      "KNC Cake Boards - Dispatch Note"
                    </h2>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                  
                  <div class="col-sm-4 invoice-col">
  
                    <b>Date:</b> ' . $dispatch_Header_Date['dtCreatedDate'] . '<br>
                    <b>DispatchNo No:</b> ' . $dispatch_Header_Date['vcDispatchNo'] . '<br>
                    <b>Create User:</b> ' . $dispatch_Header_Date['CreatedUser'] . '<br>
               
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
  
                <!-- Table row -->
                <div class="row">
                  <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                      <thead>
                      <tr>
                        <th>Item name</th>
                        <th>Order Name</th>
                        <th>Unit</th>
                        <th>Dispatch Qty</th>
                      </tr>
                      </thead>
                      <tbody>';

      foreach ($dispatch_Detail_Date as $k => $v) {

        $html .= '<tr>
                            <td>' . $v['vcItemName'] . '</td>
                            <td>' . $v['vcOrderName'] . '</td>
                            <td>' . $v['vcMeasureUnit'] . '</td>
                            <td>' . $v['decDispatchQty'] . '</td>
                          </tr>';
      }

      // $html .= ' <tr align="right">
      //                     <th>Payment Mode:</th>
      //                     <td>' . $issue_Header_Date['vcPayment'] . '</td>
      //                   </tr>
      //                   <tr align="right">
      //                     <th>Sub Total:</th>
      //                     <td>' . $issue_Header_Date['decSubTotal'] . '</td>
      //                   </tr>
      //                   <tr align="right">
      //                     <th>Discount:</th>
      //                     <td>' . $issue_Header_Date['decDiscount'] . '</td>
      //             </tr>
      //             <tr align="right">
      //             <th>Grand Total:</th>
      //             <td>' . $issue_Header_Date['decGrandTotal'] . '</td>
      //           </tr>';

      $html .= '  </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
  </div>';

      echo $html;
    }
  }




  //-----------------------------------
  // Collect Dispatched Items
  //-----------------------------------

  public function CollectDispatchedItems()
  {
    if (!$this->isAdmin) {
      if (!in_array('collectDispatch', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $dispatch_nos = $this->model_dispatch->getCollectionPendingDispatchNos();
    $this->data['dispatch_nos'] = $dispatch_nos;
    $this->render_template('Dispatch/collectDispatchedItems', 'Collect Dispatched Items',  $this->data);
  }

  public function getDispatchedItemDetails()
  {
    if (!$this->isAdmin) {
      if (!in_array('collectDispatch', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $intDispatchHeaderID = $this->input->post('intDispatchHeaderID');
    $dispatched_item_data = $this->model_dispatch->getDispatchedItemDetails($intDispatchHeaderID);
    echo json_encode($dispatched_item_data);
  }

  public function SaveCollectDispatchItems(){
    if (!$this->isAdmin) {
      if (!in_array('collectDispatch', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $response = $this->model_dispatch->saveCollectDispatchItems();

    echo json_encode($response);
  }
}
