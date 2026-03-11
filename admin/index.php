  <?php
	session_start();
	include('includes/config.php');
	if (isset($_SESSION['alogin']) && $_SESSION['alogin'] != '') {
		echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
		exit();
	}
	if (isset($_POST['login'])) {
		$uname = $_POST['username'];
		$password = md5($_POST['password']);
		$sql = "SELECT UserName,Password FROM admin WHERE UserName=:uname and Password=:password";
		$query = $dbh->prepare($sql);
		$query->bindParam(':uname', $uname, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_OBJ);
		if ($query->rowCount() > 0) {
			$_SESSION['alogin'] = $_POST['username'];
			echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
		} else {
			echo "<script>alert('Invalid Details');</script>";
		}
	}

	?>

  <!DOCTYPE HTML>
  <html>

  <head>
  	<title>Trang quản trị</title>
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
  	<link rel="stylesheet" href="css/jquery-ui.css">
  	<script src="js/jquery-2.1.4.min.js"></script>
  	<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css' />
  	<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  	<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
  	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  </head>

  <body>
  	<div class="main-wthree">
  		<div class="container">
  			<div class="sin-w3-agile">
  				<h2>Đăng nhập</h2>
  				<form method="post">
  					<div class="username">
  						<span class="username">Tài khoản:</span>
  						<input type="text" name="username" class="name" placeholder="" required="">
  						<div class="clearfix"></div>
  					</div>
  					<div class="password-agileits">
  						<span class="username">Mật khẩu:</span>
  						<input type="password" name="password" class="password" placeholder="" required="">
  						<div class="clearfix"></div>
  					</div>

  					<div class="login-w3">
  						<input type="submit" class="login" name="login" value="Sign In">
  					</div>
  					<div class="clearfix"></div>
  				</form>
  				<div class="back">
  					<a href="../index.php">Trở lại</a>
  				</div>

  			</div>
  		</div>
  	</div>

  	<style>
  		.main-wthree {
  			background-color: whitesmoke !important;
  		}

  		.back a {
  			width: 150px !important;
  			background-color: #005377 !important;
  			height: 47px !important;
  			color: whitesmoke !important;
  			line-height: 30px !important;
  			text-decoration: none !important;
  			position: relative !important;
  			top: -80px !important;
  		}

  		.back a:hover {
  			background-color: #e74c3c !important;
  		}

  		.main-wthree h2 {
  			color: #005377 !important;
  		}

  		.main-wthree form span {
  			background-color: #005377 !important;
  		}
  	</style>
  </body>

  </html>