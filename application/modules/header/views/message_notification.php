                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                                    <i class="fa fa-envelope-o"></i>
                                                                    <? if(count($messages)>0) { ?>
                                                                            <span class="label label-danger"><?=count($messages)?></span>
                                                                    <? }?>
                                                            </a>
                                                            <ul class="dropdown-menu">
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