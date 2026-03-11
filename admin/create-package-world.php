<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "starvel";

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");
if ($conn->connect_error) {
	die("connection failed" . $conn->connect_error);
}

if (isset($_POST["submit121"])) {
	$stmt = $conn->prepare("insert into goidulich(khuyenmai, nuocngoai, quocgia, tengoi, noixuatphat, vitri,giagoi,giatreem,giatrenho,giaphongdon,chitietgoi,chuongtrinh,luuy, songay, giodi,ngayxuatphat,ngayve,phuongtien, hinhanh) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	$stmt->bind_param("sssssssssssssssssss", $khuyenmai, $nuocngoai, $quocgia, $tengoi, $noixuatphat, $vitri, $giagoi, $giatreem, $giatrenho, $giaphongdon, $chitietgoi, $chuongtrinh, $luuy, $songay, $giodi, $ngayxuatphat, $ngayve, $phuongtien, $hinhanh);

	// Lấy dữ liệu từ form
	$khuyenmai = $_POST['khuyenmai'];
	$nuocngoai = $_POST['nuocngoai'];
	$quocgia = $_POST['quocgia'];
	$tengoi = $_POST['tengoi'];
	$noixuatphat = $_POST['noixuatphat'];
	$vitri = $_POST['vitri'];
	$giagoi = $_POST['giagoi'];
	$giatreem = $_POST['giatreem'];
	$giatrenho = $_POST['giatrenho'];
	$giaphongdon = $_POST['giaphongdon'];
	$chitietgoi = $_POST['chitietgoi'];
	$chuongtrinh = $_POST['chuongtrinh'];
	$luuy = $_POST['luuy'];
	$songay = $_POST['songay'];
	$giodi = $_POST['giodi'];
	$ngayxuatphat = $_POST['ngayxuatphat'];
	$ngayve = $_POST['ngayve'];
	$phuongtien = $_POST['phuongtien'];

	// XỬ LÝ HÌNH ẢNH [Cập nhật]
	$hinhanh = $_FILES["packageimage"]["name"];
	// Đường dẫn thư mục muốn lưu
	$target_dir = "../images/tour/";

	// Kiểm tra xem thư mục có tồn tại không, nếu không thì tạo mới
	if (!file_exists($target_dir)) {
		mkdir($target_dir, 0777, true);
	}

	// Di chuyển file vào thư mục images/tour
	move_uploaded_file($_FILES["packageimage"]["tmp_name"], $target_dir . $hinhanh);

	// Thực thi và kiểm tra
	// Thực thi và kiểm tra
	if ($stmt->execute()) {
		// 1. Lưu thông báo vào Session
		$_SESSION['msg'] = "Tạo Tour thành công!";
		// 2. Chuyển hướng người dùng về lại chính trang này (để xóa trạng thái POST)
		header("Location: " . $_SERVER['PHP_SELF']);
		exit();
	} else {
		$error = "Có lỗi xảy ra, vui lòng thử lại.";
	}
}
?>


<!DOCTYPE HTML>
<html>

<head>
	<title>Thêm tour Quốc tế</title>
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
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="http://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/dataTables.semanticui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">
	<script src="https://cdn.datatables.net/1.10.20/js/dataTables.semanticui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
	<script src="../ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var dt = $('#table').DataTable({
				"dom": '<"pull-right"f>t<"row mt-1" <"col-sm-3" l><"col-sm-6" <"pull-right" p>>>',
				"language": {
					"lengthMenu": "Hiển thị _MENU_ trên 1 trang",
					"zeroRecords": "Không tìm thấy nội dung cần tìm",
					"infoEmpty": "Chưa có nội dung",
					"infoFiltered": "(filtered from _MAX_ total records)",
					"sSearch": "Tìm kiếm",
					"oPaginate": {
						"sFirst": "Đầu",
						"sPrevious": "Trước",
						"sNext": "Tiếp",
						"sLast": "Cuối"
					}
				}
			});
			$('#table-breakpoint').basictable({
				breakpoint: 768
			});
		});
	</script>
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
				<li class="breadcrumb-item"><a href="index.php">Trang chủ</a><i class="fa fa-angle-right"></i>Cập nhật tour </li>
			</ol>
			<div class="grid-form">

				<?php if (isset($error)) { ?>
					<div class="errorWrap">
						<strong>Lỗi</strong>: <?php echo htmlentities($error); ?>
					</div>
				<?php } else if (isset($_SESSION['msg'])) { ?>
					<div class="succWrap">
						<strong>Thành công</strong>: <?php echo htmlentities($_SESSION['msg']); ?>
					</div>
					<?php
					// Quan trọng: Xóa thông báo sau khi đã hiện để không hiện lại khi F5
					unset($_SESSION['msg']);
					?>
				<?php } ?>

				<div class="grid-form1">
					<h3>Tạo tour Quốc Tế</h3>

					<div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" name="package" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

								<div class="col-xs-12 col-sm-2 col-md-2 dashboard-nav">
									<ul style="width: 500px;margin-top: 30px; list-style: none;">
										<li style="margin-top: -19px; margin-left: 125px;"><a href="create-package.php"><span><i class="far fa-circle"></i></span> Trong nước</a></li>
										<li class="active" style="margin-top: -19px;margin-left: 260px;"><a href="#"><span><i class=" glyphicon glyphicon-record  "></i></span> Quốc tế</a></li>
									</ul>
								</div>

								<br> <br> <br>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="max-width: 18.666667%;">Khuyến mãi: </label>
									<div class="col-sm-8">
										<input type="radio" value="1" name="khuyenmai"> Có
										&nbsp;&nbsp;&nbsp;
										<input style=" margin-left: 95px;" type="radio" value="0" name="khuyenmai" checked="checked"> Không
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Quốc gia</label>
									<div class="col-sm-8">
										<input type="hidden" class="form-control1" name="nuocngoai" id="nuocngoai" value="1" required>
										<input type="text" class="form-control1" name="quocgia" id="quocgia" placeholder="Tên quốc gia" required>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Tên gói</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="tengoi" id="packagename" placeholder="Tên tour" required>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Điểm khởi hành</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="noixuatphat" id="noixuatphat" placeholder="Nơi xuất phát" required>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Điểm đến</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="vitri" id="vitri" placeholder=" Vị trí" required>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Giá người lớn</label>
									<div class="col-sm-8">
										<input type="number" class="form-control1" name="giagoi" id="giagoi" placeholder=" Giá gói VND" required min="0">
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Giá trẻ em</label>
									<div class="col-sm-8">
										<input type="number" class="form-control1" name="giatreem" id="giatreem" placeholder=" Giá trẻ em VND" required min="0">
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Giá trẻ nhỏ</label>
									<div class="col-sm-8">
										<input type="number" class="form-control1" name="giatrenho" id="giatrenho" placeholder=" Giá trẻ nhỏ VND" required min="0">
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Giá phòng đơn</label>
									<div class="col-sm-8">
										<input type="number" class="form-control1" name="giaphongdon" id="giaphongdon" placeholder=" Giá Phòng đơn VND" required min="0">
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Chi tiết</label>
									<div class="col-sm-8">
										<textarea class="form-control" rows="5" cols="50" name="chitietgoi" id="packagedetails" placeholder="Chi tiết" required></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Chương trình</label>
									<div class="col-sm-8">
										<textarea class="form-control" rows="5" cols="50" name="chuongtrinh" id="packagedetails1" placeholder="Chương trình" required></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Lưu ý</label>
									<div class="col-sm-8">
										<textarea class="form-control" rows="5" cols="50" name="luuy" id="packagedetails2" placeholder="Lưu ý" required></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Số ngày</label>
									<div class="col-sm-8">
										<input type="number" class="form-control1" name="songay" id="songay" placeholder=" Số ngày" required min="1">
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Giờ xuất phát</label>
									<div class="col-sm-8">
										<input type="time" class="form-control1" name="giodi" id="giodi" placeholder=" Giờ đi" required>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Ngày xuất phát</label>
									<div class="col-sm-8">
										<input type="date" class="form-control1" name="ngayxuatphat" id="ngayxuatphat" placeholder=" Ngày đi" required>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Ngày về</label>
									<div class="col-sm-8">
										<input type="date" class="form-control1" name="ngayve" id="ngayve" placeholder=" Ngày về" required>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Phương tiện</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="phuongtien" id="phuongtien" placeholder=" Phương tiện" required>
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
										<button type="submit" name="submit121" class="btn-primary btn">Tạo</button>
										<button type="reset" class="btn-inverse btn">Làm mới</button>
									</div>
								</div>

							</form>
						</div>
						<div class="panel-footer"></div>
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

	<script>
		CKEDITOR.replace('packagedetails');
	</script>
	<script>
		CKEDITOR.replace('packagedetails1');
	</script>
	<script>
		CKEDITOR.replace('packagedetails2');
	</script>

	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<script src="js/bootstrap.min.js"></script>

	<script type="text/javascript">
		function validateForm() {
			// 1. Kiểm tra giá trị các ô tiền
			var giaGoi = document.getElementById("giagoi").value;
			var giaTreEm = document.getElementById("giatreem").value;
			var giaTreNho = document.getElementById("giatrenho").value;
			var giaPhongDon = document.getElementById("giaphongdon").value;

			function isInvalidNumber(val) {
				return isNaN(val) || val < 0 || val.trim() == "";
			}

			if (isInvalidNumber(giaGoi)) {
				alert("Giá người lớn phải là số và không được nhỏ hơn 0!");
				document.getElementById("giagoi").focus();
				return false;
			}
			if (isInvalidNumber(giaTreEm)) {
				alert("Giá trẻ em phải là số và không được nhỏ hơn 0!");
				document.getElementById("giatreem").focus();
				return false;
			}
			if (isInvalidNumber(giaTreNho)) {
				alert("Giá trẻ nhỏ phải là số và không được nhỏ hơn 0!");
				document.getElementById("giatrenho").focus();
				return false;
			}
			if (isInvalidNumber(giaPhongDon)) {
				alert("Giá phòng đơn phải là số và không được nhỏ hơn 0!");
				document.getElementById("giaphongdon").focus();
				return false;
			}

			// 2. Kiểm tra ngày đi - ngày về
			var ngayDi = document.getElementById("ngayxuatphat").value;
			var ngayVe = document.getElementById("ngayve").value;
			if (ngayDi && ngayVe) {
				if (new Date(ngayVe) < new Date(ngayDi)) {
					alert("Ngày về không được phép trước Ngày xuất phát!");
					document.getElementById("ngayve").focus();
					return false;
				}
			}

			// 3. Kiểm tra CKEditor (Chi tiết, Chương trình, Lưu ý)
			var chiTiet = CKEDITOR.instances['packagedetails'].getData();
			var chuongTrinh = CKEDITOR.instances['packagedetails1'].getData();
			var luuY = CKEDITOR.instances['packagedetails2'].getData();

			if (chiTiet.trim() == "") {
				alert("Vui lòng nhập Chi tiết gói tour!");
				return false;
			}
			if (chuongTrinh.trim() == "") {
				alert("Vui lòng nhập Chương trình tour!");
				return false;
			}
			if (luuY.trim() == "") {
				alert("Vui lòng nhập Lưu ý cho tour!");
				return false;
			}

			return true;
		}
	</script>

</body>

</html>