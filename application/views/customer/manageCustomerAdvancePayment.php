<!-- Content Wrapper. Contains page content -->
<style>

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
                    <h1>Manage Customer Advance Payment</h1>
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
                        <button type="button" class="btn btn-info btn-flat" id="btnAddConfig" data-toggle="modal" data-target="#addConfigModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Payment</button>
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
                                    <th>Advance Date</th>
                                    <th>Advance Amount</th>
                                    <th>Remark</th>
                                    <th>Invoice No</th>
                                    <th>Entered Date</th>
                                    <th>Entered User</th>
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
                    <h5 class="modal-title" id="addConfigModal">Add Advance Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <div class="container"> -->
                <form role="form" method="post" action="<?= base_url('Customer/SaveCustomerAdvancePayment') ?>" id="createCustomerAdvancePayment">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="Customer">Customer Name</label>
                                <select class="form-control select2" style="width: 100%;" id="cmbCustomer" name="cmbCustomer">
                                    <option value="0" disabled selected hidden>Select Item</option>
                                    <?php foreach ($advance_customer_data as $k => $v) { ?>
                                        <option value="<?= $v['intCustomerID'] ?>"><?= $v['vcCustomerName'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                            <label>Advance Date</label>
                            <div class="input-group date" id="dtReceivedDate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="advanceDate" name="advanceDate" style="pointer-events: none !important;" />
                                <div class="input-group-append" data-target="#dtReceivedDate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>

                        </div>
                        </div>
                        <div class="form-group">
                            <label for="credit_limit">Advance Amount</label>
                            <input type="number" class="form-control only-decimal" id="advance_amount" name="advance_amount" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Advance Amount" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="credit_limit">Remark</label>
                            <input type="text" class="form-control" id="remark" name="remark" placeholder="Enter Remark" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" id="btnSubmit" class="btn btn-lg btn-info btn-flat float-right"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Submit</button>

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

<script src="<?php echo base_url('resources/pageJS/manageCustomerAdvancePayment.js') ?>"></script>