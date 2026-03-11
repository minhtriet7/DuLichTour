<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	// --- XỬ LÝ ĐÁNH DẤU ĐÃ ĐỌC ---
	if (isset($_REQUEST['eid'])) {
		$eid = intval($_GET['eid']);
		$status = 1;
		$sql = "UPDATE gopy SET trangthai=:status WHERE id_gopy=:eid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':eid', $eid, PDO::PARAM_STR);
		$query->execute();
		$msg = "Đã đánh dấu là Đã đọc";
	}
?>
	<!DOCTYPE HTML>
	<html>

	<head>
		<title>Quản lý Góp ý</title>
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

			.status-badge {
				padding: 5px 10px;
				border-radius: 4px;
				color: #fff;
				font-size: 12px;
				font-weight: bold;
			}

			.badge-read {
				background-color: #5cb85c;
			}

			/* Màu xanh */
			.badge-unread {
				background-color: #d9534f;
			}

			/* Màu đỏ */
			.action-btn {
				margin: 0 2px;
			}

			/* Modal text wrapping */
			.modal-body p {
				word-wrap: break-word;
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
					<li class="breadcrumb-item"><a href="index.php">Home</a><i class="fa fa-angle-right"></i><span style="color: #1b93e1">Quản lý góp ý</span></li>
				</ol>

				<div class="agile-grids">
					<?php if ($error) { ?><div class="errorWrap"><strong>Lỗi</strong>: <?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>Hoàn thành</strong>: <?php echo htmlentities($msg); ?> </div><?php } ?>

					<div class="agile-tables">
						<div class="w3l-table-info">
							<h2>Danh sách phản hồi từ khách hàng</h2>
							<table id="table">
								<thead>
									<tr>
										<th style="background-color: #1b93e1;">#</th>
										<th style="background-color: #1b93e1;">Người gửi</th>
										<th style="background-color: #1b93e1;">Thông tin liên hệ</th>
										<th style="background-color: #1b93e1;">Chủ đề</th>
										<th style="background-color: #1b93e1;" style="width: 30%;">Nội dung (Tóm tắt)</th>
										<th style="background-color: #1b93e1;">Ngày gửi</th>
										<th style="background-color: #1b93e1;">Trạng thái</th>
										<th style="background-color: #1b93e1;">Hành động</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Sắp xếp mới nhất lên đầu
									$sql = "SELECT * from gopy ORDER BY id_gopy DESC";
									$query = $dbh->prepare($sql);
									$query->execute();
									$results = $query->fetchAll(PDO::FETCH_OBJ);
									$cnt = 1;

									if ($query->rowCount() > 0) {
										foreach ($results as $result) { ?>
											<tr>
												<td><?php echo htmlentities($cnt); ?></td>
												<td><?php echo htmlentities($result->hoten); ?></td>
												<td>
													<i class="fa fa-envelope"></i> <?php echo htmlentities($result->id_email); ?><br>
													<i class="fa fa-phone"></i> <?php echo htmlentities($result->sdt); ?>
												</td>
												<td><?php echo htmlentities($result->chude); ?></td>

												<td>
													<?php
													$content = htmlentities($result->noidung);
													if (strlen($content) > 50) {
														echo substr($content, 0, 50) . "...";
													} else {
														echo $content;
													}
													?>
												</td>

												<td><?php echo date("d/m/Y", strtotime($result->ngaygui)); ?></td>

												<td>
													<?php if ($result->trangthai == 1) { ?>
														<span class="status-badge badge-read">Đã đọc</span>
													<?php } else { ?>
														<span class="status-badge badge-unread">Chưa đọc</span>
													<?php } ?>
												</td>

												<td>
													<button type="button" class="btn btn-info btn-xs action-btn" data-toggle="modal" data-target="#viewModal<?php echo $result->id_gopy; ?>" title="Xem chi tiết">
														<i class="fa fa-eye" style="color: white"></i>
													</button>

													<?php if ($result->trangthai == 0) { ?>
														<a href="manage-enquires.php?eid=<?php echo htmlentities($result->id_gopy); ?>" onclick="return confirm('Đánh dấu tin này là đã đọc?')" class="btn btn-success btn-xs action-btn" title="Đánh dấu đã đọc">
															<i class="fa fa-check" style="color: white"></i>
														</a>
													<?php } ?>

													<a href="delete-enquiry.php?id=<?php echo htmlentities($result->id_gopy); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa góp ý này?')" class="btn btn-danger btn-xs action-btn" title="Xóa">
														<i class="fa fa-trash" style="color: white"></i>
													</a>

													<div id="viewModal<?php echo $result->id_gopy; ?>" class="modal fade" role="dialog">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title" style="color:#1B93E1;">Chi tiết góp ý #<?php echo $result->id_gopy; ?></h4>
																</div>
																<div class="modal-body">
																	<p><strong>Người gửi:</strong> <?php echo htmlentities($result->hoten); ?></p>
																	<p><strong>Email:</strong> <?php echo htmlentities($result->id_email); ?></p>
																	<p><strong>SĐT:</strong> <?php echo htmlentities($result->sdt); ?></p>
																	<p><strong>Ngày gửi:</strong> <?php echo date("d/m/Y H:i:s", strtotime($result->ngaygui)); ?></p>
																	<hr>
																	<p><strong>Chủ đề:</strong> <?php echo htmlentities($result->chude); ?></p>
																	<p><strong>Nội dung:</strong></p>
																	<div style="background: #f5f5f5; padding: 10px; border-radius: 5px;">
																		<?php echo nl2br(htmlentities($result->noidung)); ?>
																	</div>
																</div>
																<div class="modal-footer">
																	<?php if ($result->trangthai == 0) { ?>
																		<a href="manage-enquires.php?eid=<?php echo htmlentities($result->id_gopy); ?>" class="btn btn-success">Đánh dấu đã đọc</a>
																	<?php } ?>
																	<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
																</div>
															</div>
														</div>
													</div>
												</td>
											</tr>
									<?php $cnt++;
										}
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