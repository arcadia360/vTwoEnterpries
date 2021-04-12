<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Main Category</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Utilities</a></li>
                        <li class="breadcrumb-item active">Main Category</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
                <?php if (in_array('createMainCategory', $user_permission) || $isAdmin) { ?>
                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addMainCategoryModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Main Category</button>

                <?php } ?>
            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Main Category</th>
                                    <th style="width: 300px;">Action</th>
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

    <div class="modal fade" id="addMainCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addMainCategoryModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMainCategoryModal">Add Main Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal -->
                <form role="form" action="<?php echo base_url('Utilities/createMainCategory') ?>" method="post" id="createMainCategory">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="Unit_name">Main Category Name</label>
                            <input type="text" class="form-control" id="MainCat_name" name="MainCat_name" placeholder="Enter Main Category Name" autofocus autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Main Category</button>
                    </div>

            </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div>
<!-- edit MeasureUni modal -->
<!-- edit Branch modal -->
<div class="modal fade" id="editMainCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editMainCategoryModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMainCategoryModal">Edit Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('Utilities/updateMainCategory') ?>" method="post" id="updateMainCategoryForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="branch_name">Main Category Name</label>
                        <input type="text" class="form-control" id="edit_mainCat_name" name="edit_mainCat_name" placeholder="Enter Main Category Name" autocomplete="off">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Main Category</button>
                </div>

            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->


<!-- remove brand modal -->
<!-- <?php if (in_array('deleteMeasureUnit', $user_permission) || $isAdmin) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" id="removeMeasureUnithModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeMeasureUnithModal">Delete Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('Utilities/removeMeasureUnit') ?>" method="post" id="removeMeasureUnitForm">
                    <div class="modal-body">
                        <p>Do you really want to remove?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Delete Unit</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
<?php } ?> -->

<script src="<?php echo base_url('resources/pageJS/manageMainCategory.js') ?>"></script>