<style>
    .table,
    td {
        border: 1px solid #263238;
    }

    .table th {
        background-color: #263238 !important;
        color: #FFFFFF;
    }

    tbody td {
        padding: 0 !important;
    }

    .button {
        width: 35px;
        height: 35px;
        color: #fff;
        display: flex;
        align-items: center;
        cursor: pointer;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(225, 225, 225, 0.4);
        border: none;
    }


    .center-items {
        margin: 0 auto;
    }

    input[type=text]:disabled {
        background: #ffffff;
        /* border: 1px solid #ced4da !important; */
        border: none;

    }

    input[type=text]:read-only {
        background: #ffffff;
        border-color: #ffffff;
    }

    .card {
        box-shadow: none;
        margin: 0;
    }

    /* .table td, .table th {
    padding: 0;
    vertical-align: top;
    border:0;
} */

    /* .select2-results__option:nth-child(4) {
        background-color: red !important;
    } */

    .select2-results__option[id*="Test"] {
        color: red;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Collect Dispatched Items</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dispatch</a></li>
                        <li class="breadcrumb-item active">Collect Dispatched Items</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <form role="form" class="add-form" method="post" action="<?= base_url('Dispatch/SaveCollectDispatchItems') ?>" id="collectDispatchItem">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Dispatch No</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbDispatchNo" name="cmbDispatchNo">
                                <option value=" 0" disabled selected hidden>Select Item</option>
                                <?php foreach ($dispatch_nos as $k => $v) { ?>
                                    <option value="<?= $v['intDispatchHeaderID'] ?>"><?= $v['vcDispatchNo'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <table class="table" id="dispatchItemTable">
                        <thead>
                            <tr>
                                <th hidden>Dispatch Detail ID</th>
                                <th hidden>Item ID</th>
                                <th style="text-align:center;">Output Finish Item</th>
                                <th style="width: 100px; text-align:center;">Unit</th>
                                <th hidden>Cutting Order Detail ID</th>
                                <th style="width: 100px; text-align:center;">Expected Qty</th>
                                <th style="width: 100px; text-align:center;">Received Qty</th>
                                <th style="width: 100px; text-align:center;">Balance Qty</th>
                                <th style="width: 100px; text-align:center;">Receive Qty</th>
                                <th hidden>rv</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                    <td hidden><input type="number" class="form-control" name="txtItemID[]" min="0"></td>
                                    <td><input type="text" class="form-control" name="txtItemName[]" id="txtItemName" style="text-align:center;" disabled></td>
                                    <td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" disabled></td>
                                    <td hidden><input type="number" class="form-control" name="txtCuttingOrderID[]" min="0"></td>
                                    <td><input type="text" class="form-control" name="txtCuttingOrderName[]" id="txtCuttingOrderName" style="text-align:center;" disabled></td>
                                    <td><input type="text" class="form-control only-decimal" name="txtExpectedQty[]" id="txtExpectedQty" style="text-align:right;" disabled></td>
                                    <td><input type="text" class="form-control only-decimal" name="txtReceiveQty[]" id="txtReceiveQty" style="text-align:right;"></td>
                                    <td hidden><input type="text" class="form-control" name="txtRv[]" id="txtRv"></td>
                                </tr> -->

                        </tbody>
                    </table>

                    <div class="row" style="border-top:1px solid #dee2e6;">
                        <div class="col-md-6">
                            <p style="color: #c2c7d0;" id="itemCount">Item Count : 0</p>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-6">
                            <button type="button" id="btnSubmit" class="btn btn-lg btn-info btn-flat float-right" style="margin-top: 10px;"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Submit</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="<?php echo base_url('resources/pageJS/collectDispatchedItem.js') ?>"></script>