<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {

	// --- 1. XỬ LÝ HỦY ĐƠN ---
	if (isset($_REQUEST['bkid'])) {
		$bid = intval($_GET['bkid']);
		$status = 2;
		$cancelby = 'a';
		$sql = "UPDATE hoadon SET trangthai=:status, huy=:cancelby WHERE id_hoadon=:bid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':cancelby', $cancelby, PDO::PARAM_STR);
		$query->bindParam(':bid', $bid, PDO::PARAM_STR);
		$query->execute();
		$msg = "Đã hủy đơn thành công";
	}

	// --- 2. XỬ LÝ XÁC NHẬN ĐƠN ---
	if (isset($_REQUEST['bckid'])) {
		$bcid = intval($_GET['bckid']);
		$status = 1;
		$cancelby = 'a';
		$sql = "UPDATE hoadon SET trangthai=:status WHERE id_hoadon=:bcid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':bcid', $bcid, PDO::PARAM_STR);
		$query->execute();
		$msg = "Đã xác nhận đơn hàng";
	}

	// --- 3. XỬ LÝ GÁN/ĐỔI KHÁCH SẠN (MỚI) ---
	if (isset($_POST['assign_hotel'])) {
		$id_hoadon = $_POST['id_hoadon'];
		$id_khachsan = $_POST['id_khachsan'];

		$sql = "UPDATE hoadon SET id_ks=:idks WHERE id_hoadon=:idhd";
		$query = $dbh->prepare($sql);
		$query->bindParam(':idks', $id_khachsan, PDO::PARAM_INT);
		$query->bindParam(':idhd', $id_hoadon, PDO::PARAM_INT);

		if ($query->execute()) {
			$msg = "Đã cập nhật thông tin khách sạn!";
		} else {
			$error = "Có lỗi xảy ra.";
		}
	}

	// LẤY DANH SÁCH KHÁCH SẠN ĐỂ HIỂN THỊ TRONG MODAL
	$sql_ks = "SELECT id_ks, tenks FROM khachsan";
	$query_ks = $dbh->prepare($sql_ks);
	$query_ks->execute();
	$hotel_list = $query_ks->fetchAll(PDO::FETCH_OBJ);
?>
	<!DOCTYPE HTML>
	<html>

	<head>
		<title>Quản lý Khách sạn</title>
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
		<link rel="stylesheet" type="text/css" href="css/table-style.css" />
		<link rel="stylesheet" type="text/css" href="css/basictable.css" />
		<script type="text/javascript" src="js/jquery.basictable.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#table').basictable();
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
		</style>
		<script src="js/bootstrap.min.js"></script>
	</head>

	<body>
		<div class="page-container">
			<div class="left-content">
				<div class="mother-grid-inner">
					<?php include('includes/header.php'); ?>
					<div class="clearfix"> </div>
				</div>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="index.php">Home</a><i class="fa fa-angle-right"></i>Quản lý hóa đơn khách sạn</li>
				</ol>
				<div class="agile-grids">
					<?php if ($error) { ?><div class="errorWrap"><strong>Lỗi</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>Thành công</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>

					<div class="agile-tables">
						<div class="w3l-table-info">
							<h2>Quản lý đơn Khách sạn</h2>

							<div class="col-xs-12 col-sm-2 col-md-2 dashboard-nav">
								<ul style="width: 500px;margin-top: 30px; list-style: none;">
									<li style="margin-top: -19px;"><a href="manage-bookings.php"><span><i class="glyphicon glyphicon-road" style="color: #1b93e1;"></i></span> Tour</a></li>
									<li class="active" style="margin-top: -19px;margin-left: 80px;"><a href="manage-bookings-hotel.php"><span><i class="fas fa-hotel"></i></span> Khách sạn</a></li>
								</ul>
							</div>

							<table id="table">
								<thead>
									<tr>
										<th>Mã</th>
										<th>Tên khách</th>
										<th>SĐT</th>
										<th>Email</th>
										<th>Tên Khách sạn</th>
										<th>Tổng tiền (VNĐ)</th>
										<th>Check-in/out</th>
										<th>Số phòng</th>
										<th>Ngày đặt</th>
										<th>Trạng thái</th>
										<th>Hành động</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Truy vấn: Lấy tất cả đơn có liên quan đến khách sạn (id_ks > 0) hoặc chưa gán (để admin gán)
									// Lưu ý: Ta dùng LEFT JOIN khachsan để vẫn hiện đơn nếu id_ks bị sai/null
									$sql = "SELECT hoadon.id_hoadon as bookid, nguoidung.hoten as fname, nguoidung.sdt_nd as mnumber, nguoidung.id_email as email, 
                                        khachsan.tenks as pckname, khachsan.gia as gia, hoadon.sophong as sphong, hoadon.id_ks as pid, 
                                        khachsan.ngayvao as xphat, khachsan.ngayra as ve, hoadon.ghichu as comment, hoadon.ngaydat as ngaydat, 
                                        hoadon.trangthai as status, hoadon.huy as cancelby, hoadon.ngaycapnhat as upddate, hoadon.id_ks 
                                        FROM hoadon 
                                        JOIN nguoidung ON hoadon.email_nguoidung = nguoidung.id_email 
                                        LEFT JOIN khachsan ON khachsan.id_ks = hoadon.id_ks 
                                        WHERE hoadon.id_ks > 0 OR hoadon.id_ks IS NOT NULL
                                        ORDER BY hoadon.id_hoadon DESC";

									$query = $dbh->prepare($sql);
									$query->execute();
									$results = $query->fetchAll(PDO::FETCH_OBJ);
									if ($query->rowCount() > 0) {
										foreach ($results as $result) { ?>
											<tr>
												<td>#KS-<?php echo htmlentities($result->bookid); ?></td>
												<td><?php echo htmlentities($result->fname); ?></td>
												<td><?php echo htmlentities($result->mnumber); ?></td>
												<td><?php echo htmlentities($result->email); ?></td>

												<td>
													<?php if ($result->pckname) { ?>
														<a href="update-hotel.php?pid=<?php echo htmlentities($result->pid); ?>" style="font-weight:bold; color:green;">
															<?php echo htmlentities($result->pckname); ?>
														</a>
													<?php } else { ?>
														<span style="color:red">Chưa xác định</span>
													<?php } ?>
													<br>
													<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#hotelModal<?php echo $result->bookid; ?>" style="margin-top:5px;">
														<i class="fa fa-pencil"></i> Đổi KS
													</button>

													<div id="hotelModal<?php echo $result->bookid; ?>" class="modal fade" role="dialog">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title">Chọn khách sạn cho đơn #KS-<?php echo $result->bookid; ?></h4>
																</div>
																<div class="modal-body">
																	<form method="post">
																		<input type="hidden" name="id_hoadon" value="<?php echo $result->bookid; ?>">
																		<div class="form-group">
																			<label>Danh sách Khách sạn:</label>
																			<select name="id_khachsan" class="form-control" required>
																				<option value="">-- Chọn khách sạn --</option>
																				<?php foreach ($hotel_list as $hotel) { ?>
																					<option value="<?php echo $hotel->id_ks; ?>" <?php if ($result->id_ks == $hotel->id_ks) echo 'selected'; ?>>
																						<?php echo $hotel->tenks; ?>
																					</option>
																				<?php } ?>
																			</select>
																		</div>
																		<button type="submit" name="assign_hotel" class="btn btn-primary">Lưu thay đổi</button>
																	</form>
																</div>
															</div>
														</div>
													</div>
												</td>

												<td>
													<?php
													// Tính tổng tiền (Giá * Số phòng)
													if ($result->gia && $result->sphong) {
														echo number_format($result->gia * $result->sphong);
													} else {
														echo "0";
													}
													?>
												</td>

												<td>
													<?php if ($result->xphat && $result->ve) { ?>
														<?php echo date("d/m", strtotime($result->xphat)); ?> - <?php echo date("d/m/Y", strtotime($result->ve)); ?>
													<?php } else {
														echo "--";
													} ?>
												</td>

												<td><?php echo htmlentities($result->sphong); ?></td>
												<td><?php echo date("d/m/Y", strtotime($result->ngaydat)); ?></td>

												<td>
													<?php if ($result->status == 0) {
														echo "<span style='color:red'>Chờ xử lý</span>";
													} else if ($result->status == 1) {
														echo "<span style='color:green'>Đã xác nhận</span>";
													} else if ($result->status == 2) {
														echo "<span style='color:gray'>Đã hủy</span>";
													} ?>
												</td>

												<td>
													<?php if ($result->status == 2) { ?>
														<a href="delete-bookings-hotel.php?bkid=<?php echo htmlentities($result->bookid); ?>" onclick="return confirm('Bạn có chắc chắn xóa đơn này vĩnh viễn?')" class="btn btn-danger btn-xs">Xóa</a>
													<?php } else { ?>
														<a href="manage-bookings-hotel.php?bkid=<?php echo htmlentities($result->bookid); ?>" onclick="return confirm('Bạn có chắc chắn hủy đơn này?')" class="btn btn-warning btn-xs">Hủy</a>
														<?php if ($result->status == 0) { ?>
															<a href="manage-bookings-hotel.php?bckid=<?php echo htmlentities($result->bookid); ?>" onclick="return confirm('Xác nhận đơn hàng này?')" class="btn btn-success btn-xs">Duyệt</a>
														<?php } ?>
													<?php } ?>
												</td>
											</tr>
									<?php }
									} ?>
								</tbody>
							</table>
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
		</script>
		<script src="js/jquery.nicescroll.js"></script>
		<script src="js/scripts.js"></script>
	</body>

	</html>
<?php } ?>