<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> -->

<style>
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

    th.tableHeader {
        background-color: #263238;
        color: #FFFFFF;
    }

    th.tableFooter {
        background-color: #6B6F70;
        color: #FFFFFF;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Manage Item</h1>
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
						<button type="button" class="btn btn-info btn-flat" id="btnAddItem" data-toggle="modal" data-target="#addItemModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Item</button>
					<?php } ?>

					<!-- <div class="form-group">
						<label>Item Type :</label>
						<select class="form-control select2" style="width: 100%;" id="cmbItemType" name="cmbItemType">
							<option value="0" selected hidden>All Types</option>
							<?php foreach ($itemTypeAll as $k => $v) { ?>
                                    <option value="<?= $v['intItemTypeID'] ?>"><?= $v['vcItemTypeName'] ?></option>
                                <?php } ?>
						</select>
					</div> -->

				</div>
			</div>
			<div class="card-body">

				<div class="box">
					<div class="box-body">
						<table id="manageTable" class="table table-bordered table-striped text-center">
							<thead>
								<tr>
									<th>Item Name</th>
									<th>Stock Qty</th>
									<th>Stock GRN Value Total</th>
									<th>Unit Price</th>
									<th>Measure Unit</th>
									<th>Main Category</th>
									<th>Sub Category</th>
									<th>Re-Order Level</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th class="tableFooter"></th>
									<th class="tableFooter"></th>
									<th class="tableFooter" style="text-align: right;"></th>
									<th class="tableFooter"></th>
									<th class="tableFooter"></th>
									<th class="tableFooter"></th>
									<th class="tableFooter"></th>
									<th class="tableFooter"></th>
									<th class="tableFooter"></th>
								</tr>
							</tfoot>
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
	<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Add Item</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<form role="form" action="<?php echo base_url('Item/create') ?>" method="post" id="createitemForm">
					<div class="modal-body">
						<div class="form-group">
							<label for="txtItemName">Item Name</label>
							<input type="text" class="form-control" id="Item_name" name="Item_name" placeholder="Enter Item Name" autofocus>
						</div>
						<div class="form-group">
							<label>Measure Unit</label>
							<select class="form-control select2" style="width: 100%;" id="measure_unit" name="measure_unit">
								<option value="0" disabled selected hidden>Select Measure Unit</option>
								<?php foreach ($measureUnit as $row) { ?>
									<option value="<?= $row->intMeasureUnitID ?>"><?= $row->vcMeasureUnit ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label>Main Categories</label>
							<select class="form-control select2" style="width: 100%;" id="main_cat" name="main_cat">
								<option value="0" disabled selected hidden>Select Main Categorie</option>
								<?php foreach ($main_cat as $row) { ?>
									<<option value="<?= $row->intMainCategoryID ?>"><?= $row->vcMainCategory ?></option>
									<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label>Sub Categories</label>
							<select class="form-control select2" style="width: 100%;" id="sub_cat" name="sub_cat">
							</select>
						</div>

						<div class="form-group">
							<label for="txtItemName">Unit Price</label>
							<input type="text" class="form-control only-decimal" id="unit_price" name="unit_price" placeholder="Enter Unit Price">
						</div>

						<div class="form-group">
							<label for="txtItemName">Item Re-Order Level</label>
							<input type="number" class="form-control" id="re_order" name="re_order" placeholder="Enter Re-Order Level" min=1>
						</div>
					</div>
					<div class="modal-footer">

						<button type="submit" id="btnSaveItem" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Item</button>
					</div>


				</form>
			</div>
		</div>
	</div>

</div>
<!-- /.content-wrapper -->

<!-- edit Item modal -->
<?php if (in_array('editItem', $user_permission) || $isAdmin) { ?>
	<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModal" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editItemModal">Edit Item</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form role="form" action="<?php echo base_url('Item/update') ?>" method="post" id="updateItemForm">
					<div class="modal-body">
						<div class="form-group">
							<label for="Item_name">Item Name</label>
							<input type="text" class="form-control" id="edit_item_name" name="edit_item_name" placeholder="Enter Item Name" autocomplete="off">
						</div>
						<div class="form-group">
							<label>Measure Unit</label>
							<select class="form-control select2" style="width: 100%;" id="edit_measure_unit" name="edit_measure_unit">
								<!-- <option value="0" disabled selected hidden>Select Measure Unit</option> -->
								<?php foreach ($measureUnit as $row) { ?>
									<option value="<?= $row->intMeasureUnitID ?>"><?= $row->vcMeasureUnit ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label>Main Categories</label>
							<select class="form-control select2" style="width: 100%;" id="edit_main_cat" name="edit_main_cat">
								<!-- <option value="0" disabled selected hidden>Select Main Categories</option> -->
								<?php foreach ($main_cat as $row) { ?>
									<<option value="<?= $row->intMainCategoryID ?>"><?= $row->vcMainCategory ?></option>
									<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label>Sub Categories</label>
							<select class="form-control select2" style="width: 100%;" id="edit_sub_cat" name="edit_sub_cat">
								<option value='0'>Select Sub Categorie</option>
							</select>
						</div>

						<div class="form-group">
							<label for="txtItemName">Unit Price</label>
							<input type="text" class="form-control only-decimal" id="edit_unit_price" name="edit_unit_price" placeholder="Enter Unit Price">
						</div>

						<div class="form-group">
							<label for="txtItemName">Item Re-Order Level</label>
							<input type="number" class="form-control" id="edit_re_order" name="edit_re_order" placeholder="Enter Re-Order Level" min=1>
						</div>

						<div class="form-group">
							<input type="hidden" class="form-control" id="edit_rv" name="edit_rv" autocomplete="off">
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" id="btnUpdateItem" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Item</button>
					</div>

				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<?php } ?>

</div>
<!-- /.content-wrapper -->

<?php if (in_array('deleteItem', $user_permission) || $isAdmin) { ?>
	<div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="removeSupplierModal">Delete Supplier</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form role="form" action="<?php echo base_url('Item/remove') ?>" method="post" id="removeItemForm">
					<div class="modal-body">
						<p>Do you really want to remove?</p>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Delete Item</button>
					</div>
				</form>


			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php } ?>

<!-- remove Item modal -->


<script src="<?php echo base_url('resources/pageJS/item.js') ?>"></script>