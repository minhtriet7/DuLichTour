<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {
	// 1. Xử lý cập nhật thông tin
	if (isset($_POST['submit6'])) {
		$name = $_POST['name'];
		$ngaysinh = $_POST['ngaysinh'];
		$mobileno = $_POST['mobileno'];
		$diachi = $_POST['diachi'];
		$email = $_SESSION['login'];

		$sql = "UPDATE nguoidung SET hoten=:name, ngaysinh=:ngaysinh, sdt_nd=:mobileno, diachi=:diachi WHERE id_email=:email";
		$query = $dbh->prepare($sql);
		$query->bindParam(':name', $name, PDO::PARAM_STR);
		$query->bindParam(':ngaysinh', $ngaysinh, PDO::PARAM_STR);
		$query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
		$query->bindParam(':diachi', $diachi, PDO::PARAM_STR);
		$query->bindParam(':email', $email, PDO::PARAM_STR);
		$query->execute();
		$msg = "Cập nhật hồ sơ thành công";
	}

	// 2. Lấy thông tin người dùng (Sau khi update để hiển thị thông tin mới nhất)
	$useremail = $_SESSION['login'];
	$sql_user = "SELECT * from nguoidung where id_email=:useremail";
	$query_user = $dbh->prepare($sql_user);
	$query_user->bindParam(':useremail', $useremail, PDO::PARAM_STR);
	$query_user->execute();
	$result_user = $query_user->fetch(PDO::FETCH_OBJ);
?>
	<!DOCTYPE HTML>
	<html lang="en">

	<head>
		<title>Hồ sơ cá nhân</title>
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
							<li class="active">Hồ sơ</li>
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
								<p>Dưới đây là thông tin chi tiết của bạn</p>
							</div>

							<div class="dashboard-wrapper">
								<div class="row">
									<div class="col-xs-12 col-sm-2 col-md-2 dashboard-nav">
										<ul class="nav nav-tabs nav-stacked text-center">
											<li><a href="issuetickets.php"><span><i class="fa fa-question-circle"></i></span>Trợ giúp</a></li>
											<li class="active"><a href="profile.php"><span><i class="fa fa-user"></i></span>Hồ sơ</a></li>
											<li><a href="booking.php"><span><i class="fa fa-briefcase"></i></span>Đặt tour</a></li>
											<li><a href="password.php"><span><i class="fa fa-lock"></i></span>Mật khẩu</a></li>
											<li><a href="enquiry.php"><span><i class="fa fa-pencil-square-o"></i></span>Góp ý</a></li>
											<li><a href="index.php"><span><i class="fa fa-home"></i></span>Trang chủ</a></li>
										</ul>
									</div>

									<div class="col-xs-12 col-sm-10 col-md-10 dashboard-content user-profile">
										<h2 class="dash-content-title">Hồ sơ</h2>
										<?php if ($error) { ?><div class="errorWrap"><strong>Lỗi</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>Hoàn thành</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>

										<div class="panel panel-default">
											<div class="panel-heading">
												<h4>Thông tin</h4>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-sm-5 col-md-4 user-img">
														<img src="images/add.jpg" class="img-responsive" alt="user-img" />
													</div>
													<div class="col-sm-7 col-md-8 user-detail">
														<ul class="list-unstyled">
															<li><span>Email:</span> <?php echo htmlentities($result_user->id_email); ?></li>
															<li><span>Họ tên:</span> <?php echo htmlentities($result_user->hoten); ?></li>
															<li><span>Ngày sinh:</span> <?php echo htmlentities($result_user->ngaysinh); ?></li>
															<li><span>Điện thoại:</span> <?php echo htmlentities($result_user->sdt_nd); ?></li>
															<li><span>Địa chỉ:</span> <?php echo htmlentities($result_user->diachi); ?></li>
														</ul>
														<button class="btn btn-orange" data-toggle="modal" data-target="#edit-profile">Chỉnh sửa</button>
													</div>
												</div>
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

		<div id="edit-profile" class="modal custom-modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<h3 class="modal-title">Cập nhật thông tin</h3>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" onclick="$('#edit-profile').modal('hide');" style="position: absolute; right: 15px; top: 15px; opacity: 1; color: #333;">&times;</button>
					</div>
					<div class="modal-body">
						<form name="profile" method="post">
							<div class="form-group">
								<label>Email (Không thể thay đổi)</label>
								<input type="email" class="form-control" name="email" value="<?php echo htmlentities($result_user->id_email); ?>" readonly>
							</div>
							<div class="form-group">
								<label>Họ tên</label>
								<input name="name" value="<?php echo htmlentities($result_user->hoten); ?>" type="text" class="form-control" required />
							</div>
							<div class="form-group">
								<label>Ngày sinh</label>
								<input name="ngaysinh" value="<?php echo htmlentities($result_user->ngaysinh); ?>" type="date" class="form-control" />
							</div>
							<div class="form-group">
								<label>Số điện thoại</label>
								<input name="mobileno" type="text" value="<?php echo htmlentities($result_user->sdt_nd); ?>" class="form-control" maxlength="10" />
							</div>
							<div class="form-group">
								<label>Địa chỉ</label>
								<input name="diachi" type="text" value="<?php echo htmlentities($result_user->diachi); ?>" class="form-control" />
							</div>
							<button type="submit" name="submit6" class="btn btn-orange btn-block">Lưu thay đổi</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php include('includes/footer.php'); ?>
		<?php include('includes/signup.php'); ?>
		<?php include('includes/signin.php'); ?>
		<?php include('includes/write-us.php'); ?>
	</body>

	</html>
<?php } ?>