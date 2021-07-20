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
                    <h1>Issue Wise Cost & Profit Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Report</a></li>
                        <li class="breadcrumb-item active">Issue Wise Cost & Profit</li>
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
                    <div class="form-group col-md-2">
                        <label>Issue No</label>
                        <select class="form-control select2" style="width: 100%;" id="cmbIssueNo" name="cmbIssueNo">
                            <option value=" 0" disabled selected hidden>Select Issue No</option>
                            <?php foreach ($issue_No as $k => $v) { ?>
                                <option value="<?= $v['intIssueHeaderID'] ?>"><?= $v['vcIssueNo'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="box">
                    <div class="box-body">
                        <!-- <div>
                            Toggle column: <a class="toggle-vis" data-column="0">ID</a> - <a class="toggle-vis" data-column="1">GRN No</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
                        </div> -->
                        <table id="manageTable" class="table table-bordered table-striped">
                            <!-- style="display:block !important;" -->
                            <thead style="text-align: center;">
                                <tr>
                                    <th class="tableHeader">Item Name</th>
                                    <th class="tableHeader">GRN Value</th>
                                    <th class="tableHeader">Issued Value</th>
                                    <th class="tableHeader">Issued Qty</th>
                                    <th class="tableHeader">Issued Discount (%)</th>
                                    <th class="tableHeader">Issued Amount</th>
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
                                    <th class="tableFooter"></th>
                                    <th class="tableFooter"></th>
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


<div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModal">View Settlement Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <table class="table" id="IssueItemTable">
                <thead>
                    <tr>
                        <th style="width: 150px; text-align:center;">Receipt No</th>
                        <th style="width: 100px; text-align:center;">Cheque No</th>
                        <th style="width: 20px; text-align:center;">Realized</th>
                        <th style="width: 80px; text-align:center;">Paid Amount</th>
                    </tr>
                </thead>
                <tbody>


                </tbody>
            </table>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="<?php echo base_url('resources/pageJS/issueWiseCostAndProfitReport.js') ?>"></script>