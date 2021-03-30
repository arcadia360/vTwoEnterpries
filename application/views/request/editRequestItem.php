r<style>
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
                    <h1>Edit Request Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Request</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url("request/ViewRequestItem"); ?>">View Request</a></li>
                        <li class="breadcrumb-item active">Edit Request Item</li>

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
                <form role="form" class="add-form" method="post" action="<?= base_url('request/EditRequestDetails/' . $request_header_data['intRequestHeaderID']) ?>" id="editRequest">
        
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="invoice_no">Request No</label>
                            <input type="text" class="form-control" id="request_no" name="request_no" style="cursor: not-allowed; color:#000000;"  autocomplete="off" required value="<?= $request_header_data['vcRequestNo']; ?>" disabled>
                        </div>
         
                        <div class="form-group col-md-6">
                            <label for="invoice_no">Request Date</label>
                            <input type="text" class="form-control" id="request_date" name="request_date" style="cursor: not-allowed; color:#000000;" autocomplete="off" required value="<?= $request_header_data['dtCreatedDate']; ?>" disabled>
                        </div>
                    </div>

                    <table class="table arcadia-table" id="itemTable">
                        <thead>
                            <tr>
                                <!-- <th hidden>GRN Detail ID</th>
                                <th hidden>Item ID</th> -->
                                <th style="text-align:center;">Item</th>
                                <th style="width: 100px; text-align:center;">Unit</th>
                                <th style="width: 100px; text-align:center;">Stock Qty</th>
                                <th style="width: 100px; text-align:center;">Request Qty</th>
                                <th style="width: 100px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr class="first-tr">
                                <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                <td class="static">
                                    <select class="form-control select2" style="width: 100%;" id="cmbItem" name="cmbItem" onchange="getRequestFinishedByItemID();">
                                        <option value=" 0" disabled selected hidden>Select Item</option>
                                        <?php foreach ($item_data as $k => $v) { ?>
                                            <option value="<?= $v['intItemID'] ?>"><?= $v['vcItemName'] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td class="static"><input type="text" class="form-control add-item" name="txtMeasureUnit" id="txtMeasureUnit" style="text-align:center;" disabled></td>
                                <td class="static"><input type="text" class="form-control only-decimal" name="txtStockQty" id="txtStockQty" style="text-align:right;" disabled ></td>
                                <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtQty" id="txtQty" style="text-align:right;"></td>
                                <td class="static"><button type="button" class="button green center-items" id="btnAddToGrid"><i class="fas fa-plus"></i></button></td>
                            </tr>
                            <?php
                            $row = 0;
                            foreach ($request_detail_data as $k => $v) { ?>
                                <tr>
                                    <td hidden><input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_<?= $row ?>" value="<?= $v['intItemID'] ?>" readonly></td>
                                    <td><input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_<?= $row ?>" value="<?= $v['vcItemName'] ?>" readonly></td>
                                    <td><input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_<?= $row ?>" value="<?= $v['vcMeasureUnit'] ?>" readonly></td>
                                    <td><input type="text" class="form-control disable-typing" style="text-align:center;" name="stockInHand[]" id="stockInHand_<?= $row ?>" value="<?= $v['decStockInHand'] ?>" readonly></td>
                                    <td><input type="text" class="form-control disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_<?= $row ?>" value="<?= $v['decQty'] ?>" readonly></td>
                                    <td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td>
                                </tr>
                                <?php
                                $row++;
                            } ?>
                        </tbody>
                    </table>

                    <div class="row" style="border-top:1px solid #dee2e6;">
                        <div class="col-6">
                            <p style="color: #c2c7d0; position:absolute; bottom:0;" id="itemCount">Item Count : 0</p>
                        </div>
                        <!-- /.col -->
                        <div class="col-6" style="padding-right:100px;">
                            <button type="button" id="btnSubmit" class="btn btn-lg btn-info btn-flat float-right"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Submit</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="<?php echo base_url('resources/pageJS/editRequest.js') ?>"></script>