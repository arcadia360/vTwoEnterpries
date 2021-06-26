<?php

class Issue extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->not_logged_in();
    $this->data['page_title'] = 'Issue';
    $this->load->model('model_supplier');
    $this->load->model('model_item');
    $this->load->model('model_measureunit');
    $this->load->model('model_customer');
    $this->load->model('model_issue');
    $this->load->model('model_utility');
  }

  //-----------------------------------
  // Create Issue
  //-----------------------------------

  public function CreateIssue()
  {
    if (!$this->isAdmin) {
      if (!in_array('createIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $sales_rep_data = $this->model_utility->getfetchSalesRepData();
    $customer_data = $this->model_customer->getCustomerData();
    // $item_data = $this->model_item->getItemData();
    // $item_data = $this->model_item->getStockAvailableItemData();
    $payment_data = $this->model_issue->getPaymentTypes();
    $this->data['payment_data'] = $payment_data;
    $this->data['customer_data'] = $customer_data;
    // $this->data['item_data'] = $item_data;
    $this->data['sales_rep_data'] = $sales_rep_data;

    $this->render_template('Issue/createIssue', 'Create Issue',  $this->data);
  }

  public function getItemData()
  {
    $data = $this->model_item->getItemData();
    echo json_encode($data);
  }

  public function getStocktItemData()
  {
    $data = $this->model_item->getStocktItemData();
    echo json_encode($data);
  }

  public function getStockAvailableItemData()
  {
    $data = $this->model_item->getStockAvailableItemData();
    echo json_encode($data);
  }


  public function SaveIssue()
  {
    if (!$this->isAdmin) {
      if (!in_array('createIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $response = $this->model_issue->saveIssue();
    if ($response['success'] == true) {
      $response['issueNote'] = $this->PrintIssueDiv($response['intIssueHeaderID'], 0);
    }
    // $response['issueNote'] = "ABC";


    echo json_encode($response);
  }

  public function getIssuedHeaderData()
  {
    if (!$this->isAdmin) {
      if (!in_array('viewIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $intIssueHeaderID = $this->input->post('intIssueHeaderID');
    $issue_Header_Date =  $this->model_issue->GetIssueHeaderData($intIssueHeaderID);
    echo json_encode($issue_Header_Date);
  }

  public function PrintIssueDiv($intIssueHeaderID, $IsJasonReturn = null)
  {
    if ($intIssueHeaderID) {

      $issue_Header_Date =  $this->model_issue->GetIssueHeaderData($intIssueHeaderID);
      $issue_Detail_Date =  $this->model_issue->GetIssueDetailsData($intIssueHeaderID);

      //       $html = '

      // <body style="font-family: Teko, sans-serif;">
      //     <div class="page">
      //         <h1>INVOICE</h1>
      //         <p class="address"><b>KNC Cake Boards & Boxes</b><br>No.124A<br>Galle Road,
      //             Pohoddaramulla<br>Wadduwa<br>0714874746 / 0777206898</p>
      //         <hr>
      //         <table width="100%">
      //             <tr>
      //                 <td width="60%">
      //                     <h3 style="margin: 0px;">INVOICED TO</h3>
      //                     <p style="margin: 0px;">Customer Name</p>
      //                     <p style="margin: 0px;">Address Line 2</p>
      //                     <p style="margin: 0px;">Address Line 2</p>
      //                     <p style="margin: 0px;">Address Line 3</p>
      //                 </td>
      //                 <td>
      //                     <h3 style="margin: 0px;">INVOICE #</h3>
      //                     <h3 style="margin: 0px;">INVOICE DATE</h3>
      //                 </td>
      //                 <td>
      //                     <h3 style="margin: 0px;">: &nbsp; ' . $issue_Header_Date['vcIssueNo'] . '</h3>
      //                     <h3 style="margin: 0px;">: &nbsp; ' . $issue_Header_Date['dtCreatedDate'] . '</h3>
      //                 </td>
      //             </tr>
      //         </table>
      //         <table width="100%" style="border-collapse: collapse; border: 1px solid black; margin-top: 10px;">
      //             <tr>
      //                 <th style=" border: 1px solid black;">
      //                     <center><h4>DESCRIPTION</h4></center>
      //                 </th>
      //                 <th style=" border: 1px solid black;">
      //                     <center><h4>QTY</h4></center>
      //                 </th>
      //                 <th style=" border: 1px solid black;">
      //                     <center><h4>UNIT PRICE</h4></center>
      //                 </th>
      //                 <th style=" border: 1px solid black;">
      //                     <center><h4>AMOUNT</h4></center>
      //                 </th>
      //             </tr>';

      $html = '

<body>
    <div style="text-align: center; border-bottom: 1px solid #000000; margin-bottom:10px; padding-bottom:10px;">
        <h1 style="font-size: 50px;"><strong>VTwo Enterprises</strong></h1>
        <h3>General Hardware Merchants</h3>
        <h5>
            NO. S.61/93, Maithree Bodhiraja Mawatha, Colombo 12 </br>
            0113440404 / 0760147843
        </h5>
        <h4><strong>INVOICE</strong></h4>
    </div>
     
    <table width="100%" style="color:#000000;">
        <tr>
            <td width="85%">
                <h5><strong>' . $issue_Header_Date['vcCustomerName'] . '</strong></h5>
                <h5>' . $issue_Header_Date['vcBuildingNumber'] . ' , ' . $issue_Header_Date['vcStreet'] . ',</h5>
                <h5>' . $issue_Header_Date['vcCity'] . '.</h5>
                <h5>' . $issue_Header_Date['vcContactNo1'] . '</h5>
                <h5>' . $issue_Header_Date['vcContactNo2'] . '</h5>
            </td>
            <td>
                <div style="text-align: left;">
                    <h5>DATE : ' . $issue_Header_Date['dtCreatedDate'] . '</h5>
                    <h5>INVOICE # : ' . $issue_Header_Date['vcIssueNo'] . '</h5>
                    <h5>TERM : <strong>' . $issue_Header_Date['vcPaymentType'] . '</strong></h5>
                </div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-top: 1px solid #000000; border-right: 1px solid #000000; font-size: 1.2em; color:#000000;">
        <tr style="text-align: center; height: 40px;">
            <th width="30px" style="border: 1px solid #000000;">#</th>
            <th style="border: 1px solid #000000;">ITEM DESCRIPTION</th>
            <th width="80px" style="border: 1px solid #000000;">UNIT</th>
            <th width="100px" style="border: 1px solid #000000;">UNIT PRICE</th>
            <th width="80px" style="border: 1px solid #000000;">QTY</th>
            <th width="60px" style="border: 1px solid #000000;">DIS.(%)</th>
            <th width="170px" style="border: 1px solid #000000;">TOTAL</th>
        </tr>';


      $resultCount = 0;
      foreach ($issue_Detail_Date as $k => $v) {

        $html .= '
    
        <tr style="font-size: 1.2em;">
            <td style="text-align: center; border-left: 1px solid #000000;">' . $v['num'] . '</td>
            <td style="border-left: 1px solid #000000;">&nbsp;' . $v['vcItemName'] . '</td>
            <td style="text-align: center; border-left: 1px solid #000000;">' . $v['vcMeasureUnit'] . '&nbsp;</td>
            <td style="text-align: right; border-left: 1px solid #000000;">' . $v['decUnitPrice'] . '&nbsp;</td>
            <td style="text-align: center; border-left: 1px solid #000000;">' . $v['decIssueQty'] . '&nbsp;</td>
            <td style="width:60px; text-align: center; border-left: 1px solid #000000;">' . $v['decDiscountPercentage'] . '%</td>
            <td style="text-align: right; border-left: 1px solid #000000;">' . $v['decTotalPrice'] . '&nbsp;</td>
        </tr>
            ';

        $resultCount++;
      }

      for ($i = 0; $i < (25 - $resultCount); $i++) {
        $html .= '   
        <tr>
          <td style="text-align: center; border-left: 1px solid #000000;"></td>
          <td style="border-left: 1px solid #000000;">&nbsp;</td>
          <td style="text-align: center; border-left: 1px solid #000000;">&nbsp;</td>
          <td style="text-align: right; border-left: 1px solid #000000;">&nbsp;</td>
          <td style="text-align: center; border-left: 1px solid #000000;">&nbsp;</td>
          <td style="text-align: center; border-left: 1px solid #000000;">&nbsp;</td>
          <td style="text-align: right; border-left: 1px solid #000000;">&nbsp;</td>
      </tr>';
      }


      $html .= '
      <tr style="font-size: 1.2em;">
            <td style="border-top: 1px solid #000000;"></td>
            <td style="text-align: center; border-top: 1px solid #000000;"></td>
            <td style="border-top: 1px solid #000000;">&nbsp;</td>
            <td style="text-align: right; border-top: 1px solid #000000;">&nbsp;</td>
            <td colspan="2" style="border: 1px solid #000000;">&nbsp;Sub Total</td>
            <td style="text-align: right; border: 1px solid #000000;">' . $issue_Header_Date['decSubTotal'] . '&nbsp;</td>
        </tr>
        <tr style="font-size: 1.2em;">
            <td></td>
            <td></td>
            <td>&nbsp;</td>
            <td style="text-align: right;">&nbsp;</td>
            <td colspan="2" style="border: 1px solid #000000;">&nbsp;Discount (%)</td>
            <td style="text-align: right; border: 1px solid #000000;">' . $issue_Header_Date['decDiscount'] . '&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>&nbsp;</td>
            <td style="text-align: right;">&nbsp;</td>
            <td colspan="2" style="border: 1px solid #000000; font-size: 20px; font-weight:600;">&nbsp;Grand Total</td>
            <td style="text-align: right; border: 1px solid #000000; font-size: 20px; font-weight:600;">' . $issue_Header_Date['decGrandTotal'] . '&nbsp;</td>
        </tr>
    </table>
</br>
</br>
</br>
</br>
</br>

    <table width="100%">
        <tr style="border-top: 1px solid #000000; text-align: center; font-size:1.2em;">
            <td width="34%">Customer Signature & Stamp</td>
            <td width="33%">Deliverd By</td>
            <td width="33%">Prepared By</td>
        </tr>
    </table>
</body>
   ';

      if ($IsJasonReturn == 1) {
        $response['issueNote'] =  $html;
        echo json_encode($response);
      } else {
        return $html;
      }
    }
  }

  //-----------------------------------
  // View Issue
  //-----------------------------------

  public function ViewIssue()
  {
    if (!$this->isAdmin) {
      if (!in_array('viewIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $payment_data = $this->model_issue->getPaymentTypes();
    $customer_data = $this->model_customer->getCustomerData();
    $this->data['payment_data'] = $payment_data;
    $this->data['customer_data'] = $customer_data;
    $this->render_template('Issue/ViewIssue', 'View Issue', $this->data);
  }

  public function FilterIssueHeaderData($PaymentType, $CustomerID, $FromDate, $ToDate)
  {
    if (!$this->isAdmin) {
      if (!in_array('viewIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $result = array('data' => array());


    $issue_data = $this->model_issue->GetIssueHeaderData(null, $PaymentType, $CustomerID, $FromDate, $ToDate);

    // $this->data['grn_data'] = $grn_data;

    foreach ($issue_data as $key => $value) {

      $buttons = '';
      $badge = '';

      if (in_array('viewIssue', $this->permission) || $this->isAdmin) {
        $buttons .= '<a class="button btn btn-default" href="' . base_url("Issue/ViewIssueDetails/" . $value['intIssueHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-eye"></i></a>';
        $buttons .= ' <button type="button" class="btn btn-default" onclick="viewPrintIssueDiv(' . $value['intIssueHeaderID'] . ')"><i class="fa fa-print"></i></button>';
      }

      if ($value['PaymentViewButton'] != 'N/A') {
        if (in_array('viewIssueCreditSettlement', $this->permission) || $this->isAdmin) {
          $buttons .= ' <button type="button" class="btn btn-default" onclick="viewIssuetWiseSettlementDetails(' . $value['intIssueHeaderID'] . ')" data-toggle="modal" data-target="#viewModal"><i class="fas fa-money-bill-alt"></i></button>';
        }
      }

      if ($value['decGrandTotal'] > $value['PaidTotal'] && $value['PaymentViewButton'] != 'N/A') {
        $badge = '<span class="badge badge-secondary" style="padding: 4px 10px; float:right; margin-right:10px;">Partially Paid</span>';
      } else if ($value['decGrandTotal'] <= $value['PaidTotal'] && $value['PaymentViewButton'] != 'N/A') {
        $badge = '<span class="badge badge-success" style="padding: 4px 10px; float:right; margin-right:10px;">Fully Paid</span>';
      } else if ($value['PaidTotal'] == NULL) {
        $badge = '<span class="badge badge-warning" style="padding: 4px 10px; float:right; margin-right:10px;">Total Pending</span>';
      }


      $result['data'][$key] = array(
        $value['vcIssueNo'],
        $value['vcCustomerName'],
        $value['dtIssueDate'],
        $value['dtCreatedDateWithTime'],
        $value['vcFullName'],
        $value['vcPaymentType'],
        number_format((float)$value['decSubTotal'], 2, '.', ''),
        number_format((float)$value['decDiscount'], 2, '.', ''),
        number_format((float)$value['decGrandTotal'], 2, '.', ''),
        $value['vcRemark'],
        $badge,
        $buttons
      );
    }

    echo json_encode($result);
  }

  public function ViewIssueDetails($IssueHeaderID)
  {
    if (!$this->isAdmin) {
      if (!in_array('viewIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    if (!$IssueHeaderID) {
      redirect('dashboard', 'refresh');
    }

    $issue_header_data = $this->model_issue->GetIssueHeaderData($IssueHeaderID, null, null, null, null);

    if (isset($issue_header_data)) {

      $issue_detail_Date =  $this->model_issue->GetIssueDetailsData($IssueHeaderID);

      $this->data['issue_detail_Date'] = $issue_detail_Date;
      $this->data['issue_header_data'] = $issue_header_data;

      $this->render_template('Issue/viewIssueDetail', 'View Issue', $this->data);
    } else {
      redirect(base_url() . 'Issue/viewIssueDetail', 'refresh');
    }
  }

  public function viewIssuetWiseSettlementDetails()
  {
    $IssueHeaderID = $this->input->post('intIssueHeaderID');
    $data = $this->model_issue->getIssuetWiseSettlementDetails($IssueHeaderID);
    echo json_encode($data);
  }


  //-----------------------------------
  // Issue Return
  //-----------------------------------

  public function IssueReturn()
  {
    if (!$this->isAdmin) {
      if (!in_array('issueReturn', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $issue_No = $this->model_issue->getReturnIssueNo();
    $this->data['issue_No'] = $issue_No;


    $this->render_template('Issue/IssueReturn', 'Issue Return', $this->data);
  }


  public function ViewIssueDetailsToReturnData()
  {
    if (!$this->isAdmin) {
      if (!in_array('viewIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $intIssueHeaderID = $this->input->post('intIssueHeaderID');
    // $issued_item_data = $this->model_issue->GetIssueDetailsData($intIssueHeaderID);
    $issued_item_data = $this->model_issue->GetIssueDetailsToReturnData($intIssueHeaderID);
    echo json_encode($issued_item_data);
  }

  public function SaveIssueReturn()
  {
    if (!$this->isAdmin) {
      if (!in_array('issueReturn', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $IssueHeaderData = $this->model_issue->GetIssueHeaderData($this->input->post('cmbIssueNo'), null, null, null, null);
    $CustomerID =  $IssueHeaderData['intCustomerID'];
    $IssueHeaderID = $this->input->post('cmbIssueNo');

    $result =  $this->model_issue->chkNullCustomerAdvancePayment($CustomerID);

    if ($result) {
      $ckhExist = $this->model_issue->chkExistsReceiptDetails($IssueHeaderID);
      if ($ckhExist <> '') {
        if ($ckhExist[0]['value'] == 1) {
          $response['success'] = false;
          $response['messages'] = "Already Payment added this Issue. Please Cancel Payment Details !";
        } else {
          $response = $this->model_issue->saveIssueReturn();
        }
      }
    } else {
      $response['success'] = false;
      $response['messages'] = 'Already Have a Advance Payment Please Delete this Customer Advance Amount !';
    }

    echo json_encode($response);
  }
}
