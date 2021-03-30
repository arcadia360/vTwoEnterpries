		<!-- Small boxes (Stat box) -->
		<div class="row">
		    <!-- Pending GRN Approvals -->
		    <?php
            if ($_SESSION['Is_main_branch'] == 1) { // GRN Approval Facility Can Main Branch Only
                if ($isAdmin || in_array('viewGRN', $user_permission) || in_array('approveGRN', $user_permission) || in_array('createGRN', $user_permission) || in_array('editGRN', $user_permission) || in_array('deleteGRN', $user_permission)) {
            ?>
		            <div class="col-lg-3 col-6">
		                <div class="small-box bg-info">
		                    <div class="inner">
		                        <h3><?= $approval_pending_grn_count ?></h3>
		                        <p>Pending GRN Approvals</p>
		                    </div>
		                    <div class="icon">
		                        <i class="far fa-clock"></i>
		                    </div>
		                    <a href="<?= base_url("GRN/ViewGRN")?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
		                </div>
		            </div>
		    <?php
                }
            }
            ?>

		    <!-- ./col -->
		    <div class="col-lg-3 col-6">
		        <!-- small box -->
		        <div class="small-box bg-success">
		            <div class="inner">
		                <h3>0</h3>
		                <p>Pending Requistion Approvals</p>
		            </div>
		            <div class="icon">
		                <i class="fas fa-hourglass-half"></i>
		            </div>
		            <a href="#" class="small-box-footer"><i class="fab fa-creative-commons-nd"></i></a>
		        </div>
		    </div>
		    <!-- ./col -->
		    <div class="col-lg-3 col-6">
		        <!-- small box -->
		        <div class="small-box bg-warning">
		            <div class="inner">
		                <h3>0</h3>
		                <p>Pending Dispatch Collections</p>
		            </div>
		            <div class="icon">
		                <i class="fas fa-people-carry"></i>
		            </div>
		            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
		        </div>
		    </div>
		    <!-- ./col -->
		    <div class="col-lg-3 col-6">
		        <!-- small box -->
		        <div class="small-box bg-danger">
		            <div class="inner">
		                <h3>0</h3>
		                <p>Re-Order Item Warnings</p>
		            </div>
		            <div class="icon">
		                <i class="fas fa-battery-quarter"></i>
		            </div>
		            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
		        </div>
		    </div>
		    <!-- ./col -->
		</div>






		<!--  -->
		<!-- Dashboar partials -->
		<!--  -->

		</div>
		</div>
		<!-- /.card -->

		</section>
		<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->