                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                                    <i class="fa fa-plus-square"></i>
                                                                    <? if(count($news)>0){ ?>
                                                                            <span class="label label-success"><?=count($news)?></span>
                                                                    <? }?>
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                    <li class="header" id="casenotice">You have <?=count($news)?> new cases</li>
                                                                    <li>
                                                                            <!-- inner menu: contains the actual data -->
                                                                            <ul class="menu">
                                                                                    <? foreach($news as $new)
                                                                                    {   
                                                                                            $ownerid= $new->get('owner');
                                                                                            $query= ParseUser::Query();
                                                                                            var_dump($query);
                                                                                            exit('1');
                                                                                            $owner = $query->get($ownerid);
                                                                                            $ownerphoto = $owner->get('photo');
                                                                                            
                                                                                    ?>

                                                                                            <li><!-- start message -->
                                                                                                    <a href="<?=base_url()?>dashboard">
                                                                                                            <div class="pull-left">
                                                                                                                    <? if($ownerphoto) { ?>
                                                                                                                            <img src="<?php echo str_replace("http://", "//", $ownerphoto->getURL());?>" class="img-circle" alt="" />
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