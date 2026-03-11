<?php
session_start();
// Bật báo lỗi để dễ debug nếu có sự cố (sau này xong có thể tắt)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {
	// --- 1. XỬ LÝ GỬI YÊU CẦU ---
	if (isset($_POST['submit_ticket'])) {
		$issue = $_POST['issue'];           // Vấn đề (Chủ đề)
		$description = $_POST['description']; // Nội dung mô tả
		$email = $_SESSION['login'];        // Email người gửi

		// SỬA LẠI CÂU LỆNH SQL CHO ĐÚNG VỚI BẢNG 'trogiup'
		// Cột: emailgui, chude, noidung, ngaygui
		$sql = "INSERT INTO trogiup(emailgui, chude, noidung, ngaygui) VALUES(:email, :issue, :description, NOW())";

		$query = $dbh->prepare($sql);
		$query->bindParam(':email', $email, PDO::PARAM_STR);
		$query->bindParam(':issue', $issue, PDO::PARAM_STR);
		$query->bindParam(':description', $description, PDO::PARAM_STR);

		if ($query->execute()) {
			$msg = "Gửi yêu cầu thành công!";
			// Redirect để tránh gửi lại form khi F5 (Optional)
			// header("Location: issuetickets.php"); 
			// exit();
		} else {
			$error = "Có lỗi xảy ra, vui lòng thử lại.";
		}
	}

	// 2. Lấy thông tin người dùng
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
		<title>Trợ giúp</title>
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

			.dashboard-nav .nav-tabs li.active a,
			.dashboard-nav .nav-tabs li.active a:hover {
				color: white !important;
				font-weight: bold;
				background: #faa61a !important;
			}

			.dashboard-nav .nav-tabs li a {
				color: #333;
			}

			.form-support {
				background: #f9f9f9;
				padding: 20px;
				border: 1px solid #eee;
				border-radius: 5px;
				margin-bottom: 20px;
			}

			.table-responsive {
				margin-top: 10px;
			}

			.form-heading {
				color: #faa61a;
				font-weight: bold;
				border-bottom: 2px solid #faa61a;
				padding-bottom: 10px;
				margin-bottom: 20px;
			}
		</style>
	</head>

	<body>
		<?php include('includes/header.php'); ?>

		<section class="page-cover dashboard">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<h1 class="page-title">Trung tâm trợ giúp</h1>
						<ul class="breadcrumb">
							<li><a href="index.php">Trang chủ</a></li>
							<li class="active">Trợ giúp</li>
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
								<p>Gửi yêu cầu hỗ trợ và xem lịch sử phản hồi</p>
							</div>

							<div class="dashboard-wrapper">
								<div class="row">
									<div class="col-xs-12 col-sm-2 col-md-2 dashboard-nav">
										<ul class="nav nav-tabs nav-stacked text-center">
											<li class="active"><a href="issuetickets.php"><span><i class="fa fa-question-circle"></i></span>Trợ giúp</a></li>
											<li><a href="profile.php"><span><i class="fa fa-user"></i></span>Hồ sơ</a></li>
											<li><a href="booking.php"><span><i class="fa fa-briefcase"></i></span>Đặt tour</a></li>
											<li><a href="password.php"><span><i class="fa fa-lock"></i></span>Mật khẩu</a></li>
											<li><a href="enquiry.php"><span><i class="fa fa-pencil-square-o"></i></span>Góp ý</a></li>
											<li><a href="cards.php"><span><i class="fa fa-credit-card"></i></span>Thẻ</a></li>
										</ul>
									</div>

									<div class="col-xs-12 col-sm-10 col-md-10 dashboard-content user-profile">

										<?php if (isset($error)) { ?><div class="errorWrap"><strong>Lỗi</strong>: <?php echo htmlentities($error); ?> </div><?php } else if (isset($msg)) { ?><div class="succWrap"><strong>Thành công</strong>: <?php echo htmlentities($msg); ?> </div><?php } ?>

										<div class="row">
											<div class="col-md-5">
												<div class="form-support">
													<h3 class="form-heading"><i class="fa fa-pencil-square-o"></i> Gửi yêu cầu mới</h3>
													<form method="post" name="ticket_form">
														<div class="form-group">
															<label>Vấn đề cần hỗ trợ</label>
															<select name="issue" class="form-control" required>
																<option value="">-- Chọn vấn đề --</option>
																<option value="Hủy tour">Hủy tour</option>
																<option value="Đặt tour">Đặt tour</option>
																<option value="Thay đổi lịch trình">Thay đổi lịch trình</option>
																<option value="Hoàn tiền">Hoàn tiền</option>
																<option value="Vấn đề thanh toán">Vấn đề thanh toán</option>
																<option value="Khác">Khác</option>
															</select>
														</div>

														<div class="form-group">
															<label>Mô tả chi tiết</label>
															<textarea class="form-control" name="description" rows="6" placeholder="Vui lòng mô tả chi tiết vấn đề của bạn..." required></textarea>
														</div>

														<button type="submit" name="submit_ticket" class="btn btn-block" style="background:#faa61a;color:white;font-weight:bold;">Gửi yêu cầu</button>
													</form>
												</div>
											</div>

											<div class="col-md-7">
												<h3 class="form-heading" style="border-bottom: 2px solid #ccc;"><i class="fa fa-history"></i> Lịch sử yêu cầu</h3>
												<div class="table-responsive">
													<table class="table table-bordered table-striped">
														<thead>
															<tr>
																<th>Mã</th>
																<th>Vấn đề</th>
																<th>Mô tả</th>
																<th>Phản hồi</th>
																<th>Ngày gửi</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$uemail = $_SESSION['login'];
															// SỬA LẠI CÂU SQL SELECT ĐÚNG TÊN CỘT
															$sql = "SELECT * from trogiup where emailgui=:uemail ORDER BY ngaygui DESC";
															$query = $dbh->prepare($sql);
															$query->bindParam(':uemail', $uemail, PDO::PARAM_STR);
															$query->execute();
															$results = $query->fetchAll(PDO::FETCH_OBJ);

															if ($query->rowCount() > 0) {
																foreach ($results as $result) { ?>
																	<tr>
																		<td>#<?php echo htmlentities($result->id_trogiup); ?></td>
																		<td><?php echo htmlentities($result->chude); ?></td>
																		<td>
																			<div style="max-height: 60px; overflow-y: auto;">
																				<?php echo htmlentities($result->noidung); ?>
																			</div>
																		</td>
																		<td>
																			<?php
																			if ($result->traloi == "") {
																				echo '<span class="label label-warning">Chờ xử lý</span>';
																			} else {
																				echo '<div style="max-height: 60px; overflow-y: auto; color:green;">' . htmlentities($result->traloi) . '</div>';
																				if ($result->ngaytraloi) {
																					echo '<small style="color:#999; display:block; margin-top:5px;">(' . date("d/m", strtotime($result->ngaytraloi)) . ')</small>';
																				}
																			}
																			?>
																		</td>
																		<td><?php echo date("d/m/Y", strtotime($result->ngaygui)); ?></td>
																	</tr>
																<?php }
															} else { ?>
																<tr>
																	<td colspan="5" style="text-align:center">Bạn chưa gửi yêu cầu hỗ trợ nào.</td>
																</tr>
															<?php } ?>
														</tbody>
													</table>
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

		<?php include('includes/footer.php'); ?>
		<?php include('includes/signup.php'); ?>
		<?php include('includes/signin.php'); ?>
		<?php include('includes/write-us.php'); ?>
	</body>

	</html>
<?php } ?>