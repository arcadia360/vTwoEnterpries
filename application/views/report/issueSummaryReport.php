<!-- <style type="text/css">
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    } 
</style> -->

<style>
    .table,
    td {
        border: 1px solid #263238;
    }

    /* .table th {
        background-color: #263238 !important;
        color: #FFFFFF;
    } */

    /* .modal-ku {
        width: 650px;
        margin: auto;
    } */

    th.tableHeader {
        background-color: #263238;
        color: #FFFFFF;
    }

    th.tableFooter {
        background-color: #6B6F70;
        color: #FFFFFF;
    }

    tbody td {
        padding: 0 !important;
    }

    div.dt-top-container {
        display: grid;
        grid-template-columns: auto auto auto;
    }

    div.dt-center-in-div {
        margin: 0 auto;
    }

    div.dt-filter-spacer {
        margin: 10px 0;
    }

    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 5px 10px !important;
    }
    
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container ">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Issue Summary Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Report</a></li>
                        <li class="breadcrumb-item active">Issue Summary Report</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

    </section>
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <!-- Date range -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="customer">Customer</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbcustomer" name="cmbcustomer">
                                <option value="0" selected hidden>All Customers</option>
                                <?php foreach ($customer_data as $k => $v) { ?>
                                    <option value="<?= $v['intCustomerID'] ?>"><?= $v['vcCustomerName'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Date Range :</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right" name="daterange">
                        </div>
                        <!-- /.input group -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="box">
                    <div class="box-body">
                        <!-- <div>
                            Toggle column: <a class="toggle-vis" data-column="0">ID</a> - <a class="toggle-vis" data-column="1">GRN No</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
                        </div> -->
                        <table id="manageTable2" class="table table-bordered table-striped">
                            <!-- style="display:block !important;" -->
                            <thead style="text-align: center;">
                                <tr>
                                    <th class="tableHeader">Item Name</th>
                                    <th class="tableHeader">GRN Value</th>
                                    <th class="tableHeader">Issued Value</th>
                                    <th class="tableHeader">Issued Qty</th>
                                    <th class="tableHeader">Issued Discount (%)</th>
                                    <th class="tableHeader">Issued Amount</th>
                                    <th class="tableHeader">Returned Amount</th>
                                    <th class="tableHeader">GRN Amount</th>
                                    <th class="tableHeader">Profit Amount</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="tableFooter" style="text-align: center;"></th>
                                    <th class="tableFooter"></th>
                                    <th class="tableFooter"></th>
                                    <th class="tableFooter"></th>
                                    <th class="tableFooter"></th>
                                    <th class="tableFooter" style="text-align: right;"></th>
                                    <th class="tableFooter" style="text-align: right;"></th>
                                    <th class="tableFooter" style="text-align: right;"></th>
                                    <th class="tableFooter" style="text-align: right;"></th>
                                </tr>
                            </tfoot>
                        </table>
                        <hr>
                        <!-- Color tags : <span class="badge badge-pill badge-warning">Pending Approvals</span> <span class="badge badge-pill badge-light" style="border: 1px #000000 solid;">Approved GRNs</span> <span class="badge badge-pill badge-danger">Rejected GRNs</span> -->
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.row -->
    </section>
</div>




<script src="<?php echo base_url('resources/pageJS/issueSummaryReport.js') ?>"></script>