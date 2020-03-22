<?php
		error_reporting(0);
		session_start();
		if ($_SESSION["username1"] != "admin"){
			header("location:"."./");
		}
			include("includes/connect.php");
			include("includes/data.php");
		?>
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="author" content="@housamz">

				<meta name="description" content="Mass Admin Panel">
				<title>Stock Predictor Admin Panel</title>

				<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-h21C2fcDk/eFsW9sC9h0dhokq5pDinLNklTKoxIZRUn3+hvmgQSffLLQ4G4l2eEr" crossorigin="anonymous">
				
				<!-- Custom CSS -->
				<link rel="stylesheet" href="includes/style.css">
				<link href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
			</head>

			<body>

			<div class="wrapper">
				<!-- Sidebar Holder -->
				<nav id="sidebar" class="bg-primary">
					<div class="sidebar-header">
						<h3>
							Stock Predictor Admin Panel<br><br>
							<i id="sidebarCollapse" class="glyphicon glyphicon-circle-arrow-left"></i>
						</h3>
						<strong>
						<i class="logo">
                			<svg class="s" width="50" height="50" viewBox="0 0 24 24"	><style type="text/css">.s{fill:aliceblue;}</style><path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z"/></svg>
            			<br></i><br>
							<i id="sidebarExtend" class="glyphicon glyphicon-circle-arrow-right"></i>
						</strong>
						
					</div><!-- /sidebar-header -->

					<!-- start sidebar -->
					<ul class="list-unstyled components">
						<li>
							<a href="home.php" aria-expanded="false">
								<i class="glyphicon glyphicon-home"></i>
								Home
							</a>
						</li>
			<li><a href="login.php"> <i class="glyphicon glyphicon-sort-by-order-alt"></i>Login <span class="pull-right"><?=counting("login", "id")?></span></a></li>
<li><a href="prediction.php"> <i class="glyphicon glyphicon-save"></i>Prediction <span class="pull-right"><?=counting("prediction", "id")?></span></a></li>
<li><a href="s_c_details.php"> <i class="glyphicon glyphicon-calendar"></i>Stock Company details <span class="pull-right"><?=counting("s_c_details", "id")?></span></a></li>
<li><a href="stock_details.php"> <i class="glyphicon glyphicon-tower"></i>Stock Details <span class="pull-right"><?=counting("stock_details", "id")?></span></a></li>
<li><a href="user.php"> <i class="glyphicon glyphicon-lamp"></i>User <span class="pull-right"><?=counting("user", "id")?></span></a></li>
<li><a href="update.php"><i class="glyphicon glyphicon-lamp"></i> Update</a></li>
<li><a href="logout.php"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
				</ul>
			</nav><!-- /end sidebar -->

			<!-- Page Content Holder -->
			<div id="content">