<!DOCTYPE html>
<html>
    <head>
            <meta charset="UTF-8">
            <title><?=$page_title?></title>
            <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <link href="<?=base_url()?>src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
            <link href="<?=base_url()?>src/dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
            <link href="<?=base_url()?>src/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
    </head>
    <body style="background-image:url('<?=base_url()?>src/dist/img/login-back.jpg');background-repeat: no-repeat;background-size:cover">
            <div class="login-box">
                    <div class="login-logo">
                            <div style="height:120px;"></div>
                    </div><!-- /.login-logo -->
                    <div class="login-box-body" style="background:transparent;">
                            <p class="login-box-msg">Sign in to start your session</p>

                            <form action="<?php echo base_url()?>login/do_login" method="post">
                                    <div class="form-group has-feedback">
                                            <input type="email" name="email" class="form-control" placeholder="Email" required />
                                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                    </div>
                                    <div class="form-group has-feedback">
                                            <input type="password" name="password" class="form-control" placeholder="Password" required />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                    </div>
                                    <div class="row">
                                            <div class="col-xs-8">
                                            </div><!-- /.col -->
                                            <div class="col-xs-12">
                                                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                                            </div><!-- /.col -->
                                    </div>
                            </form>

                            <a href="<?=base_url()?>login/forgetpassword">I forgot my password</a><br>
                    </div>
            </div><!-- /.login-box -->
            <script src="<?=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
            <script src="<?=base_url()?>src/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
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
