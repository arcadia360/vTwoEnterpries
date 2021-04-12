<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sub Category</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Utilities</a></li>
                        <li class="breadcrumb-item active">Sub Category</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Sub content -->
    <section class="content">
        <!-- Default box -->
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
                <?php if (in_array('createSubCategory', $user_permission) || $isAdmin) { ?>
                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addSubCategoryModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Sub Category</button>

                <?php } ?>
            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Main Category</th>
                                    <th>Sub Category</th>
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

    <div class="modal fade" id="addSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addSubCategoryModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubCategoryModal">Add Sub Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal -->
                <form role="form" action="<?php echo base_url('Utilities/createSubCategory') ?>" method="post" id="createSubCategory">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Main Category</label>
                            <select class="form-control select2" style="width: 100%;" id="main_cat" name="main_cat">
                                <option value="0" disabled selected hidden>Select Main Category</option>
                                <?php foreach ($main_cat_data as $row) { ?>
                                    <option value="<?= $row->intMainCategoryID ?>"><?= $row->vcMainCategory ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Unit_name">Sub Category Name</label>
                            <input type="text" class="form-control" id="subCat_name" name="subCat_name" placeholder="Enter Sub Category Name" autofocus autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSaveSubCat" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Sub Category</button>
                    </div>

            </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div>
<!-- edit MeasureUni modal -->
<!-- edit Branch modal -->
<div class="modal fade" id="editSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editSubCategoryModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubCategoryModal">Edit Sub Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('Utilities/updateSubCategory') ?>" method="post" id="updateSubCategoryForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Main Category</label>
                        <select class="form-control select2" style="width: 100%;" id="edit_main_cat" name="edit_main_cat">
                            <option value="0" disabled selected hidden>Select Main Category</option>
                            <?php foreach ($main_cat_data as $row) { ?>
                                <option value="<?= $row->intMainCategoryID ?>"><?= $row->vcMainCategory ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="branch_name">Sub Category Name</label>
                        <input type="text" class="form-control" id="edit_SubCat_name" name="edit_SubCat_name" placeholder="Enter Sub Category Name" autocomplete="off">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit"  id="btnUpdateSubCat" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Sub Category</button>
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

<script src="<?php echo base_url('resources/pageJS/manageSubCategory.js') ?>"></script>