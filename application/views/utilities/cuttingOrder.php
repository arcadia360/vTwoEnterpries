<!-- Content Wrapper. Contains page content -->
<style>
    /* body {
        background: #f6f6f6;
    } */

    /* #table {
        position: relative;
        overflow: hidden;
        margin-top: 50px;
    } */

    /* table {
        background: black;
        box-shadow: 0 10px 30px rgba(225, 225, 225, 0.5);
    } */



    .first-tr {
        /* background-color: #c2c7d0; */
        border: 2px solid #3d9970;
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

    .red,
    .remove {
        background: #e74c3c;
    }

    .green {
        background: #3d9970;
    }

    /* tr>td {
        position: relative;
    } */

    .static {
        position: static !important;
    }

    .center-items {
        /* align-items: center; */
        margin: 0 auto;

    }

    input[type=text]:disabled {
        background: #ffffff;
        border: 1px solid #ced4da !important;

    }

    input[type=text]:read-only {
        background: #ffffff;
        border-color: #ffffff;
    }
</style>


<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cutting Orders</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Utilities</a></li>
                        <li class="breadcrumb-item active">Cutting Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="card">
            <div class="card-header">
                <!-- <?php if (in_array('createBranch', $user_permission) || $isAdmin) { ?> -->
                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addCuttingModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Cutting Order</button>
                <a href="<?= base_url("Utilities/CuttingOrderConfiguration") ?>" class="btn btn-info btn-flat"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Cutting Order Configuration</a>
                <!-- <?php } ?> -->
            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Order Name</th>
                                    <th>Created Date</th>
                                    <th>Created User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>


    </section>
    <div class="modal fade" id="addCuttingModal" tabindex="-1" role="dialog" aria-labelledby="addCuttingModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCuttingModal">Create Cutting Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <div class="container"> -->
                <form role="form" method="post" action="<?= base_url('Utilities/SaveCuttingOrder') ?>" id="createCuttingOrder">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="invoice_no">Cutting Order Name</label>
                                <input type="text" class="form-control" id="cutting_order_name" name="cutting_order_name" autocomplete="off" required value="">
                            </div>
                        </div>
                        <table class="table arcadia-table" id="itemTable">
                            <thead>
                                <tr>
                                    <th hidden>Item ID</th>
                                    <th style="text-align:center;">Item Name</th>
                                    <th style="width: 100px; text-align:center;">Qty</th>
                                    <th style="width: 100px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr class="first-tr">
                                    <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                    <td class="static">
                                    <!-- <input type="text" class="form-control" name="txtItem"> -->
                                    <select class="form-control select2" style="width: 100%;" id="cmbItem" name="cmbItem">
                                        <option value=" 0" disabled selected hidden>Select Item</option>
                                        <?php foreach ($item_data as $k => $v) { ?>
                                            <option value="<?= $v['intItemID'] ?>"><?= $v['vcItemName'] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                    <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtQty" id="txtQty" style="text-align:right;"></td>
                                    <td class="static"><button type="button" class="button green center-items" id="btnAddToGrid"><i class="fas fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="modal-footer">
                            <!-- <div class="row" style="border-top:1px solid #dee2e6;"> -->

                            <div class="col-md-6">
                                <p style="color: #c2c7d0; " id="itemCount">Item Count : 0</p>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">

                                <button type="button" id="btnSubmit" class="btn btn-lg btn-info btn-flat float-right"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Submit</button>
                            </div>

                            <!-- /.col -->
                            <!-- </div> -->
                        </div>
                    </div>
                </form>
                <!-- </div> -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>





<script src="<?php echo base_url('resources/pageJS/cuttingOrder.js') ?>"></script>