<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>TeleVet | Vet</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.4 -->
		<link href="<?=base_url()?>src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<!-- Font Awesome Icons -->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<!-- Theme style -->
		<link href="<?=base_url()?>src/dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
		<!-- iCheck -->
		<link href="<?=base_url()?>src/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
				<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
				<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body style="background-image:url('<?=base_url()?>src/dist/img/login-back.jpg');background-repeat: no-repeat;background-size:cover">
		<div class="login-box">
			<div class="login-logo">
				<div style="height:120px;"></div>
			</div><!-- /.login-logo -->
			<div class="login-box-body" style="background:transparent;">
				<p class="login-box-msg">Input your email to get new password.</p>
				<form action="<?php echo base_url()?>login/formatpassword" method="post">
					<div class="form-group has-feedback">
					 <input type="email" name="email" class="form-control" placeholder="Email" required />
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-primary btn-block btn-flat">New Password</button>
						</div><!-- /.col -->
					</div>
				</form>

			</div>

		</div><!-- /.login-box -->

		<!-- jQuery 2.1.4 -->
		<script src="<?=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
		<!-- Bootstrap 3.3.2 JS -->
		<script src="<?=base_url()?>src/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<!-- iCheck -->
		<script src="<?=base_url()?>src/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
		<script>
			$(function () {
				$('input').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
			});
		</script>
	</body>
</html>
