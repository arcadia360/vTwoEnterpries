<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Manage Sales Rep
        </h1>

    </section>
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
                <?php if (in_array('createSupplier', $user_permission) || $isAdmin) { ?>
                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addSalesRepModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Sales Rep</button>
                <?php } ?>
            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sales Rep Name</th>
                                    <th>Contact No</th>
                                    <th>Address</th>
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

    <div class="modal fade" id="addSalesRepModal" tabindex="-1" role="dialog" aria-labelledby="addSalesRepModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSalesRepModal">Add Sales Rep</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('utilities/saveSalesRep') ?>" method="post" id="createSalesRepForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="supplier_name">Sales Rep Name</label>
                            <input type="text" class="form-control" id="salesRep_name" name="salesRep_name" placeholder="Enter Sales Rep Name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="contact_no">Contact No</label>
                            <input type="number" class="form-control" id="contact_no" name="contact_no" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Sales Rep</button>
                    </div>

                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->
<!-- edit Supplier modal -->
<div class="modal fade" id="editSalesRepModal" tabindex="-1" role="dialog" aria-labelledby="editSalesRepModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSalesRepModal">Edit Sales Rep</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('utilities/updateSalesRep') ?>" method="post" id="updateSalesRepForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="supplier_name">Supplier Name</label>
                        <input type="text" class="form-control" id="edit_salesRep_name" name="edit_salesRep_name" placeholder="Enter Sales Rep Name" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact No</label>
                        <input type="number" class="form-control" id="edit_contact_no" name="edit_contact_no" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="edit_address" name="edit_address" placeholder="Enter Address" autocomplete="off">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Sales Rep</button>
                </div>

            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->

    <!-- remove brand modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeSalesRepModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeSalesRepModal">Delete Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('utilities/removeSalesRep') ?>" method="post" id="removeSalesRepForm">
                    <div class="modal-body">
                        <p>Do you really want to remove?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Delete Sales Rep</button>
                    </div>
                </form>


            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<script src="<?php echo base_url('resources/pageJS/manageSalesRep.js') ?>"></script>