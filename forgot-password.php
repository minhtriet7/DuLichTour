<?php
session_start();
error_reporting(0);
include('includes/config.php');

// --- 1. KIỂM TRA ĐĂNG NHẬP ---
// Nếu đã đăng nhập thì không cho vào trang quên mật khẩu, đẩy về trang chủ
if (isset($_SESSION['login']) && strlen($_SESSION['login']) > 0) {
	header("Location: index.php");
	exit();
}

// Yêu cầu thư viện PHPMailer (Bạn cần tải thư mục PHPMailer để vào đường dẫn này)
require 'includes/PHPMailer/src/Exception.php';
require 'includes/PHPMailer/src/PHPMailer.php';
require 'includes/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit50'])) {
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];

	// Kiểm tra thông tin trong CSDL
	$sql = "SELECT id_email FROM nguoidung WHERE id_email=:email and sdt_nd=:mobile";
	$query = $dbh->prepare($sql);
	$query->bindParam(':email', $email, PDO::PARAM_STR);
	$query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
	$query->execute();

	if ($query->rowCount() > 0) {
		// --- 2. LOGIC MỚI: SINH MẬT KHẨU NGẪU NHIÊN VÀ GỬI MAIL ---

		// Tạo mật khẩu ngẫu nhiên 8 ký tự (gồm số và chữ)
		$random_password = substr(md5(mt_rand()), 0, 8);
		$hashed_password = md5($random_password); // Mã hóa để lưu vào DB

		// Cập nhật mật khẩu mới vào Database
		$con = "UPDATE nguoidung SET matkhau=:newpassword WHERE id_email=:email";
		$chngpwd1 = $dbh->prepare($con);
		$chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
		$chngpwd1->bindParam(':newpassword', $hashed_password, PDO::PARAM_STR);

		if ($chngpwd1->execute()) {
			$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'thinhphuc2704@gmail.com'; // Email của bạn
				$mail->Password = 'frjzzwfldpafjagy';    // Mật khẩu ứng dụng
				$mail->SMTPSecure = 'tls';
				$mail->Port = 587;

				$mail->setFrom('thinhphuc2704@gmail.com', 'Viet Nam Tour');
				$mail->addAddress($email);

				$mail->isHTML(true);
				$mail->Subject = 'Khoi phuc mat khau - Viet Nam Tour';
				$mail->Body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#f4f7fa;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f7fa;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);overflow:hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#faa61a 0%,#f7941d 100%);padding:40px 30px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:600;">🔐 Khôi Phục Mật Khẩu</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding:40px 30px;">
                            <p style="margin:0 0 20px;color:#333;font-size:16px;line-height:1.6;">
                                Xin chào,
                            </p>
                            
                            <p style="margin:0 0 25px;color:#555;font-size:15px;line-height:1.6;">
                                Bạn đã yêu cầu khôi phục mật khẩu cho tài khoản tại <strong style="color:#faa61a;">Việt Nam Tour</strong>. 
                                Chúng tôi đã tạo một mật khẩu tạm thời cho bạn.
                            </p>
                            
                            <div style="background:#f8f9fa;border-radius:6px;padding:25px;margin:25px 0;text-align:center;">
                                <p style="margin:0 0 12px;color:#666;font-size:14px;text-transform:uppercase;letter-spacing:0.5px;">
                                    Mật khẩu tạm thời
                                </p>
                                <div style="background:#ffffff;border:2px dashed #faa61a;border-radius:4px;padding:16px 20px;display:inline-block;">
                                    <code style="font-size:24px;font-weight:700;color:#333;letter-spacing:2px;">' . $random_password . '</code>
                                </div>
                            </div>
                            
                            <div style="background:#fff3e0;border-left:4px solid #faa61a;padding:15px 20px;margin:25px 0;border-radius:4px;">
                                <p style="margin:0;color:#e65100;font-size:14px;line-height:1.5;">
                                    <strong>⚠️ Lưu ý quan trọng:</strong><br>
                                    Vui lòng đăng nhập và đổi mật khẩu ngay để bảo mật tài khoản của bạn.
                                </p>
                            </div>
                            
                            <p style="margin:25px 0 0;color:#777;font-size:14px;line-height:1.6;">
                                Nếu bạn không yêu cầu khôi phục mật khẩu, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi ngay lập tức.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8f9fa;padding:30px;text-align:center;border-top:1px solid #e0e0e0;">
                            <p style="margin:0 0 10px;color:#999;font-size:13px;">
                                Email này được gửi tự động, vui lòng không trả lời.
                            </p>
                            <p style="margin:0 0 15px;color:#666;font-size:14px;font-weight:600;">
                                🌏 Hệ thống Việt Nam Tour
                            </p>
                            <p style="margin:0;color:#999;font-size:12px;">
                                © 2026 Việt Nam Tour. All rights reserved.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
';

				$mail->send();
				$msg = "Mật khẩu mới đã được gửi về Email của bạn. Vui lòng kiểm tra hộp thư.";
			} catch (Exception $e) {
				$error = "Không thể gửi mail. Lỗi: {$mail->ErrorInfo}";
			}


			// --- NẾU KHÔNG DÙNG ĐƯỢC MAIL ---
			// Tạm thời hiển thị mật khẩu ra màn hình (Chỉ dùng cho môi trường Test/Localhost)
			// $msg = "Thành công! Mật khẩu mới tạm thời của bạn là: " . $random_password;

		} else {
			$error = "Đã xảy ra lỗi hệ thống.";
		}
	} else {
		$error = "Email hoặc số điện thoại không chính xác.";
	}
}
?>
<!DOCTYPE HTML>
<html>

<head>
	<title>Khôi phục mật khẩu - Starvel</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<link href="css/font-awesome.css" rel="stylesheet">
	<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/wow.min.js"></script>
	<script>
		new WOW().init();
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

		.form-container {
			max-width: 500px;
			margin: 0 auto;
			background: #fff;
			padding: 30px;
			border-radius: 5px;
			box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
		}
	</style>
</head>

<body>
	<?php include('includes/header.php'); ?>

	<div class="privacy">
		<div class="container">
			<div class="form-container wow fadeInDown animated" data-wow-delay=".5s">
				<h3 class="text-center" style="margin-bottom: 20px; color: #faa61a;">Lấy lại mật khẩu</h3>

				<form name="chngpwd" method="post">

					<?php if ($error) { ?>
						<div class="errorWrap"><strong>Lỗi</strong>: <?php echo htmlentities($error); ?> </div>
					<?php } else if ($msg) { ?>
						<div class="succWrap"><strong>Thành công</strong>: <?php echo htmlentities($msg); ?> </div>
					<?php } ?>

					<div class="form-group">
						<label>Email đăng ký</label>
						<input type="email" name="email" class="form-control" placeholder="Nhập email của bạn" required>
					</div>

					<div class="form-group">
						<label>Số điện thoại xác thực</label>
						<input type="text" name="mobile" class="form-control" placeholder="Nhập số điện thoại" required>
					</div>

					<div class="form-group text-center">
						<button type="submit" name="submit50" class="btn btn-primary btn-block" style="background-color: #faa61a; border: none; padding: 10px; font-weight:bold;">Gửi mật khẩu mới qua Email</button>
					</div>

					<div class="text-center" style="margin-top: 15px;">
						<a href="index.php" style="color: #666;"><i class="fa fa-arrow-left"></i> Quay lại trang chủ</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php include('includes/footer.php'); ?>
	<?php include('includes/signup.php'); ?>
	<?php include('includes/signin.php'); ?>
	<?php include('includes/write-us.php'); ?>
</body>

</html>