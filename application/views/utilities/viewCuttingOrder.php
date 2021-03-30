<style>
    .first-tr {
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
<div class="content-wrapper arcadia-main-container">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manage Cutting Order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Cutting Order</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url("Utilities/cuttingOrder"); ?>">View Cutting Order</a></li>
                        <li class="breadcrumb-item active">Edit Cutting Order</li>

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <form role="form" class="add-form" method="post" action="<?= base_url('Utilities/EditCuttingOrderDetails/' . $cuttingorder_header_data['intCuttingOrderHeaderID']) ?>" id="editCuttingOrder">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="invoice_no">Order Name</label>
                            <input type="text" class="form-control" id="edit_order_name" name="edit_order_name" autocomplete="off" style="cursor: not-allowed; color:#000000;" required value="<?= $cuttingorder_header_data['vcOrderName']; ?>" disabled>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="invoice_no">Created Date</label>
                            <input type="text" class="form-control" id="created_date" name="created_date" style="cursor: not-allowed; color:#000000;" autocomplete="off" required value="<?= $cuttingorder_header_data['dtCreatedDate']; ?>" disabled>
                        </div>
                    </div>

                    <table class="table arcadia-table" id="itemTable">
                        <thead>
                            <tr>
                                <th hidden>Item ID</th>
                                <th style="text-align:center;">Item Description</th>
                                <th style="width: 100px; text-align:center;">Qty</th>
                                <!-- <th style="width: 100px; text-align: center;">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
<!-- 
                            <tr class="first-tr">
                                <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                <td class="static"><input type="text" class="form-control  add-item" name="txtOrderDescription" id="txtOrderDescription" placeholder="Example : 12 in X 18 in"></td>
                                <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtQty" id="txtQty" style="text-align:right;"></td>
                                <td class="static"><button type="button" class="button green center-items" id="btnAddToGrid"><i class="fas fa-plus"></i></button></td>
                            </tr> -->
                            <?php
                            $row = 0;
                            foreach ($cuttingorder_detail_data as $k => $v) { ?>
                                <tr>
                                    <td hidden><input type="text" class="form-control itemID disable-typing" name="intCuttingOrderDetailID[]" id="intCuttingOrderDetailID_<?= $row ?>" value="<?= $v['intCuttingOrderDetailID'] ?>" readonly></td>
                                    <td><input type="text" class="form-control itemName disable-typing" name="vcItemName[]" id="vcItemName_<?= $row ?>" value="<?= $v['vcItemName'] ?>" readonly></td>
                                    <td><input type="text" class="form-control disable-typing" style="text-align:right;" name="qty[]" id="qty_<?= $row ?>" value="<?= $v['decQty'] ?>" readonly></td>
                                    <!-- <td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td> -->
                                </tr>
                            <?php
                                $row++;
                            } ?>
                        </tbody>
                    </table>

             
                </form>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="<?php echo base_url('resources/pageJS/cuttingOrder.js') ?>"></script>