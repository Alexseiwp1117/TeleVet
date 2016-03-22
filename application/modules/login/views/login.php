<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Televet | Log In</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.4 -->
		<link href="<?=base_url()?>src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<!-- Font Awesome Icons -->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<!-- Theme style -->
		<link href="<?=base_url()?>src/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
				<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
				<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body style="background-image:url('<?=base_url()?>src/dist/img/login-back.jpg');background-repeat: no-repeat;background-size:cover">
		<!-- Automatic element centering -->
		<div class="lockscreen-wrapper">
			<div class="lockscreen-logo">
				<div style="height:120px;"></div>
			</div>
			<!-- User name -->

			<div class="lockscreen-name"><?=$user->get('firstName').' '.$user->get('lastName')?></div>

			<!-- START LOCK SCREEN ITEM -->
			<div class="lockscreen-item">
				<!-- lockscreen image -->
				<div class="lockscreen-image">
					<img src="<?$photo=$user->get('photo'); if($photo){ echo str_replace("http://", "//", $photo->getURL()); } else { echo base_url()."src/dist/img/nophoto.png";}?>" alt="User Image" />
				</div>
				<!-- /.lockscreen-image -->

				<!-- lockscreen credentials (contains the form) -->
				<form class="lockscreen-credentials" action="<?php echo base_url()?>login/do_login" method="post" >
					<div class="input-group">
						<input type="hidden" name="email" value="<?=$email?>">
						<input type="password" class="form-control" name="password" placeholder="Password" />
						<div class="input-group-btn">
							<button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
						</div>
					</div>
				</form><!-- /.lockscreen credentials -->

			</div><!-- /.lockscreen-item -->
			<div class="help-block text-center">
				Enter your password to retrieve your session
			</div>
			<div class="text-center">
				<a href="<?=base_url()?>login/resetCookie">Or sign in as a different user</a>
			</div>
		<br/>
		<div class="text-center">
				<a href="<?=base_url()?>login/forgetpassword">Forgot Password</a>
			</div>

			<!-- <div class="lockscreen-footer text-center">
				Copyright &copy; 2014-2015 <b><a href="http://almsaeedstudio.com" class="text-black">Almsaeed Studio</a></b><br/>
				All rights reserved
			</div> -->
		</div><!-- /.center -->

		<!-- jQuery 2.1.4 -->
		<script src="<?=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
		<!-- Bootstrap 3.3.2 JS -->
		<script src="<?=base_url()?>src/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	</body>
</html>
