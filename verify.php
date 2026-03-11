<?php
session_start();
include('includes/config.php');

if (isset($_GET['email']) && isset($_GET['token'])) {
  $email = $_GET['email'];
  $token = $_GET['token'];

  // 1. Kiểm tra Email và Token có khớp trong DB không, và tài khoản chưa kích hoạt (trangthai=0)
  $sql = "SELECT id_email FROM nguoidung WHERE id_email=:email AND token=:token AND trangthai=0";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':token', $token, PDO::PARAM_STR);
  $query->execute();

  if ($query->rowCount() > 0) {
    // 2. Nếu đúng -> Kích hoạt tài khoản (trangthai=1) và xóa token (để link không dùng lại được)
    $sql_update = "UPDATE nguoidung SET trangthai=1, token='' WHERE id_email=:email";
    $query_update = $dbh->prepare($sql_update);
    $query_update->bindParam(':email', $email, PDO::PARAM_STR);

    if ($query_update->execute()) {
      $msg = "Kích hoạt tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.";
    } else {
      $msg = "Lỗi hệ thống. Vui lòng liên hệ Admin.";
    }
  } else {
    // Trường hợp link sai, hết hạn hoặc đã kích hoạt rồi
    $msg = "Link kích hoạt không hợp lệ hoặc tài khoản đã được kích hoạt trước đó.";
  }
} else {
  header('location:index.php');
  exit();
}
?>

<!DOCTYPE HTML>
<html>

<head>
  <title>Xác thực tài khoản</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
  <link href="css/style.css" rel='stylesheet' type='text/css' />
  <style>
    .verify-box {
      width: 50%;
      margin: 100px auto;
      background: #fff;
      padding: 30px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      text-align: center;
      border-radius: 5px;
    }

    .btn-home {
      background-color: #34ad00;
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 4px;
    }

    .btn-home:hover {
      color: #fff;
      background-color: #2c9200;
    }
  </style>
</head>

<body>
  <?php include('includes/header.php'); ?>

  <div class="container">
    <div class="verify-box">
      <h2 style="color: #34ad00; margin-bottom: 20px;">Thông báo xác thực</h2>
      <p style="font-size: 16px; margin-bottom: 30px;"><?php echo htmlentities($msg); ?></p>
      <a href="index.php" class="btn-home">Về trang chủ & Đăng nhập</a>
    </div>
  </div>

  <?php include('includes/footer.php'); ?>
</body>

</html>