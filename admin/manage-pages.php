<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	// Xử lý cập nhật nội dung
	if (isset($_POST['submit'])) {
		$pagetype = $_GET['type'];
		$pagedetails = $_POST['pgedetails'];

		$sql = "UPDATE vanban SET noidung=:pagedetails WHERE loaivb=:pagetype";
		$query = $dbh->prepare($sql);
		$query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
		$query->bindParam(':pagedetails', $pagedetails, PDO::PARAM_STR);

		if ($query->execute()) {
			$msg = "Cập nhật nội dung trang thành công";
		} else {
			$error = "Có lỗi xảy ra, vui lòng thử lại";
		}
	}
?>
	<!DOCTYPE HTML>
	<html>

	<head>
		<title>Quản lý văn bản</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="application/x-javascript">
			addEventListener("load", function() {
				setTimeout(hideURLbar, 0);
			}, false);

			function hideURLbar() {
				window.scrollTo(0, 1);
			}
		</script>

		<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link rel="stylesheet" href="css/morris.css" type="text/css" />
		<link href="css/font-awesome.css" rel="stylesheet">
		<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />

		<script src="js/jquery-2.1.4.min.js"></script>

		<script type="text/javascript" src="nicEdit.js"></script>
		<script type="text/javascript">
			bkLib.onDomLoaded(function() {
				nicEditors.allTextAreas()
			});
		</script>

		<style>
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

			.logo-w3-agile {
				background-color: #1B93E1 !important;
			}
		</style>
	</head>

	<body>
		<div class="page-container">
			<div class="left-content">
				<div class="mother-grid-inner">
					<?php include('includes/header.php'); ?>
					<div class="clearfix"> </div>

					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="dashboard.php">Trang chủ</a><i class="fa fa-angle-right"></i>Quản lý văn bản </li>
					</ol>

					<div class="grid-form">
						<div class="grid-form1">
							<h3>Quản lý nội dung trang</h3>

							<?php if (isset($error)) { ?>
								<div class="errorWrap"><strong>Lỗi</strong>: <?php echo htmlentities($error); ?> </div>
							<?php } else if (isset($msg)) { ?>
								<div class="succWrap"><strong>Thành công</strong>: <?php echo htmlentities($msg); ?> </div>
							<?php } ?>

							<div class="tab-content">
								<div class="tab-pane active" id="horizontal-form">
									<form class="form-horizontal" name="package" method="post" enctype="multipart/form-data">

										<div class="form-group">
											<label for="focusedinput" class="col-sm-2 control-label">Chọn trang</label>
											<div class="col-sm-8">
												<select name="menu1" class="form-control" onChange="window.location.href=this.value">
													<option value="">-- Chọn trang cần sửa --</option>
													<option value="manage-pages.php?type=Điều khoản" <?php if ($_GET['type'] == 'Điều khoản') echo 'selected'; ?>>Điều kiện và điều khoản</option>
													<option value="manage-pages.php?type=Chính sách và bảo mật" <?php if ($_GET['type'] == 'Chính sách và bảo mật') echo 'selected'; ?>>Bảo mật và chính sách</option>
													<option value="manage-pages.php?type=Giới thiệu" <?php if ($_GET['type'] == 'Giới thiệu') echo 'selected'; ?>>Giới thiệu</option>
													<option value="manage-pages.php?type=Liên hệ" <?php if ($_GET['type'] == 'Liên hệ') echo 'selected'; ?>>Liên hệ</option>
												</select>
											</div>
										</div>

										<div class="form-group">
											<label for="focusedinput" class="col-sm-2 control-label">Trang đang chọn</label>
											<div class="col-sm-8">
												<?php
												// Hiển thị tên trang đang chọn cho rõ ràng
												$page_title = "";
												if (isset($_GET['type'])) {
													switch ($_GET['type']) {
														case "Điều khoản":
															$page_title = "Điều kiện và điều khoản";
															break;
														case "Chính sách và bảo mật":
															$page_title = "Bảo mật và chính sách";
															break;
														case "Giới thiệu":
															$page_title = "Giới thiệu chung";
															break;
														case "Liên hệ":
															$page_title = "Thông tin liên hệ";
															break;
														default:
															$page_title = "";
															break;
													}
												}
												?>
												<input type="text" class="form-control" value="<?php echo $page_title; ?>" disabled style="background-color: #eee;">
											</div>
										</div>

										<div class="form-group">
											<label for="focusedinput" class="col-sm-2 control-label">Nội dung</label>
											<div class="col-sm-8">
												<textarea class="form-control" rows="10" cols="50" name="pgedetails" id="pgedetails" placeholder="Nội dung trang" required>
                                                <?php
																								if (isset($_GET['type'])) {
																									$pagetype = $_GET['type'];
																									$sql = "SELECT noidung from vanban where loaivb=:pagetype";
																									$query = $dbh->prepare($sql);
																									$query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
																									$query->execute();
																									$results = $query->fetchAll(PDO::FETCH_OBJ);
																									if ($query->rowCount() > 0) {
																										foreach ($results as $result) {
																											echo htmlentities($result->noidung);
																										}
																									}
																								}
																								?>
                                            </textarea>
											</div>
										</div>

										<?php if (isset($_GET['type'])) { ?>
											<div class="row">
												<div class="col-sm-8 col-sm-offset-2">
													<button type="submit" name="submit" id="submit" class="btn-primary btn">Cập nhật</button>
												</div>
											</div>
										<?php } ?>

									</form>
								</div>
							</div>
						</div>
					</div>

					<?php include('includes/footer.php'); ?>
				</div>
			</div>

			<?php include('includes/sidebarmenu.php'); ?>
			<div class="clearfix"></div>
		</div>

		<script>
			var toggle = true;
			$(".sidebar-icon").click(function() {
				if (toggle) {
					$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
					$("#menu span").css({
						"position": "absolute"
					});
				} else {
					$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
					setTimeout(function() {
						$("#menu span").css({
							"position": "relative"
						});
					}, 400);
				}
				toggle = !toggle;
			});
		</script>
		<script src="js/jquery.nicescroll.js"></script>
		<script src="js/scripts.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>

	</html>
<?php } ?>