<style>
    /* body {
        background: #f6f6f6;
    } */

    /* #table {
        position: relative;
        overflow: hidden;
        margin-top: 50px;
    } */

    /* table {
        background: black;
        box-shadow: 0 10px 30px rgba(225, 225, 225, 0.5);
    } */



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

    /* tr>td {
        position: relative;
    } */

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
<div class="content-wrapper arcadia-main-container ">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manage Cutting Order Configuration</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Cutting Order</a></li>
                        <li class="breadcrumb-item active">Create Cutting Order Configuration</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

    </section>
    <section class="content">
        <!-- Default box -->
        <form role="form" class="add-form" method="post" action="<?= base_url('Utilities/SaveCuttingOrderConfiguration') ?>" id="createCuttingOrderConfiguration">
            <div class="card">
                <div class="card-header">

                </div>
                <section class="content">
                    <!-- Small boxes (Stat box) -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Item Name :</label>
                                        <select class="form-control select2" style="width: 100%;" id="cmbItem" name="cmbItem">
                                            <option value="0" disabled selected hidden>Select Item</option>
                                            <?php foreach ($item_data as $k => $v) { ?>
                                                <option value="<?= $v['intItemID'] ?>"><?= $v['vcItemName'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table arcadia-table" id="itemTable">
                                <thead>
                                    <tr>
                                        <th hidden>Item ID</th>
                                        <th style="text-align:center;">Cutting Order Name</th>
                                        <th style="width: 100px; text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr class="first-tr">
                                        <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                        <td class="static">
                                            <select class="form-control select2" style="width: 100%;" id="cmbCuttingOrder" name="cmbCuttingOrder">

                                            </select>
                                        </td>
                                        <td class="static"><button type="button" class="button green center-items" id="btnAddToGrid"><i class="fas fa-plus"></i></button></td>
                                    </tr>
                    
                                </tbody>
                            </table>

                            <div class="row" style="border-top:1px solid #dee2e6;">
                                <div class="col-6">
                                    <p style="color: #c2c7d0; position:absolute; bottom:0;" id="itemCount">Item Count : 0</p>
                                </div>
                                <!-- /.col -->
                                <div class="col-6">

                                    <!-- <button type="button" id="btnSubmit" class="btn btn-lg btn-info btn-flat float-right"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Submit</button> -->
                                </div>
                                <!-- /.col -->
                            </div>

                        </div>
                    </div>
                    <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.row -->
        </form>
    </section>


</div>

</div>

</section>
</div>




<script src="<?php echo base_url('resources/pageJS/createCuttingOrderConfiguration.js') ?>"></script>