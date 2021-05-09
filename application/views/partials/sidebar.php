<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="#" class="brand-link">
        <div class="user-panel pb-3 d-flex">
            <!-- <div class="image">
						<img src="<?php echo base_url('resources/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="img-circle elevation-2" style="opacity: .8">
					</div> -->
            <div class="info">
                <span class="brand-text font-weight-light d-block">VTwo Enterpries</span>
                <span class="brand-text font-weight-light d-block" style="font-size: 0.8em;">Business Management</span>
                <span class="brand-text font-weight-light d-block" style="font-size: 0.8em;">System</span>
            </div>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">



                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <div class="user-panel pb-3 mb-3 d-flex">
                            <div class="image">
                                <img src="<?php echo base_url('resources/img/User-Default.png') ?>" class="img-circle elevation-2" alt="User Image">
                            </div>
                            <div class="info">
                                <span href="#" class="d-block"><?= $_SESSION['full_name'] ?>
                                    <span class="d-block small"><?= $_SESSION['group_name'] ?></span>
                                </span>
                            </div>
                        </div>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('Customer/addCustomer') ?>" class="nav-link">
                                <i class="fas fa-user-edit"></i>
                                <p>&nbsp;&nbsp;Change Account Details</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?php if ($this->uri->segment(1) == "dashboard") {
                                                                                echo 'active';
                                                                            } ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>&nbsp;&nbsp;&nbsp;Dashboard</p>
                    </a>
                </li>

                <li class="nav-item has-treeview <?php if ($this->uri->segment(1) == "User" || $this->uri->segment(2) == "UserGroup" || $this->uri->segment(2) == "MeasureUnit" || $this->uri->segment(2) =="MainCategory" || $this->uri->segment(2) =="SubCategory" || $this->uri->segment(2) == "manageSalesRep") {
                                                        echo 'menu-open';
                                                    } ?>">
                    <a href="#" class="nav-link">
                        <i class="fas fa-tools"></i>
                        <p>&nbsp;&nbsp;&nbsp;Utilities
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('User/index') ?>" class="nav-link <?php if ($this->uri->segment(1) == "User") {
                                                                                                echo 'active';
                                                                                            } ?>">
                                <i class="fas fa-user"></i>
                                <p>&nbsp;&nbsp;&nbsp;User Accounts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('Utilities/UserGroup') ?>" class="nav-link <?php if ($this->uri->segment(2) == "UserGroup") {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <i class="fas fa-users"></i>
                                <p>&nbsp;&nbsp;User Groups</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('Utilities/MeasureUnit') ?>" class="nav-link <?php if ($this->uri->segment(2) == "MeasureUnit") {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <i class="fas fa-balance-scale-right"></i>
                                <p>&nbsp;&nbsp;Measure Unit</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('Utilities/MainCategory') ?>" class="nav-link <?php if ($this->uri->segment(2) == "MainCategory") {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <i class="fas fa-balance-scale-right"></i>
                                <p>&nbsp;&nbsp;Main Category</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="<?= base_url('Utilities/SubCategory') ?>" class="nav-link <?php if ($this->uri->segment(2) == "SubCategory") {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <i class="fas fa-balance-scale-right"></i>
                                <p>&nbsp;&nbsp;Sub Category</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo base_url('Utilities/manageSalesRep') ?>" class="nav-link <?php if ($this->uri->segment(2) == "manageSalesRep") {
                                                                                                                echo 'active';
                                                                                                            } ?>">
                                <i class="fa fa-truck" aria-hidden="true"></i>
                                <p>&nbsp;&nbsp;Sales Rep</p>
                            </a>
                        </li>

                      </ul>
                </li>
                <li class="nav-item has-treeview <?php if ($this->uri->segment(2) == "CustomersList" || $this->uri->segment(2) == "manageCustomerUnitPriceConfig") {
                                                        echo 'menu-open';
                                                    } ?>">
                    <a href="#" class="nav-link">
                        <i class="fas fa-tools"></i>
                        <p>&nbsp;&nbsp;&nbsp;Customer
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('Customer/CustomersList') ?>" class="nav-link <?php if ($this->uri->segment(2) == "CustomersList") {
                                                                                                            echo 'active';
                                                                                                        } ?>">
                                <i class="fas fa-user"></i>
                                <p>&nbsp;&nbsp;&nbsp;Customers List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('Customer/manageCustomerUnitPriceConfig') ?>" class="nav-link <?php if ($this->uri->segment(2) == "manageCustomerUnitPriceConfig") {
                                                                                                                            echo 'active';
                                                                                                                        } ?>">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <p>&nbsp;&nbsp;Customer Price Config</p>
                            </a>
                        </li>
                    </ul>

                </li>

                <!-- <li class="nav-item">
                    <a href="<?php echo base_url('Utilities/manageSalesRep') ?>" class="nav-link <?php if ($this->uri->segment(1) == "SalesRep") {
                                                                                                    } ?>">
                        <i class="fa fa-truck" aria-hidden="true"></i>
                        <p>&nbsp;&nbsp;Sales Rep</p>
                    </a>
                </li> -->



                <li class="nav-item">
                    <a href="<?php echo base_url('Supplier/index') ?>" class="nav-link <?php if ($this->uri->segment(1) == "Supplier") {
                                                                                            echo 'active';
                                                                                        } ?>">
                        <i class="fa fa-truck" aria-hidden="true"></i>
                        <p>&nbsp;&nbsp;Supplier</p>
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a href="<?php echo base_url('Branch/index') ?>" class="nav-link <?php if ($this->uri->segment(1) == "Branch") {
                                                                                            echo 'active';
                                                                                        } ?>">
                        <i class="fas fa-store"></i>
                        <p>&nbsp;&nbsp;Branch</p>
                    </a>
                </li> -->

                <li class="nav-item">
                    <a href="<?php echo base_url('Item/index') ?>" class="nav-link <?php if ($this->uri->segment(1) == "Item") {
                                                                                        echo 'active';
                                                                                    } ?>">
                        <i class="fa fa-industry" aria-hidden="true"></i>
                        <p>&nbsp;&nbsp;&nbsp;Item</p>
                    </a>
                </li>

                <li class="nav-item has-treeview <?php if ($this->uri->segment(2) == "CreateReceipt" || $this->uri->segment(2) == "manageCustomerAdvancePayment") {
                                                        echo 'menu-open';
                                                    } ?>">
                    <a href="#" class="nav-link">
                        <i class="fas fa-tools"></i>
                        <p>&nbsp;&nbsp;&nbsp;Payments
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('Receipt/CreateReceipt') ?>" class="nav-link <?php if ($this->uri->segment(2) == "CreateReceipt") {
                                                                                                            echo 'active';
                                                                                                        } ?>">
                                <i class="fas fa-user"></i>
                                <p>&nbsp;&nbsp;&nbsp;Customer Payment Receipt</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('Customer/manageCustomerAdvancePayment') ?>" class="nav-link <?php if ($this->uri->segment(2) == "manageCustomerAdvancePayment") {
                                                                                                                            echo 'active';
                                                                                                                        } ?>">
                                <i class="fas fa-user"></i>
                                <p>&nbsp;&nbsp;&nbsp;Customer Advance Payment</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('Supplier/SupplierCreditSettlement') ?>" class="nav-link <?php if ($this->uri->segment(2) == "supplierCreditSettlement") {
                                                                                                                        echo 'active';
                                                                                                                    } ?>">
                                <i class="fas fa-user"></i>
                                <p>&nbsp;&nbsp;&nbsp;Supplier Credit Settlement</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('Receipt/ViewReceipt') ?>" class="nav-link <?php if ($this->uri->segment(2) == "viewReceipt") {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                <i class="fas fa-user"></i>
                                <p>&nbsp;&nbsp;&nbsp;View Customer Credit Settlement</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('Supplier/ViewSupplierCreditSettlement') ?>" class="nav-link <?php if ($this->uri->segment(2) == "viewSupplierCreditSettlement") {
                                                                                                                            echo 'active';
                                                                                                                        } ?>">
                                <i class="fas fa-user"></i>
                                <p>&nbsp;&nbsp;&nbsp;View Supplier Credit Settlement</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('Supplier/CancelSupplierCreditSettlement') ?>" class="nav-link <?php if ($this->uri->segment(2) == "cancelSupplierCreditSettlement") {
                                                                                                                            echo 'active';
                                                                                                                        } ?>">
                                <i class="fas fa-user"></i>
                                <p>&nbsp;&nbsp;&nbsp;Cancel Supplier Credit Settlement</p>
                            </a>
                        </li>
                    </ul>

                </li>


                <!-- <li class="nav-item has-treeview <?php if ($this->uri->segment(2) == "RequestItem" || $this->uri->segment(2) == "ViewRequest") {
                                                            echo 'menu-open';
                                                        } ?>">
                    <a href="#" class="nav-link">
                        <i class="fas fa-layer-group"></i>
                        <p>&nbsp;&nbsp;&nbsp;Request
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php
                        if ($_SESSION['Is_main_branch'] != 1) { ?>
                            <li class="nav-item">
                                <a href="<?php echo base_url('Request/RequestItem') ?>" class="nav-link <?php if ($this->uri->segment(2) == "RequestItem") {
                                                                                                            echo 'active';
                                                                                                        } ?>">
                                    <i class="fas fa-cart-plus"></i>
                                    <p>&nbsp;&nbsp;Request Item</p>
                                </a>
                            </li>
                        <?php }
                        ?>

                        <li class="nav-item">
                            <a href="<?php echo base_url('Request/ViewRequest') ?>" class="nav-link <?php if ($this->uri->segment(2) == "ViewRequest") {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                <i class="fas fa-search"></i>
                                <p>&nbsp;&nbsp;View Request</p>
                            </a>
                        </li>
                    </ul>
                </li> -->

                <li class="nav-item has-treeview <?php if ($this->uri->segment(2) == "CreateGRN" || $this->uri->segment(2) == "ViewGRN" || $this->uri->segment(2) == "EditGRN" || $this->uri->segment(2) == "ViewGRNDetails" || $this->uri->segment(2) == "ApproveOrRejectGRN") {
                                                        echo 'menu-open';
                                                    } ?>">
                    <a href="#" class="nav-link">
                        <i class="fas fa-layer-group"></i>
                        <p>&nbsp;&nbsp;&nbsp;Stock
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('GRN/CreateGRN') ?>" class="nav-link <?php if ($this->uri->segment(2) == "CreateGRN") {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <i class="fas fa-cart-plus"></i>
                                <p>&nbsp;&nbsp;Create GRN</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('GRN/ViewGRN') ?>" class="nav-link <?php if ($this->uri->segment(2) == "ViewGRN" || $this->uri->segment(2) == "ViewGRNDetails" || $this->uri->segment(2) == "EditGRN" || $this->uri->segment(2) == "ApproveOrRejectGRN") {
                                                                                                echo 'active';
                                                                                            } ?>">
                                <i class="fas fa-search"></i>
                                <p>&nbsp;&nbsp;View GRN</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php
                if ($_SESSION['Is_main_branch'] == 1) { ?>
                    <!-- <li class="nav-item has-treeview <?php if ($this->uri->segment(2) == "CreateDispatch" || $this->uri->segment(2) == "ViewDispatch" || $this->uri->segment(2) == "CollectDispatchedItems") {
                                                                echo 'menu-open';
                                                            } ?>">
                        <a href="#" class="nav-link">
                            <i class="fas fa-layer-group"></i>
                            <p>&nbsp;&nbsp;&nbsp;Dispatch
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo base_url('Dispatch/CreateDispatch') ?>" class="nav-link <?php if ($this->uri->segment(2) == "CreateDispatch") {
                                                                                                                echo 'active';
                                                                                                            } ?>">
                                    <i class="fas fa-cart-plus"></i>
                                    <p>&nbsp;&nbsp;Create Dispatch</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('Dispatch/ViewDispatch') ?>" class="nav-link <?php if ($this->uri->segment(2) == "ViewDispatch") {
                                                                                                                echo 'active';
                                                                                                            } ?>">
                                    <i class="fas fa-search"></i>
                                    <p>&nbsp;&nbsp;View Dispatch</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('Dispatch/CollectDispatchedItems') ?>" class="nav-link <?php if ($this->uri->segment(2) == "CollectDispatchedItems") {
                                                                                                                        echo 'active';
                                                                                                                    } ?>">
                                    <i class="fas fa-dolly"></i>
                                    <p>&nbsp;&nbsp;Collect Dispatched Items</p>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                <?php
                }
                ?>

                <li class="nav-item has-treeview <?php if ($this->uri->segment(2) == "CreateIssue" || $this->uri->segment(2) == "ViewIssue") {
                                                        echo 'menu-open';
                                                    } ?>">
                    <a href="#" class="nav-link">
                        <i class="fas fa-layer-group"></i>
                        <p>&nbsp;&nbsp;&nbsp;Issue
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('Issue/CreateIssue') ?>" class="nav-link <?php if ($this->uri->segment(2) == "CreateIssue") {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                <i class="fas fa-cart-plus"></i>
                                <p>&nbsp;&nbsp;Issue Item</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('Issue/ViewIssue') ?>" class="nav-link <?php if ($this->uri->segment(2) == "ViewIssue") {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <i class="fas fa-search"></i>
                                <p>&nbsp;&nbsp;View Issue</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('Issue/IssueReturn') ?>" class="nav-link <?php if ($this->uri->segment(2) == "IssueReturn") {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                <i class="fas fa-search"></i>
                                <p>&nbsp;&nbsp;Issue Return</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('auth/logout') ?>" class="nav-link">
                        <i class="fas fa-power-off"></i>
                        <p>&nbsp;&nbsp;&nbsp;Logout</p>
                    </a>
                </li>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>