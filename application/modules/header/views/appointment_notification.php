<?
require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject; 
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseUser;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);
?>

<a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-flag-o"></i>
        <? if(count($schedules)>0) { ?>
                <span class="label label-warning"><?=count($schedules)?></span>
        <? }?>
</a>
<ul class="dropdown-menu">
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
                                                        var_dump($datetime);
                                                        exit();
                                                        $zone= $this->session->userdata('sess_user_zone');
                                                        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
                                                        $tz=new DateTimeZone($zone);
                                                        $heredate= date_timezone_set($datetime, $tz);
                                                        $showdate = $heredate->format("m/d/Y h:i A");
                                                        echo $showdate; 
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