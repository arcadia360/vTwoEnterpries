<!-- <style type="text/css">
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    } 
</style> -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container ">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Dispatch</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dispatch</a></li>
                        <li class="breadcrumb-item active">View Dispatch</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

    </section>
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status :</label>
                            <select class="custom-select" id="cmbStatus">
                                <option value="0">All</option>
                                <option value="1">To Be Received</option>
                                <option value="2">Received</option>
                                <option value="2">Canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Date range -->
                        <div class="form-group">
                            <label>Date Range :</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right" name="daterange">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <!-- <div>
                            Toggle column: <a class="toggle-vis" data-column="0">ID</a> - <a class="toggle-vis" data-column="1">GRN No</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
                        </div> -->
                        <table id="manageTable" class="table table-bordered table-striped">
                            <!-- style="display:block !important;" -->
                            <thead>
                                <tr>
                                    <th>Dispatch No</th>
                                    <th>Dispatched Date</th>
                                    <th>Created Date</th>
                                    <th>Remark</th>
                                    <th>Created User</th>
                                    <th>Cancelled Date</th>
                                    <th>Cancelled User</th>
                                    <th>Receive Completed Date</th>
                                    <th>Receive Completed User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <!-- <tbody>
                                <?php foreach ($grn_data as $k => $v) { ?>
                                    <?php if ($v['ApprovedUser'] == NULL) { ?>
                                        <tr style="background-color: #FFC108;">
                                        <?php } else { ?>
                                        <tr>
                                        <?php }
                                        ?>

                                        <td hidden><?= $v['intGRNHeaderID'] ?></td>
                                        <td class="text-center"><?= $v['vcGRNNo'] ?></td>
                                        <td><?= $v['vcInvoiceNo'] ?></td>
                                        <td><?= $v['vcSupplierName'] ?></td>
                                        <td class="text-right"><?= number_format((float)$v['decSubTotal'], 2, '.', '') ?></td>
                                        <td class="text-right"><?= number_format((float)$v['decDiscount'], 2, '.', '') ?></td>
                                        <td class="text-right"><?= number_format((float)$v['decGrandTotal'], 2, '.', '') ?></td>
                                        <td class="text-center"><?= $v['dtReceivedDate'] ?></td>
                                        <td class="text-center"><?= $v['dtCreatedDate'] ?></td>
                                        <td class="text-center"><?= $v['CreatedUser'] ?></td>
                                        <td class="text-center"><?= ($v['dtApprovedOn'] == NULL) ? "N/A" : $v['dtApprovedOn'] ?></td>
                                        <td class="text-center"><?= ($v['ApprovedUser'] == NULL) ? "N/A" : $v['ApprovedUser'] ?></td>
                                        <td style="padding: 0;">
                                            <?php if ($v['ApprovedUser'] == NULL) { ?>
                                                <?php if (in_array('editGRN', $user_permission) || $isAdmin) { ?>
                                                    <a class="button btn btn-default" href="<?= base_url("GRN/EditGRN/" . $v['intGRNHeaderID']); ?>"><i class="fas fa-edit"></i></a>
                                                <?php }
                                                if (in_array('deleteGRN', $user_permission) || $isAdmin) { ?>
                                                    <a class="button btn btn-default" onclick="removeGRN(<?= $v['intGRNHeaderID'] ?>)"><i class="fa fa-trash"></i></a>
                                                <?php }
                                            }
                                            if (in_array('approveGRN', $user_permission) || $isAdmin) { ?>
                                                <button type="button" class="btn btn-default"><i class="far fa-thumbs-up"></i></button>
                                            <?php }
                                            ?>
                                        </td>
                                        </tr>

                                    <?php } ?>
                            </tbody> -->
                        </table>
                        <hr>
                        Color tags : <span class="badge badge-pill badge-warning">To Be Received</span> <span class="badge badge-pill badge-light" style="border: 1px #000000 solid;">Fully Received</span> <span class="badge badge-pill badge-danger">Canceled</span>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.row -->
    </section>


</div>
<!-- /.content-wrapper -->

<!-- /.content-wrapper -->


<!-- <script>
    $(function() {
        date = new Date();
        $('input[name="daterange"]').daterangepicker({
            opens: 'center',
            startDate: new Date(date.getFullYear(), date.getMonth(), 1),
            endDate: new Date()
        }, function(start, end, label) {
            alert("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });
</script> -->


<script src="<?php echo base_url('resources/pageJS/viewDispatch.js') ?>"></script>