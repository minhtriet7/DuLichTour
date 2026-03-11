<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {
	// 1. Lấy thông tin người dùng để hiển thị tên và menu
	$useremail = $_SESSION['login'];
	$sql_user = "SELECT * from nguoidung where id_email=:useremail";
	$query_user = $dbh->prepare($sql_user);
	$query_user->bindParam(':useremail', $useremail, PDO::PARAM_STR);
	$query_user->execute();
	$result_user = $query_user->fetch(PDO::FETCH_OBJ);

	// 2. Xử lý đổi mật khẩu
	if (isset($_POST['submit5'])) {
		$password = md5($_POST['password']);
		$newpassword = md5($_POST['newpassword']);
		$confirmpassword = md5($_POST['confirmpassword']);

		// Validate 1: Mật khẩu mới và xác nhận phải trùng nhau
		if ($newpassword != $confirmpassword) {
			$error = "Mật khẩu xác nhận không khớp!";
		}
		// Validate 2: Mật khẩu mới không được trùng mật khẩu cũ
		else if ($password == $newpassword) {
			$error = "Mật khẩu mới không được trùng với mật khẩu cũ!";
		} else {
			// Kiểm tra mật khẩu cũ trong Database
			$sql = "SELECT matkhau FROM nguoidung WHERE id_email=:email and matkhau=:password";
			$query = $dbh->prepare($sql);
			$query->bindParam(':email', $useremail, PDO::PARAM_STR);
			$query->bindParam(':password', $password, PDO::PARAM_STR);
			$query->execute();

			if ($query->rowCount() > 0) {
				// Mật khẩu cũ đúng -> Cập nhật mật khẩu mới
				$con = "update nguoidung set matkhau=:newpassword where id_email=:email";
				$chngpwd1 = $dbh->prepare($con);
				$chngpwd1->bindParam(':email', $useremail, PDO::PARAM_STR);
				$chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
				$chngpwd1->execute();
				$msg = "Đổi mật khẩu thành công!";
			} else {
				$error = "Mật khẩu cũ không chính xác!";
			}
		}
	}
?>
	<!DOCTYPE HTML>
	<html lang="en">

	<head>
		<title>Đổi mật khẩu</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="icon" href="images/favicon.png" type="image/x-icon">

		<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link href="css/font-awesome.css" rel="stylesheet">
		<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">

		<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
		<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
		<link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
		<link href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" rel="stylesheet">

		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/wow.min.js"></script>
		<script>
			new WOW().init();
		</script>

		<script type="text/javascript">
			function valid() {
				if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
					alert("Mật khẩu mới và Nhập lại mật khẩu không khớp!");
					document.chngpwd.confirmpassword.focus();
					return false;
				}
				return true;
			}
		</script>

		<style type="text/css">
			.errorWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #dd3d36;
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			.succWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #5cb85c;
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			.dashboard-nav .nav-tabs li.active a,
			.dashboard-nav .nav-tabs li.active a:hover {
				color: white !important;
				font-weight: bold;
				background: #faa61a !important;
			}

			.dashboard-nav .nav-tabs li a {
				color: #333;
			}
		</style>
	</head>

	<body>
		<?php include('includes/header.php'); ?>

		<section class="page-cover dashboard">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<h1 class="page-title">Tài khoản của tôi</h1>
						<ul class="breadcrumb">
							<li><a href="index.php">Trang chủ</a></li>
							<li class="active">Mật khẩu</li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<section class="innerpage-wrapper">
			<div id="dashboard" class="innerpage-section-padding">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

							<div class="dashboard-heading">
								<h2>Thông tin<span> người dùng</span></h2>
								<p>Xin chào <?php echo htmlentities($result_user->hoten); ?></p>
								<p>Dưới đây là phần thay đổi mật khẩu</p>
							</div>

							<div class="dashboard-wrapper">
								<div class="row">
									<div class="col-xs-12 col-sm-2 col-md-2 dashboard-nav">
										<ul class="nav nav-tabs nav-stacked text-center">
											<li><a href="issuetickets.php"><span><i class="fa fa-question-circle"></i></span>Trợ giúp</a></li>
											<li><a href="profile.php"><span><i class="fa fa-user"></i></span>Hồ sơ</a></li>
											<li><a href="booking.php"><span><i class="fa fa-briefcase"></i></span>Đặt tour</a></li>
											<li class="active"><a href="password.php"><span><i class="fa fa-lock"></i></span>Mật khẩu</a></li>
											<li><a href="enquiry.php"><span><i class="fa fa-pencil-square-o"></i></span>Góp ý</a></li>
											<li><a href="index.php"><span><i class="fa fa-home"></i></span>Trang chủ</a></li>
										</ul>
									</div>

									<div class="col-xs-12 col-sm-10 col-md-10 dashboard-content user-profile">
										<h2 class="dash-content-title">Mật khẩu</h2>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4>Thay đổi mật khẩu</h4>
											</div>
											<div class="panel-body">
												<?php if ($error) { ?>
													<div class="errorWrap"><strong>Lỗi: </strong> <?php echo htmlentities($error); ?></div>
												<?php } else if ($msg) { ?>
													<div class="succWrap"><strong>Thành công</strong>: <?php echo htmlentities($msg); ?></div>
												<?php } ?>

												<form name="chngpwd" method="post" onSubmit="return valid();">
													<div class="form-group">
														<label>Mật khẩu cũ</label>
														<input type="password" name="password" class="form-control" placeholder="Mật khẩu cũ" required>
													</div>

													<div class="form-group">
														<label>Mật khẩu mới</label>
														<input type="password" class="form-control" name="newpassword" placeholder="Mật khẩu mới" required>
													</div>

													<div class="form-group">
														<label>Nhập lại mật khẩu mới</label>
														<input type="password" class="form-control" name="confirmpassword" placeholder="Nhập lại mật khẩu mới" required>
													</div>

													<button type="submit" name="submit5" class="btn btn-primary btn-lg" style="background-color: #faa61a; border:none;">Thay đổi</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</section>

		<?php include('includes/footer.php'); ?>
		<?php include('includes/signup.php'); ?>
		<?php include('includes/signin.php'); ?>
		<?php include('includes/write-us.php'); ?>

	</body>

	</html>
<?php } ?>