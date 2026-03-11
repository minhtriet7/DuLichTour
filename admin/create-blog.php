<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	if (isset($_POST['submit'])) {
		$chude = $_POST['chude'];
		$tomtat = $_POST['tomtat'];
		$noidung = $_POST['noidung'];
		$nguoiviet = $_POST['nguoiviet'];
		$pimage = $_FILES["packageimage"]["name"];
		move_uploaded_file($_FILES["packageimage"]["tmp_name"], "../images/blog/" . $_FILES["packageimage"]["name"]);

		$sql = "INSERT INTO blog(chude, tomtat, noidung, nguoiviet, hinhanh) VALUES(:chude, :tomtat,:noidung, :nguoiviet,:pimage)";
		$query = $dbh->prepare($sql);
		$query->bindParam(':chude', $chude, PDO::PARAM_STR);
		$query->bindParam(':tomtat', $tomtat, PDO::PARAM_STR);
		$query->bindParam(':noidung', $noidung, PDO::PARAM_STR);
		$query->bindParam(':nguoiviet', $nguoiviet, PDO::PARAM_STR);
		$query->bindParam(':pimage', $pimage, PDO::PARAM_STR);
		$query->execute();
		$lastInsertId = $dbh->lastInsertId();

		if ($lastInsertId) {
			// SỬA: Lưu vào Session và chuyển trang để tránh gửi lại form khi F5
			$_SESSION['msg'] = "Blog được tạo thành công";
			header("Location: create-blog.php");
			exit();
		} else {
			$error = "Thêm thất bại. Hãy thử lại";
		}
	}
?>
	<!DOCTYPE HTML>
	<html>

	<head>
		<title>Tạo blog</title>
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
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
		<script src="js/jquery-2.1.4.min.js"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/table-style.css" />
		<link rel="stylesheet" type="text/css" href="css/basictable.css" />
		<script type="text/javascript" src="js/jquery.basictable.min.js"></script>
		<style>
			.errorWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #dd3d36;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			.succWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #5cb85c;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			.col-sm-6 {
				width: 73%;
			}

			.logo-w3-agile {
				background-color: #1B93E1 !important;
			}
		</style>
		<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css' />
		<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
	</head>

	<body>
		<div class="page-container">
			<div class="left-content">
				<div class="mother-grid-inner">
					<?php include('includes/header.php'); ?>
					<div class="clearfix"> </div>
				</div>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="index.php">Trang chủ</a><i class="fa fa-angle-right"></i>Thêm blog </li>
				</ol>
				<div class="grid-form">

					<div class="grid-form1">
						<h3>Tạo blog</h3>

						<?php if (isset($error)) { ?>
							<div class="errorWrap"><strong>Lỗi</strong>:<?php echo htmlentities($error); ?> </div>
						<?php } else if (isset($_SESSION['msg'])) { ?>
							<div class="succWrap"><strong>Hoàn thành</strong>:<?php echo " " . htmlentities($_SESSION['msg']); ?> </div>
							<?php unset($_SESSION['msg']); // Xóa session sau khi hiện 
							?>
						<?php } ?>
						<div class="tab-content">
							<div class="tab-pane active" id="horizontal-form">
								<form class="form-horizontal" name="package" method="post" enctype="multipart/form-data">
									<div class="form-group">
										<label for="focusedinput" class="col-sm-2 control-label">Chủ đề</label>
										<div class="col-sm-8">
											<input type="text" class="form-control1" name="chude" id="packagename" placeholder="Chủ đề" required>
										</div>
									</div>
									<div class="form-group">
										<label for="focusedinput" class="col-sm-2 control-label">Tóm tắt</label>
										<div class="col-sm-8">
											<input type="text" class="form-control1" name="tomtat" id="packagename" placeholder="Tóm tắt" required>
										</div>
									</div>
									<div class="form-group">
										<label for="focusedinput" class="col-sm-2 control-label">Người viết</label>
										<div class="col-sm-8">
											<input type="text" class="form-control1" name="nguoiviet" id="packagename" placeholder="Người viết" required>
										</div>
									</div>

									<div class="form-group">
										<label for="focusedinput" class="col-sm-2 control-label">Nội dung</label>
										<div class="col-sm-8">
											<textarea class="form-control" rows="5" cols="50" name="noidung" id="packagedetails" placeholder="Nội dung" required></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="focusedinput" class="col-sm-2 control-label">Hình ảnh</label>
										<div class="col-sm-8">
											<input type="file" name="packageimage" id="packageimage" required>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-8 col-sm-offset-2">
											<button type="submit" name="submit" class="btn-primary btn">Tạo</button>
											<button type="reset" class="btn-inverse btn">Làm mới</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<script>
					$(document).ready(function() {
						var navoffeset = $(".header-main").offset().top;
						$(window).scroll(function() {
							var scrollpos = $(window).scrollTop();
							if (scrollpos >= navoffeset) {
								$(".header-main").addClass("fixed");
							} else {
								$(".header-main").removeClass("fixed");
							}
						});
					});
				</script>
				<div class="inner-block"></div>
				<?php include('includes/footer.php'); ?>
			</div>
		</div>
		<?php include('includes/sidebarmenu.php'); ?>
		<div class="clearfix"></div>
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
		<script src="../ckeditor/ckeditor.js"></script>
		<script>
			CKEDITOR.replace('packagedetails');
		</script>
		<script src="js/jquery.nicescroll.js"></script>
		<script src="js/scripts.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>

	</html>
<?php } ?>