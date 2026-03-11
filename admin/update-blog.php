<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	$pid = intval($_GET['pid']);

	if (isset($_POST['submit'])) {
		$ten = $_POST['tenchude'];
		$tomtat = $_POST['tomtat'];
		$noidung = $_POST['noidung'];
		$nguoiviet = $_POST['nguoiviet'];

		$pimage = $_FILES["packageimage"]["name"];

		// 1. Logic cập nhật (Có ảnh hoặc Không ảnh)
		if (!empty($pimage)) {
			// Nếu có chọn ảnh mới -> Upload và Update cột hinhanh
			move_uploaded_file($_FILES["packageimage"]["tmp_name"], "../images/blog/" . $pimage);
			$sql = "UPDATE blog SET chude=:ten, tomtat=:tomtat, noidung=:noidung, nguoiviet=:nguoiviet, hinhanh=:pimage WHERE id_blog=:pid";
			$query = $dbh->prepare($sql);
			$query->bindParam(':pimage', $pimage, PDO::PARAM_STR);
		} else {
			// Nếu không chọn ảnh -> Giữ nguyên ảnh cũ
			$sql = "UPDATE blog SET chude=:ten, tomtat=:tomtat, noidung=:noidung, nguoiviet=:nguoiviet WHERE id_blog=:pid";
			$query = $dbh->prepare($sql);
		}

		$query->bindParam(':ten', $ten, PDO::PARAM_STR);
		$query->bindParam(':tomtat', $tomtat, PDO::PARAM_STR);
		$query->bindParam(':noidung', $noidung, PDO::PARAM_STR);
		$query->bindParam(':nguoiviet', $nguoiviet, PDO::PARAM_STR);
		$query->bindParam(':pid', $pid, PDO::PARAM_STR);

		if ($query->execute()) {
			// 2. Fix lỗi Reload: Lưu thông báo vào Session và chuyển hướng
			$_SESSION['msg'] = "Cập nhật Blog thành công";
			header("Location: update-blog.php?pid=$pid");
			exit();
		} else {
			$error = "Đã có lỗi xảy ra";
		}
	}
?>
	<!DOCTYPE HTML>
	<html>

	<head>
		<title>Cập nhật Blog</title>
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
		<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css' />
		<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
		<script src="../ckeditor/ckeditor.js"></script>
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
				</div>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="index.php">Home</a><i class="fa fa-angle-right"></i>Cập nhật blog </li>
				</ol>
				<div class="grid-form">
					<div class="grid-form1">
						<h3>Cập nhật blog</h3>

						<?php if (isset($error)) { ?>
							<div class="errorWrap"><strong>Lỗi</strong>:<?php echo htmlentities($error); ?> </div>
						<?php } else if (isset($_SESSION['msg'])) { ?>
							<div class="succWrap"><strong>Hoàn thành</strong>:<?php echo htmlentities($_SESSION['msg']); ?> </div>
							<?php unset($_SESSION['msg']); ?>
						<?php } ?>

						<div class="tab-content">
							<div class="tab-pane active" id="horizontal-form">
								<?php
								$pid = intval($_GET['pid']);
								$sql = "SELECT * from blog where id_blog=:pid";
								$query = $dbh->prepare($sql);
								$query->bindParam(':pid', $pid, PDO::PARAM_STR);
								$query->execute();
								$results = $query->fetchAll(PDO::FETCH_OBJ);
								if ($query->rowCount() > 0) {
									foreach ($results as $result) { ?>

										<form class="form-horizontal" name="package" method="post" enctype="multipart/form-data">
											<div class="form-group">
												<label for="focusedinput" class="col-sm-2 control-label">Chủ đề</label>
												<div class="col-sm-8">
													<input type="text" class="form-control1" name="tenchude" id="packagename" placeholder="Chủ đề" value="<?php echo htmlentities($result->chude); ?>" required>
												</div>
											</div>
											<div class="form-group">
												<label for="focusedinput" class="col-sm-2 control-label">Tóm tắt</label>
												<div class="col-sm-8">
													<textarea class="form-control" rows="5" cols="50" name="tomtat" id="packagedetails" placeholder="Tóm tắt" required><?php echo htmlentities($result->tomtat); ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label for="focusedinput" class="col-sm-2 control-label">Nội dung</label>
												<div class="col-sm-8">
													<textarea class="form-control" rows="5" cols="50" name="noidung" id="packagedetail" placeholder="Nội dung" required><?php echo htmlentities($result->noidung); ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label for="focusedinput" class="col-sm-2 control-label">Người viết</label>
												<div class="col-sm-8">
													<input type="text" class="form-control1" name="nguoiviet" id="packageprice" placeholder="Người viết" value="<?php echo htmlentities($result->nguoiviet); ?>" required>
												</div>
											</div>

											<div class="form-group">
												<label for="focusedinput" class="col-sm-2 control-label">Hình ảnh</label>
												<div class="col-sm-8">
													<img id="imagePreview" src="../images/blog/<?php echo htmlentities($result->hinhanh); ?>" width="200" style="border: 1px solid #ddd; padding: 5px; margin-bottom: 10px;">

													<div>
														<label style="font-weight: normal; cursor: pointer; color: #0275d8;">
															[Chọn ảnh mới nếu muốn thay đổi]
														</label>
														<input type="file" name="packageimage" id="packageimage" onchange="previewImage(this);">
													</div>
												</div>
											</div>

											<div class="form-group">
												<label for="focusedinput" class="col-sm-2 control-label">Cập nhật lần cuối</label>
												<div class="col-sm-8">
													<p class="form-control-static"><?php echo htmlentities($result->ngaycapnhat); ?></p>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-8 col-sm-offset-2">
													<button type="submit" name="submit" class="btn-primary btn">Cập nhật</button>
												</div>
											</div>
										</form>
								<?php }
								} ?>
							</div>
						</div>
					</div>
				</div>

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

			// 3. Script Xem trước ảnh (Preview)
			function previewImage(input) {
				if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function(e) {
						$('#imagePreview').attr('src', e.target.result);
					};
					reader.readAsDataURL(input.files[0]);
				}
			}
		</script>
		<script>
			CKEDITOR.replace('packagedetail');
		</script>
		<script src="js/jquery.nicescroll.js"></script>
		<script src="js/scripts.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>

	</html>
<?php } ?>