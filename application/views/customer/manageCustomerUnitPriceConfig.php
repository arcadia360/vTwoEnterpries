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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manage Customer Unit Price</h1>
                </div>
                <!-- <div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Item</a></li>
						<li class="breadcrumb-item active">Manage Item</li>
					</ol>
				</div> -->
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <?php if (in_array('createItem', $user_permission) || $isAdmin) { ?>
                        <button type="button" class="btn btn-info btn-flat" id="btnAddConfig" data-toggle="modal" data-target="#addConfigModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Config</button>
                    <?php } ?>

                    <div class="form-group">
                        <label>Customer :</label>
                        <select class="form-control select2" style="width: 100%;" id="cmbCustomerFilter" name="cmbCustomerFilter">
                            <option value="0" selected hidden>All Customers</option>
                            <?php foreach ($customer_data as $k => $v) { ?>
                                    <option value="<?= $v['intCustomerID'] ?>"><?= $v['vcCustomerName'] ?></option>
                                <?php } ?>
                        </select>
                    </div>

                </div>
            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Item Name</th>
                                    <th>Unit Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </section>
    <!-- Main content end -->

    <!-- Modal -->
    <div class="modal fade" id="addConfigModal" tabindex="-1" role="dialog" aria-labelledby="addConfigModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addConfigModal">Add Price Config</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <div class="container"> -->
                <form role="form" method="post" action="<?= base_url('Customer/SaveCustomerPriceConfig') ?>" id="createCustomerPriceConfig">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                            <label for="Customer">Customer Name</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbCustomer" name="cmbCustomer">
                                        <option value="0" disabled selected hidden>Select Item</option>
                                        <?php foreach ($customer_data as $k => $v) { ?>
                                            <option value="<?= $v['intCustomerID'] ?>"><?= $v['vcCustomerName'] ?></option>
                                        <?php } ?>
                                    </select>
                            </div>
                        </div>
                        <table class="table arcadia-table" id="itemTable">
                            <thead>
                                <tr>
                                    <th hidden>Item ID</th>
                                    <th style="text-align:center;">Item Name</th>
                                    <th style="width: 100px; text-align:center;">Unit Price</th>
                                    <th style="width: 100px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr class="first-tr">
                                    <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                    <td class="static">
                                    <select class="form-control select2" style="width: 100%;" id="cmbItem" name="cmbItem">
                                    </select>
                                </td>
                                    <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtUnitPrice" id="txtUnitPrice" style="text-align:right;"></td>
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
<!-- /.content-wrapper -->
</div>

<!-- edit Supplier modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModal">Edit Customer Unit Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('Customer/UpdateCustomerPriceConfig') ?>" method="post" id="updateCustomerPriceConfigForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="supplier_name">Customer </label>
                        <input type="text" class="form-control" id="edit_customer" name="edit_customer" style="cursor: not-allowed; color:#000000;" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="address">Item</label>
                        <input type="text" class="form-control" id="edit_item" name="edit_item" style="cursor: not-allowed; color:#000000;" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Unit Price</label>
                        <input type="number" class="form-control only-decimal" id="edit_unit_price" name="edit_unit_price" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Unit Price" autocomplete="off">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Customer Price Config</button>
                </div>

            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script src="<?php echo base_url('resources/pageJS/manageCustomerUnitPriceConfig.js') ?>"></script>