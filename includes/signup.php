<?php
// --- BẬT BÁO LỖI ĐỂ KIỂM TRA ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

// Kiểm tra file tồn tại trước khi require
if (!file_exists('includes/PHPMailer/src/Exception.php')) {
	die("Lỗi: Không tìm thấy thư viện PHPMailer. Hãy kiểm tra lại đường dẫn folder 'includes/PHPMailer/src/'");
}

require 'includes/PHPMailer/src/Exception.php';
require 'includes/PHPMailer/src/PHPMailer.php';
require 'includes/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
	$fname = $_POST['fname'];
	$mnumber = $_POST['mobilenumber'];
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	$token = bin2hex(random_bytes(16));
	$status = 0;

	$sql_check = "SELECT id_email FROM nguoidung WHERE id_email=:email";
	$query_check = $dbh->prepare($sql_check);
	$query_check->bindParam(':email', $email, PDO::PARAM_STR);
	$query_check->execute();

	if ($query_check->rowCount() > 0) {
		echo "<script>alert('Email này đã được đăng ký!');</script>";
	} else {
		$sql = "INSERT INTO nguoidung(hoten,sdt_nd,id_email,matkhau,token,trangthai) VALUES(:fname,:mnumber,:email,:password,:token,:status)";
		$query = $dbh->prepare($sql);
		$query->bindParam(':fname', $fname, PDO::PARAM_STR);
		$query->bindParam(':mnumber', $mnumber, PDO::PARAM_STR);
		$query->bindParam(':email', $email, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);
		$query->bindParam(':token', $token, PDO::PARAM_STR);
		$query->bindParam(':status', $status, PDO::PARAM_INT);

		if ($query->execute()) {
			$mail = new PHPMailer(true);
			try {
				$mail->SMTPDebug = 2;

				$mail->isSMTP();
				$mail->Host       = 'smtp.gmail.com';
				$mail->SMTPAuth   = true;
				$mail->Username   = 'thinhphuc2704@gmail.com';
				$mail->Password   = 'frjzzwfldpafjagy';
				$mail->SMTPSecure = 'tls';
				$mail->Port       = 587;

				$mail->setFrom('thinhphuc2704@gmail.com', 'Viet Nam Tour');
				$mail->addAddress($email, $fname);

				$mail->isHTML(true);
				$mail->Subject = 'Kich hoat tai khoan - Viet Nam Tour';

				$base_url = "http://localhost/DuLichTour/DuLichTour/";
				$verify_link = $base_url . "verify.php?email=" . $email . "&token=" . $token;

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
                            <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:600;">✨ Chào Mừng Đến Với Việt Nam Tour!</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding:40px 30px;">
                            <p style="margin:0 0 20px;color:#333;font-size:16px;line-height:1.6;">
                                Xin chào <strong style="color:#faa61a;">' . $fname . '</strong>,
                            </p>
                            
                            <p style="margin:0 0 25px;color:#555;font-size:15px;line-height:1.6;">
                                Cảm ơn bạn đã đăng ký tài khoản tại <strong style="color:#faa61a;">Việt Nam Tour</strong>! 
                                Chúng tôi rất vui mừng được đồng hành cùng bạn trong những chuyến du lịch khám phá Việt Nam.
                            </p>
                            
                            <p style="margin:0 0 25px;color:#555;font-size:15px;line-height:1.6;">
                                Để hoàn tất quá trình đăng ký và bắt đầu trải nghiệm, vui lòng kích hoạt tài khoản của bạn bằng cách nhấn vào nút bên dưới:
                            </p>
                            
                            <div style="text-align:center;margin:35px 0;">
                                <a href="' . $verify_link . '" style="display:inline-block;background:linear-gradient(135deg,#faa61a 0%,#f7941d 100%);color:#ffffff;text-decoration:none;padding:16px 45px;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(250,166,26,0.3);">
                                    🔓 Kích Hoạt Tài Khoản
                                </a>
                            </div>
                            
                            <div style="background:#f8f9fa;border-radius:6px;padding:20px;margin:30px 0;">
                                <p style="margin:0 0 10px;color:#666;font-size:13px;">
                                    Hoặc sao chép và dán liên kết sau vào trình duyệt:
                                </p>
                                <p style="margin:0;word-break:break-all;">
                                    <a href="' . $verify_link . '" style="color:#faa61a;text-decoration:none;font-size:13px;">' . $verify_link . '</a>
                                </p>
                            </div>
                            
                            <div style="background:#e3f2fd;border-left:4px solid #2196f3;padding:15px 20px;margin:25px 0;border-radius:4px;">
                                <p style="margin:0;color:#1565c0;font-size:14px;line-height:1.5;">
                                    <strong>ℹ️ Lưu ý:</strong><br>
                                    Liên kết kích hoạt này có hiệu lực trong vòng 24 giờ. Nếu quá thời gian, bạn có thể yêu cầu gửi lại email kích hoạt.
                                </p>
                            </div>
                            
                            <p style="margin:25px 0 0;color:#777;font-size:14px;line-height:1.6;">
                                Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi để được hỗ trợ.
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
				// $_SESSION['mng'] = "Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.";
				// header('Location: thankyou.php');
				echo "<script>
				alert('Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.');
				</script>";
				exit();
			} catch (Exception $e) {
				echo "Lỗi gửi mail: " . $mail->ErrorInfo;
			}
		} else {
			echo "Lỗi Insert CSDL: ";
			print_r($query->errorInfo());
		}
	}
}
?>

<script>
	function checkAvailability() {
		$("#loaderIcon").show();
		jQuery.ajax({
			url: "check_availability.php",
			data: 'emailid=' + $("#email").val(),
			type: "POST",
			success: function(data) {
				$("#user-availability-status").html(data);
				$("#loaderIcon").hide();
			},
			error: function() {}
		});
	}
</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<section>
				<div class="modal-body modal-spa">
					<div class="login-grids">
						<div class="login">
							<div class="login-left">
								<ul style="list-style: none; margin-left: -18px;">
									<li><a class="fb" href="#"><i></i>Facebook</a></li>
									<li><a class="goog" href="#"><i></i>Google</a></li>
								</ul>
							</div>
							<div class="login-right">
								<form name="signup" method="post">
									<h3>Tạo tài khoản </h3>
									<input type="text" value="" placeholder="Họ tên" name="fname" autocomplete="off" required="">
									<input type="text" value="" placeholder="Số điện thoại" maxlength="10" name="mobilenumber" autocomplete="off" required="">
									<input type="text" value="" placeholder="Email" name="email" id="email" onBlur="checkAvailability()" autocomplete="off" required="">
									<span id="user-availability-status" style="font-size:12px;"></span>
									<input type="password" value="" placeholder="Mật khẩu" name="password" required="">
									<input type="submit" name="submit" id="submit" value="Đăng ký">
								</form>
							</div>
							<div class="clearfix"></div>
						</div>
						<p style="margin-top: 15px; margin-bottom: -10px;">Bằng cách đăng ký, bạn đồng ý với chúng tôi <a href="page.php?type=terms">điều khoản </a> và <a href="page.php?type=privacy">bảo mật</a></p>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>