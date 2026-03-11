<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {
	// 1. XỬ LÝ GỬI GÓP Ý
	if (isset($_POST['submit1'])) {
		$fname = $_POST['fname'];
		$email = $_POST['email'];
		$mobileno = $_POST['mobileno'];
		$subject = $_POST['subject'];
		$description = $_POST['description'];

		$sql = "INSERT INTO gopy(hoten, id_email, sdt, chude, noidung) VALUES(:fname, :email, :mobileno, :subject, :description)";
		$query = $dbh->prepare($sql);
		$query->bindParam(':fname', $fname, PDO::PARAM_STR);
		$query->bindParam(':email', $email, PDO::PARAM_STR);
		$query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
		$query->bindParam(':subject', $subject, PDO::PARAM_STR);
		$query->bindParam(':description', $description, PDO::PARAM_STR);

		if ($query->execute()) {
			$msg = "Gửi góp ý thành công. Cảm ơn phản hồi của bạn!";
		} else {
			$error = "Đã xảy ra lỗi. Vui lòng thử lại";
		}
	}

	// 2. LẤY THÔNG TIN NGƯỜI DÙNG
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
		<title>Góp ý</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="icon" href="images/favicon.png" type="image/x-icon">

		<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link href="css/font-awesome.css" rel="stylesheet">
		<link href="css/style1.css" rel="stylesheet" type="text/css" />
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

			/* Style cho sidebar active */
			.dashboard-nav .nav-tabs li.active a,
			.dashboard-nav .nav-tabs li.active a:hover {
				color: white !important;
				font-weight: bold;
				background: #faa61a !important;
			}

			.dashboard-nav .nav-tabs li a {
				color: #333;
			}

			/* Style cho form đồng bộ */
			.form-control[readonly] {
				background-color: #f9f9f9;
			}
		</style>
	</head>

	<body>
		<?php include('includes/header.php'); ?>

		<section class="page-cover dashboard">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<h1 class="page-title">Góp ý & Phản hồi</h1>
						<ul class="breadcrumb">
							<li><a href="index.php">Trang chủ</a></li>
							<li class="active">Góp ý</li>
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
								<p>Đóng góp ý kiến giúp chúng tôi cải thiện chất lượng dịch vụ</p>
							</div>

							<div class="dashboard-wrapper">
								<div class="row">
									<div class="col-xs-12 col-sm-2 col-md-2 dashboard-nav">
										<ul class="nav nav-tabs nav-stacked text-center">
											<li><a href="issuetickets.php"><span><i class="fa fa-question-circle"></i></span>Trợ giúp</a></li>
											<li><a href="profile.php"><span><i class="fa fa-user"></i></span>Hồ sơ</a></li>
											<li><a href="booking.php"><span><i class="fa fa-briefcase"></i></span>Đặt tour</a></li>
											<li><a href="password.php"><span><i class="fa fa-lock"></i></span>Mật khẩu</a></li>
											<li class="active"><a href="enquiry.php"><span><i class="fa fa-pencil-square-o"></i></span>Góp ý</a></li>
											<li><a href="index.php"><span><i class="fa fa-home"></i></span>Trang chủ</a></li>
										</ul>
									</div>

									<div class="col-xs-12 col-sm-10 col-md-10 dashboard-content user-profile">
										<h2 class="dash-content-title">Gửi phản hồi</h2>
										<?php if ($error) { ?><div class="errorWrap"><strong>Lỗi</strong>: <?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>Hoàn thành</strong>: <?php echo htmlentities($msg); ?> </div><?php } ?>

										<div class="panel panel-default">
											<div class="panel-heading">
												<h4>Form thông tin</h4>
											</div>
											<div class="panel-body">
												<form name="enquiry" method="post">
													<div class="row">
														<div class="col-sm-6">
															<div class="form-group">
																<label>Họ tên</label>
																<input type="text" name="fname" class="form-control" value="<?php echo htmlentities($result_user->hoten); ?>" required readonly>
															</div>
															<div class="form-group">
																<label>Email</label>
																<input type="email" name="email" class="form-control" value="<?php echo htmlentities($result_user->id_email); ?>" required readonly>
															</div>
															<div class="form-group">
																<label>Số điện thoại</label>
																<input type="text" name="mobileno" class="form-control" value="<?php echo htmlentities($result_user->sdt_nd); ?>" maxlength="10" required>
															</div>
														</div>

														<div class="col-sm-6">
															<div class="form-group">
																<label>Chủ đề</label>
																<input type="text" name="subject" class="form-control" placeholder="Nhập chủ đề góp ý" required>
															</div>
															<div class="form-group">
																<label>Nội dung</label>
																<textarea name="description" class="form-control" rows="6" placeholder="Nhập nội dung chi tiết..." required></textarea>
															</div>
															<button type="submit" name="submit1" class="btn btn-orange btn-block" style="margin-top: 10px;">Gửi góp ý</button>
														</div>
													</div>
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