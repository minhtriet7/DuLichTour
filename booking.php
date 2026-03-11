<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {
	// 1. Lấy thông tin người dùng để hiển thị tên
	$useremail = $_SESSION['login'];
	$sql_user = "SELECT * from nguoidung where id_email=:useremail";
	$query_user = $dbh->prepare($sql_user);
	$query_user->bindParam(':useremail', $useremail, PDO::PARAM_STR);
	$query_user->execute();
	$result_user = $query_user->fetch(PDO::FETCH_OBJ);

	// 2. Xử lý hủy đơn
	if (isset($_REQUEST['bkid'])) {
		$bid = intval($_GET['bkid']);
		$status = 2;
		$cancelby = 'u';
		$sql = "UPDATE hoadon SET trangthai=:status,huy=:cancelby WHERE id_hoadon=:bid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':cancelby', $cancelby, PDO::PARAM_STR);
		$query->bindParam(':bid', $bid, PDO::PARAM_STR);
		$query->execute();
		$msg = "Hủy đơn thành công";
	}
?>
	<!DOCTYPE HTML>
	<html lang="en">

	<head>
		<title>Tour đã đặt</title>
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
						<h1 class="page-title">Tour đã đặt</h1>
						<ul class="breadcrumb">
							<li><a href="index.php">Trang chủ</a></li>
							<li class="active">Tour đã đặt</li>
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
								<p>Danh sách các tour bạn đã đặt</p>
							</div>

							<div class="dashboard-wrapper">
								<div class="row">
									<div class="col-xs-12 col-sm-2 col-md-2 dashboard-nav">
										<ul class="nav nav-tabs nav-stacked text-center">
											<li><a href="issuetickets.php"><span><i class="fa fa-question-circle"></i></span>Trợ giúp</a></li>
											<li><a href="profile.php"><span><i class="fa fa-user"></i></span>Hồ sơ</a></li>
											<li class="active"><a href="booking.php"><span><i class="fa fa-briefcase"></i></span>Đặt tour</a></li>
											<li><a href="password.php"><span><i class="fa fa-lock"></i></span>Mật khẩu</a></li>
											<li><a href="enquiry.php"><span><i class="fa fa-pencil-square-o"></i></span>Góp ý</a></li>
											<li><a href="index.php"><span><i class="fa fa-home"></i></span>Trang chủ</a></li>
										</ul>
									</div>

									<div class="col-xs-12 col-sm-10 col-md-10 dashboard-content booking-trips">
										<h2 class="dash-content-title">Chuyến đi đã đặt</h2>
										<?php if ($error) { ?><div class="errorWrap"><strong>Lỗi</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>Hoàn thành</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>

										<div class="dashboard-listing booking-listing">
											<div class="dash-listing-heading">
												<div class="custom-radio">
													<ul style="list-style: none; padding-left: 0;">
														<li style="display:inline-block; margin-right: 20px;" class="active"><a href="#" style="color:#faa61a; font-weight:bold;"><span><i class="glyphicon glyphicon-record"></i></span> Tour du lịch</a></li>
														<!-- <li style="display:inline-block;"><a href="booking-hotel.php"><span><i class="far fa-circle"></i></span> Khách sạn</a></li> -->
													</ul>
												</div>
											</div>

											<?php
											$uemail = $_SESSION['login'];
											$sql = "SELECT hoadon.id_hoadon as bookid,hoadon.id_goi as pkgid,goidulich.tengoi as packagename,goidulich.ngayxuatphat as ngdi,goidulich.ngayve as sngay,goidulich.giagoi as giagoi,goidulich.giatreem as giatreem,goidulich.giatrenho as giatrenho,goidulich.giaphongdon as giaphongdon,hoadon.nguoilon as nguoilon,hoadon.treem as treem,hoadon.trenho as trenho,hoadon.embe as embe,hoadon.ghichu as comment,hoadon.trangthai as status,hoadon.ngaydat as regdate,hoadon.huy as cancelby,hoadon.ngaycapnhat as upddate from hoadon join goidulich on goidulich.id_goi=hoadon.id_goi where email_nguoidung=:uemail";
											$query = $dbh->prepare($sql);
											$query->bindParam(':uemail', $uemail, PDO::PARAM_STR);
											$query->execute();
											$results = $query->fetchAll(PDO::FETCH_OBJ);
											$cnt = 1;
											if ($query->rowCount() > 0) {
												foreach ($results as $result) { ?>
													<div class="table-responsive">
														<table class="table table-hover">
															<tbody>
																<tr>
																	<td class="dash-list-icon booking-list-date">
																		<div class="b-date" style="width: 75px;">
																			<h3><?php echo htmlentities($cnt); ?></h3>
																			<p>Mã: <?php echo htmlentities($result->bookid); ?></p>
																		</div>
																	</td>
																	<td class="dash-list-text booking-list-detail">
																		<a href="tour-detail.php?pkgid=<?php echo htmlentities($result->pkgid); ?>">
																			<h3 style="color: #7e4fa9"><?php echo htmlentities($result->packagename); ?></h3>
																		</a>
																		<ul class="list-unstyled booking-info">
																			<li><span>Khởi hành: </span><?php echo date("d-m-Y", strtotime($result->ngdi)); ?> | <span>Kết thúc: </span><?php echo date("d-m-Y", strtotime($result->sngay)); ?></li>
																			<li><span>Đã đặt gồm: </span><?php echo ($result->nguoilon); ?> Người lớn | <?php echo ($result->treem); ?> Trẻ em | <?php echo ($result->trenho); ?> Trẻ nhỏ | <?php echo ($result->embe); ?> Em bé | <?php echo ($result->phongdon + 1); ?> Phòng đơn</li>
																			<li><span>Tổng tiền: </span><?php echo number_format($result->giagoi * $result->nguoilon + $result->giatreem * $result->treem + $result->giatrenho * $result->trenho + $result->giaphongdon * $result->phongdon + $result->giaphongdon); ?> đ</li>
																			<li><span>Ghi chú: </span><?php echo htmlentities($result->comment); ?></li>
																			<li><span>Ngày đặt: </span><?php echo date("d-m-Y", strtotime($result->regdate)); ?></li>
																		</ul>
																	</td>
																	<td>
																		<?php
																		if ($result->status == 0) {
																			echo "Đang chờ xử lý";
																		}
																		if ($result->status == 1) {
																			echo "Đã xác nhận";
																		}
																		if ($result->status == 2 and $result->cancelby == 'u') {
																			echo "Bạn đã hủy lúc " . $result->upddate;
																		}
																		if ($result->status == 2 and $result->cancelby == 'a') {
																			echo "Admin đã hủy lúc " . $result->upddate;
																		}
																		?>
																	</td>
																	<td>
																		<?php if ($result->status == 0) { ?>
																			<a href="booking.php?bkid=<?php echo htmlentities($result->bookid); ?>" onclick="return confirm('Bạn thật sự muốn hủy đặt tour này?')" class="btn btn-danger btn-sm">Hủy</a>
																		<?php } ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												<?php $cnt++;
												}
											} else { ?>
												<p style="text-align:center; padding:20px;">Bạn chưa đặt tour nào.</p>
											<?php } ?>
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