<?php
// header('Access-Control-Allow-Origin: *');
// header("Access-Control-Allow-Methods: GET, OPTIONS");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title><?= $page_title ?></title>

	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url('resources/tempcss/select2.min.css') ?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url('resources/tempcss/adminlte.min.css') ?>">
	<!-- daterange picker -->
	<link rel="stylesheet" href="<?php echo base_url('resources/tempcss/daterangepicker.css') ?>">
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

	<!-- Common -->
	<link rel="stylesheet" href="<?php echo base_url('resources/tempcss/common.css') ?>">
	<!-- Toster message -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<!-- jquery.dataTables -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">


	<!-- <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.flash.min.js"></script> -->

	<!-- Tempusdominus Bbootstrap 4 -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/tempcss/tempusdominus-bootstrap-4.min.css') ?>">


	<script src="<?php echo base_url('resources/tempjs/jquery.min.js') ?>"></script>

	<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script> -->

	<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


	<script>
		$.fn.DataTable.ext.pager.numbers_length = 5;
		var base_url = "<?php echo base_url(); ?>";
	</script>



</head>

<body onload="Preloader()" class="hold-transition sidebar-mini sidebar-collapse" style="font-size: 0.8rem !important;">
	<!-- Preloader Start-->
	<div id="Preloader">
		<div class="dl">
			<div class="dl__container">
				<div class="dl__corner--top"></div>
				<div class="dl__corner--bottom"></div>
			</div>
			<div class="dl__square"></div>
		</div>
	</div>
	<!-- Preloader End -->
	<!-- Site wrapper -->
	<div class="wrapper">