<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Customers List</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Customer</a></li>
						<li class="breadcrumb-item active">Customers List</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="card">
			<div class="card-header">
				<?php if (in_array('createCustomer', $user_permission) || $isAdmin) { ?>
					<button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addCustomerModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Customer</button>
				<?php } ?>
			</div>
			<div class="card-body">

				<div class="box">
					<div class="box-body">
						<table id="manageTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Customer Name</th>
									<th>Building Number</th>
									<th>Street</th>
									<th>City</th>
									<th>Contact No 1</th>
									<th>Contact No 2</th>
									<th>Credit Limit</th>
									<th>Available Limit</th>
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

	<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModal" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addCustomerModal">Add Supplier</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form role="form" action="<?php echo base_url('customer/create') ?>" method="post" id="createCustomerForm">
					<div class="modal-body">
						<div class="form-group">
							<label for="customer_name">Customer Name</label>
							<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer Name" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="address">Building Number</label>
							<input type="text" class="form-control" id="building_number" name="building_number" placeholder="Enter Building Number" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="address">Street</label>
							<input type="text" class="form-control" id="street" name="street" placeholder="Enter Street" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="address">City</label>
							<input type="text" class="form-control" id="city" name="city" placeholder="Enter City" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="contact_no_1">Contact No 1</label>
							<input type="number" class="form-control" id="contact_no_1" name="contact_no_1" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No 1" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="contact_no_2">Contact No 2</label>
							<input type="number" class="form-control" id="contact_no_2" name="contact_no_2" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No 2" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="txtCredit">Credit Limit</label>
							<input type="text" class="form-control only-decimal" id="credit_limit" name="credit_limit" placeholder="Enter Credit Limit">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Customer</button>
					</div>

				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->
<!-- edit Customer modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editCustomerModal">Edit Customer</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form role="form" action="<?php echo base_url('customer/update') ?>" method="post" id="updateCustomerForm">
				<div class="modal-body">
					<div class="form-group">
						<label for="customer_name">Customer Name</label>
						<input type="text" class="form-control" id="edit_customer_name" name="edit_customer_name" placeholder="Enter Customer Name" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="address">Building Number</label>
						<input type="text" class="form-control" id="edit_building_number" name="edit_building_number" placeholder="Enter Building Number" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="address">Street</label>
						<input type="text" class="form-control" id="edit_street" name="edit_street" placeholder="Enter Street" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="address">City</label>
						<input type="text" class="form-control" id="edit_city" name="edit_city" placeholder="Enter City" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="contact_no_1">Contact No 1</label>
						<input type="number" class="form-control" id="edit_contact_no_1" name="edit_contact_no_1" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No 1" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="contact_no_2">Contact No 2</label>
						<input type="number" class="form-control" id="edit_contact_no_2" name="edit_contact_no_2" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Contact No 2" autocomplete="off">
					</div>
					<div class="row">
						<div class="form-group">
							<label for="txtCredit">Credit Limit</label>
							<input type="number" class="form-control only-decimal" id="edit_credit_limit" name="edit_credit_limit" placeholder="Enter Credit Limit">
						</div>
						&nbsp;&nbsp;&nbsp;
						<div class="form-group">
							<label for="txtCredit">Customer's Credit</label>
							<input type="text" class="form-control only-decimal" id="available_limit" name="available_limit" disabled>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="submit" id="btnUpDateCustomer" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Customer</button>
				</div>

			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->

<!-- remove brand modal -->
<?php if (in_array('deleteCustomer', $user_permission) || $isAdmin) { ?>
	<div class="modal fade" tabindex="-1" role="dialog" id="removeCustomerModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="removeCustomerModal">Delete Customer</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form role="form" action="<?php echo base_url('customer/remove') ?>" method="post" id="removeCustomerForm">
					<div class="modal-body">
						<p>Do you really want to remove?</p>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Delete Customer</button>
					</div>
				</form>


			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php } ?>

<script src="<?php echo base_url('resources/pageJS/customer.js') ?>"></script>