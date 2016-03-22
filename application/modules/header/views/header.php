<!DOCTYPE html>
<html>
    <head>
            <meta charset="UTF-8">
            <title><?=$page_title?></title>
            <!-- Tell the browser to be responsive to screen width -->
            <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <!-- Bootstrap 3.3.4 -->
            <link href="<?=base_url()?>src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
             <!-- Font Awesome Icons -->
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
            <!-- Ionicons -->
            <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
            <!-- jvectormap -->
            <link href="<?=base_url()?>src/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
            <!-- DATA TABLES -->
            <link href="<?=base_url()?>src/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
            <!-- Date Time picker -->
            <link href="<?=base_url()?>src/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
            <!-- iCheck -->
            <link href="<?=base_url()?>src/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
            <!-- FancyBox -->
            <link href="<?=base_url()?>src/dist/css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
            <!-- Select2 -->
            <link href="<?=base_url()?>src/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
            <!-- Theme style -->
            <link href="<?=base_url()?>src/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
            <link href="<?=base_url()?>src/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
            <!-- AdminLTE Skins. Choose a skin from the css/skins
                             folder instead of downloading all of them to reduce the load. -->
            <!-- jquery-ui -->
            <!--link src="<?//=base_url()?>package/js/jquery-ui/jquery-ui.css"></link-->

            <script src="<?=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
            <script>
                    var base_url="<?=base_url()?>";
            </script>
            <style type="text/css">
                    /* Paste this css to your style sheet file or under head tag */
                    /* This only works with JavaScript, 
                    if it's not present, don't show loader */
                    .no-js #loader { display: none;  }
                    .js #loader { display: block; position: absolute; left: 100px; top: 0; }
                    .se-pre-con {
                            position: fixed;
                            left: 0px;
                            top: 0px;
                            width: 100%;
                            height: 100%;
                            z-index: 9999;
                            background: url(<?=base_url()?>src/dist/img/Preloader_2.gif) center no-repeat rgba(0,0,0,0.3);
                            background-size: 50px 50px;
                    }
                    
                    #tel_jwplayer:hover {
                            cursor: pointer;
                    }
            </style>
            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
                            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
            <script type="text/javascript" src="<?=base_url()?>package/cordova.js"></script>
            <script type="text/javascript" src="<?=base_url()?>src/dist/js/socket.io.js"></script>

            <script type="text/javascript">

                    var socket = io.connect('https://52.34.172.221:3300/'),
                    session, username, callingTo, duplicateMessages = [];
                    username='<?=$this->session->userdata("sess_user_id")?>';

                    socket.emit('connecting',username);
                    socket.on('noticeReceived', onNoticeReceived);

                    function onNoticeReceived(name, message){
                            switch (message.type){
                                    case 'newCase':
                                            $("#new_case_notification").load('<?=base_url();?>header/do_caseNotification');
                                            break;
                                    case 'resolved':
                                            $("#new_appointments_notification").load('<?=base_url();?>header/do_scheduleNotification');
                                            break;
                                    case 'closed':
                                            $("#unread_message_notification").load('<?=base_url();?>header/do_messageNotification');
                                            break;
                            }
                    }

            </script>

            <script type="text/javascript">
                    $(document).ready(function(){
                            setInterval(function(){
                                    /*$("#new_case_notification").load('<?=base_url();?>header/do_caseNotification');
                                    $("#new_appointments_notification").load('<?=base_url();?>header/do_appointmentNotification');
                                    $("#unread_message_notification").load('<?=base_url();?>header/do_messageNotification'); */
                            }, 3000);
                    });
            </script>
    </head>
    <?
    require './vendor/autoload.php';
    use Parse\ParseClient;
    use Parse\ParseObject;
    use Parse\ParseQuery;
    use Parse\ParseFile;
    use Parse\ParseUser;
    
    ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);
    ?>
    <body class="skin-blue sidebar-mini" onclick=''>
            <div class="se-pre-con"></div>
            <div class="wrapper">
                    <header class="main-header">

                            <!-- Logo -->
                            <a href="<?=base_url()?>" class="logo">
                                    <!-- mini logo for sidebar mini 50x50 pixels -->
                                    <span class="logo-mini"><img src="<?=base_url()."src/dist/img/logo.png"?>" style="width:50px;height:48px;"></span>
                                    <!-- logo for regular state and mobile devices -->
                                    <span class="logo-lg"><b>TeleVet</b></span>
                            </a>

                            <!-- Header Navbar: style can be found in header.less -->
                            <nav class="navbar navbar-static-top" role="navigation">
                                    <!-- Sidebar toggle button-->
                                    <a href="#" class="sidebar-toggle" data-togg le="offcanvas" role="button">
                                            <span class="sr-only">Toggle navigation</span>
                                    </a>
                                    <!-- Navbar Right Menu -->
                                    <ul class="nav navbar-nav" style="float: right;">
                                            <!-- Messages: style can be found in dropdown.less-->
                                            <li class="dropdown messages-menu" id="new_case_notification">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-plus-square"></i>
                                                            <? if(count($news)>0){ ?>
                                                                    <span class="label label-success"><?=count($news)?></span>
                                                            <? }?>
                                                    </a>
                                                    <ul class="dropdown-menu" style="left: -238px">
                                                            <li class="header" id="casenotice">You have <?=count($news)?> new cases</li>
                                                            <li>
                                                                    <!-- inner menu: contains the actual data -->
                                                                    <ul class="menu">
                                                                            <? foreach($news as $new)
                                                                            {
                                                                                    $ownerid= $new->get('owner');
                                                                                    $query= ParseUser::Query();
                                                                                    $owner = $query->get($ownerid);
                                                                                    $ownerphoto = $owner->get('photo');
                                                                            ?>

                                                                                    <li><!-- start message -->
                                                                                            <a href="<?=base_url()?>dashboard">
                                                                                                    <div class="pull-left">
                                                                                                            <? if($ownerphoto) { ?>
                                                                                                                    <img src="<?=str_replace("http://", "//", $ownerphoto->getURL());?>" class="img-circle" alt="" />
                                                                                                            <? } else { ?>
                                                                                                                    <img src="<?=base_url()?>src/dist/img/nophoto.png" class="img-circle" alt="" />
                                                                                                            <? } ?>
                                                                                                    </div>
                                                                                                    <h4>
                                                                                                            <?=$owner->get('firstName').' '.$owner->get('lastName')?>
                                                                                                            <small>
                                                                                                                    <i class="fa fa-clock-o"></i> 
                                                                                                                    <?
                                                                                                                    $datetime= $new->getCreatedAt();
                                                                                                                    $zone= $this->session->userdata('sess_user_zone');
                                                                                                                    $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
                                                                                                                    $tz=new DateTimeZone($zone);
                                                                                                                    $heredate= date_timezone_set($datetime, $tz);
                                                                                                                    echo $heredate->format("m/d/Y h:i A");
                                                                                                                    ?>
                                                                                                            </small>
                                                                                                    </h4>
                                                                                                    <p><?=$new->get('summary')?></p>
                                                                                            </a>
                                                                                    </li><!-- end message -->
                                                                            <? } ?>
                                                                    </ul>
                                                            </li>
                                                            <li class="footer"><a href="<?=base_url()?>dashboard">See All Cases</a></li>
                                                    </ul>
                                            </li>
                                            <!-- Notifications: style can be found in dropdown.less -->
                                            <li class="dropdown notifications-menu" id="new_appointments_notification">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-flag-o"></i>
                                                            <? if(count($schedules)>0) { ?>
                                                                    <span class="label label-warning"><?=count($schedules)?></span>
                                                            <? }?>
                                                    </a>
                                                    <ul class="dropdown-menu" style="left: -236px">
                                                            <li class="header" >You have <?=count($schedules)?> appointments today</li>
                                                            <li>
                                                                    <!-- inner menu: contains the actual data -->
                                                                    <ul class="menu">
                                                                            <? 
                                                                            foreach($schedules as $consult)
                                                                            {
                                                                                $ownerid= $consult->get('owner');
                                                                                $query= ParseUser::Query();
                                                                                $owner = $query->get($ownerid);
                                                                            ?>
                                                                                    <li>
                                                                                            <a href="<?=base_url()?>appointment/detail/<?=$consult->getObjectId();?>">
                                                                                                    <i class="fa fa-flag-o text-red"></i>
                                                                                                    <small class="pull-right" >
                                                                                                            <i class="fa fa-clock-o"></i>
                                                                                                            <? 
                                                                                                            $datetime= $consult->get('scheduledAt');
                                                                                                            if($datetime != NULL) {
                                                                                                                    $zone= $this->session->userdata('sess_user_zone');
                                                                                                                    $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
                                                                                                                    $tz=new DateTimeZone($zone);
                                                                                                                    $heredate= date_timezone_set($datetime, $tz);
                                                                                                                    $showdate = $heredate->format("m/d/Y h:i A");
                                                                                                                    echo $showdate; 
                                                                                                            }

                                                                                                            ?>
                                                                                                    </small>
                                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                    <?=$owner->get('firstName').' '.$owner->get('lastName')?>
                                                                                            </a>
                                                                                    </li>
                                                                            <? 
                                                                            } 
                                                                            ?>
                                                                    </ul>
                                                            </li>
                                                            <li class="footer"><a href="<?=base_url()?>appointment">View all</a></li>
                                                    </ul>
                                            </li>
                                            <!-- Tasks: style can be found in dropdown.less -->
                                            <li class="dropdown tasks-menu" id="unread_message_notification">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-envelope-o"></i>
                                                            <? if(count($messages)>0) { ?>
                                                                    <span class="label label-danger"><?=count($messages)?></span>
                                                            <? }?>
                                                    </a>
                                                    <ul class="dropdown-menu" style="left: -236px">
                                                            <li class="header">You have <?=count($messages)?> unread messages</li>
                                                            <li>
                                                                    <!-- inner menu: contains the actual data -->
                                                                    <ul class="menu" id="noticeMessage" >
                                                                            <? 
                                                                            foreach ($messages as $message){
                                                                                    $consultid = $message->get('consult');
                                                                                    $query = new ParseQuery('Consult');
                                                                                    $consult = $query->get($consultid);
                                                                                    $ownerid= $consult->get('owner');
                                                                                    $query= ParseUser::Query();
                                                                                    $owner = $query->get($ownerid);
                                                                                    ?>
                                                                                    <li><!-- Task item -->
                                                                                            <a href="<?=base_url()?>consult/detail/<?=$consultid?>">
                                                                                                    <h3>
                                                                                                            <?=$owner->get('firstName').' '.$owner->get('lastName')?>
                                                                                                            <small class="pull-right" ><i class="fa fa-clock-o"></i> 
                                                                                                                    <? 
                                                                                                                    $datetime= $message->getCreatedAt();
                                                                                                                    $zone= $this->session->userdata('sess_user_zone');
                                                                                                                    $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

                                                                                                                    $tz=new DateTimeZone($zone);
                                                                                                                    $heredate= date_timezone_set($datetime, $tz);
                                                                                                                    $showdate = $heredate->format("m/d/Y h:i A");

                                                                                                                    echo $showdate; 
                                                                                                                    ?>
                                                                                                            </small>
                                                                                                    </h3>
                                                                                                    <p>
                                                                                                            <? 
                                                                                                            if ($message->get('type') == "TEXT") 
                                                                                                            {
                                                                                                                    echo $message->get('detail'); 
                                                                                                            } 
                                                                                                            else { 
                                                                                                                    echo "New file received.";
                                                                                                            }
                                                                                                            ?>
                                                                                                    </p>
                                                                                            </a>
                                                                                    </li><!-- end task item -->
                                                                            <? } ?>
                                                                    </ul>
                                                            </li>
                                                            <li class="footer">
                                                                    <!-- <a href="#">View all messages</a> -->
                                                            </li>
                                                    </ul>
                                            </li>
                                            <!-- User Account: style can be found in dropdown.less -->
                                            <li class="dropdown user user-menu">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <img src="<?$photo=$user->get('photo'); if($photo){ echo str_replace("http://", "//", $photo->getURL()); } else { echo base_url()."src/dist/img/nophoto.png";} ?>" class="user-image" alt="User Image" />
                                                            <span class="hidden-xs"><?=$user->get('firstName')." ".$user->get('lastName')?></span>
                                                    </a>
                                                    <ul class="dropdown-menu" style="left: -115px;">
                                                            <!-- User image -->
                                                            <li class="user-header">
                                                                    <img src="<? $photo=$user->get('photo'); if($photo){ echo str_replace("http://", "//", $photo->getURL()); } else { echo base_url()."src/dist/img/nophoto.png";}?>" class="img-circle" alt="User Image" />
                                                                    <p>
                                                                            <?=$user->get('firstName')." ".$user->get('lastName')?>
                                                                            <!-- <small>Member since Nov. 2012</small> -->
                                                                    </p>
                                                            </li>
                                                            <!-- Menu Body -->
                                                            <!-- <li class="user-body">
                                                                    <div class="col-xs-4 text-center">
                                                                            <a href="#">Followers</a>
                                                                    </div>
                                                                    <div class="col-xs-4 text-center">
                                                                            <a href="#">Sales</a>
                                                                    </div>
                                                                    <div class="col-xs-4 text-center">
                                                                            <a href="#">Friends</a>
                                                                    </div>
                                                            </li> -->
                                                            <!-- Menu Footer-->
                                                            <li class="user-footer">
                                                                    <div class="pull-left">
                                                                            <a href="<?=base_url()?>setting" class="btn btn-default btn-flat">Setting</a>
                                                                    </div>
                                                                    <div class="pull-right">
                                                                            <a href="<?=base_url()?>login/logout" class="btn btn-default btn-flat">Sign out</a>
                                                                    </div>
                                                            </li>
                                                    </ul>
                                            </li>
                                    </ul>
                            </nav>
                    </header>

                    <!-- Left side column. contains the logo and sidebar -->
                    <aside class="main-sidebar">
                            <!-- sidebar: style can be found in sidebar.less -->
                            <section class="sidebar">
                                    <!-- sidebar menu: : style can be found in sidebar.less -->
                                    <ul class="sidebar-menu">
                                            <li class="<? if($this->uri->segment(1)=='dashboard') echo 'active'; ?>" >
                                                    <a href="<?=base_url()?>dashboard"><i class="fa fa-home"></i> <span> Home </span></a>
                                            </li>

                                            <li class=" <? if($this->uri->segment(1)=='appointment') echo 'active'; ?>">
                                                    <a href="<?=base_url()?>appointment"><i class="fa fa-calendar"></i> <span> Appointment </span></a>
                                            </li>

                                            <li class="<? if($this->uri->segment(1)=='record') echo 'active'; ?>" >
                                                    <a href="<?=base_url()?>record"><i class="fa fa-bars"></i> <span> Records </span></a>
                                            </li>
                                            <? if($user->get('head') == true) { ?>
                                                    <li class=" <? if($this->uri->segment(1)=='clinic') echo 'active'; ?>">
                                                            <a href="<?=base_url()?>clinic"><i class="fa fa-tachometer"></i> <span> Clinic Manager </span></a>
                                                    </li>
                                            <? } ?>
                                            <li class=" <? if($this->uri->segment(1)=='setting') echo 'active'; ?>">
                                                    <a href="<?=base_url()?>setting"><i class="fa fa-gears"></i> <span> Settings </span></a>
                                            </li>
                                    </ul>
                            </section>
                            <!-- /.sidebar -->
                    </aside>