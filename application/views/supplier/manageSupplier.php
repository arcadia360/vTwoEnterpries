<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Manage Supplier
        </h1>

    </section>
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
                <?php if (in_array('createSupplier', $user_permission) || $isAdmin) { ?>
                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addSupplierModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Supplier</button>
                <?php } ?>
            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Address</th>
                                    <th>Contact No</th>
                                    <th>Credit Limit</th>
                                    <th>Available Credit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.row -->
    </section>

    <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierModal">Add Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('supplier/create') ?>" method="post" id="createSupplierForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="supplier_name">Supplier Name</label>
                            <input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Enter Supplier Name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="contact_no">Contact No</label>
                            <input type="number" class="form-control" id="contact_no" name="contact_no" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="credit_limit">Credit Limit</label>
                            <input type="number" class="form-control only-decimal" id="credit_limit" name="credit_limit" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Supplier</button>
                    </div>

                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->
<!-- edit Supplier modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-labelledby="editSupplierModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSupplierModal">Edit Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('supplier/update') ?>" method="post" id="updateSupplierForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="supplier_name">Supplier Name</label>
                        <input type="text" class="form-control" id="edit_supplier_name" name="edit_supplier_name" placeholder="Enter Supplier Name" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="edit_address" name="edit_address" placeholder="Enter Address" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact No</label>
                        <input type="number" class="form-control" id="edit_contact_no" name="edit_contact_no" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="credit_limit">Credit Limit</label>
                        <input type="number" class="form-control only-decimal" id="edit_credit_limit" name="edit_credit_limit" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="edit_rv" name="edit_rv" autocomplete="off">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Supplier</button>
                </div>

            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->
<?php if (in_array('deleteSupplier', $user_permission) || $isAdmin) { ?>
    <!-- remove brand modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeSupplierModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeSupplierModal">Delete Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('supplier/remove') ?>" method="post" id="removeSupplierForm">
                    <div class="modal-body">
                        <p>Do you really want to remove?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Delete Supplier</button>
                    </div>
                </form>


            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php } ?>

<script src="<?php echo base_url('resources/pageJS/supplier.js') ?>"></script>