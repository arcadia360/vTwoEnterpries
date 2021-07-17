<style>
   .table,
    td {
        border: 1px solid #263238;
    }

    .table th {
        background-color: #263238 !important;
        color: #FFFFFF;
    }

    /* .modal-ku {
        width: 650px;
        margin: auto;
    } */

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
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container ">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Item Wise Last GRN Unit Price</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Item Wise Last GRN Unit Price</a></li>
                        <li class="breadcrumb-item active">View Item Wise Last GRN Unit Price</li>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Items :</label>
                            <select class="form-control select2" id="cmbItem">
                            <option value="0" disabled selected hidden>Select Item</option>
                                <?php foreach ($item_data as $k => $v) { ?>
                                    <option value="<?= $v['intItemID'] ?>"><?= $v['vcItemName'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                                            <!-- Date range -->
                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label>Date Range :</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right" name="daterange">
                            </div>
                        </div>
                    </div> -->
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
                            <thead>
                                <tr>
                                    <th>GRN No</th>
                                    <th>Invoice No</th>
                                    <th>Supplier</th>
                                    <th>Received Date</th>
                                    <th>GRN Qty</th>
                                    <th>Unit Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        <hr>
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


<script src="<?php echo base_url('resources/pageJS/itemWiseLastGRNUnitPrice.js') ?>"></script>