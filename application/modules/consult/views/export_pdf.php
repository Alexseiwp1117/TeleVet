<!DOCTYPE html>
<html lang="en">
<head>
        <title>Export PDF</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style type="text/css">
                .container {
                        width: 100%;
                        height: 100%;
                        padding: 3%;
                }
                .left {
                        width: 30%;
                }
                .right {
                        float: left;
                        margin-left: 3%;
                }
                .block {
                        padding: 3%;
                        border: 1px solid black;
                        width: 100%;
                        margin-top: 2%;
                        display: inline-block;
                }
        </style>
    
</head>
<body>

        <div class="container">
                <div >
                        <div class="left" style='float: left;'>
                                <img src="<?=base_url()?>package/img/LogoPDF.png" alt="logo" style="width: 50%;">
                                <div class="block" >
                                        <h3><?=$ownername?></h3>
                                        <div style="padding-bottom: 5%;"><img src="<?=$photo?>" style="padding-left: 25%; width: 50%;" /></div>
                                        <div><label>DOB: </label> <?=$dob?></div>
                                        <div><label>Type of Pet: </label> <?=$type?></div>
                                        <div><label>Weight: </label> <?=$weight?></div>
                                        <div><label>Breed: </label> <?=$breed?></div>
                                        <div><label>Sex: </label> <?=$sex?></div>
                                        <div><label>Status: </label> <?=$status?></div>
                                        <div><label>Environment: </label> <?=$environment?></div>
                                </div>

                                <div class="block">
                                        <div><label>Assigned Vet: </label> <?=$vet?></div>
                                </div>

                                <!-- div class="block">
                                    <h3>Client</h3>
                                    <div><label>name: </label> </div>
                                    <div><label>Email: </label> </div>
                                    <div><label>phone: </label> </div>
                                    <div><label>card type: </label> </div>
                                    <div><label>last 4 on CC: </label> </div>
                                    <div><label>charge amount: </label> </div>
                                </div -->
                        </div>
                        <div class="right" >
                                <div class="block">
                                        <h3>Summary: </h3>
                                        <div><label><?=$summary?></label> </div>
                                </div>
                                <div class="block">
                                        <h3>Case History: </h3>
                                        <div><label>Created : </label><?=$created?></div>
                                        <div><label>Assigned : </label><?=$assigned?></div>
                                        <div><label>Appointment : </label><?=$appointment?></div>
                                        <div><label>Resolved : </label><?=$resolved?></div>
                                        <div><label>Closed : </label><?=$closed?></div>
                                </div>
                                <div class="block">
                                        <h3>Details: </h3>
                                        <div><label><?=$detail?></label></div>
                                </div>
                                <div class="block">
                                        <h3>Treatment: </h3>
                                        <div><label><?=$treatment?></label></div>
                                </div>
                                <div class="block">
                                        <h3>Notes: </h3>
                                        <div><label><?=$notes?></label></div>
                                </div>
                        </div>
                </div>
                <div style='clear: both; width: 100%;'>
                        <div class="block">
                                <h3>Media: </h3>
                                <? for($i=0; $i<count($thumbnails); $i++) { ?>
                                        <? if($thumbnails[$i] != NULL) {?>
                                                <img src="<?=$thumbnails[$i]?>" alt="thumbnail" style="padding: 2%;">  
                                        <?}?>
                                <? } ?>
                                
                        </div>
                </div>
        </div>    

        <script type="text/javascript">
                
        </script>
    
</body>
</html>
