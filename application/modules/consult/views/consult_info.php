
<div class="row">
        <div class="col-md-12">
        <!-- <h3><small><?if($this->uri->segment(1)=='appointment') { ?>OWNER<?}?></small><br/><?=$ownername?><small class="pull-right"><?if($this->uri->segment(1)=='appointment') { ?>
        <label><?=$pettype?><br/><?=$petname;?></label>
        <?  } else { ?><?=$date?><? } ?></small></h3> -->
                <?if($this->uri->segment(1) == 'appointment') {?>
                        <div class="col-sm-6">
                                <h3><small>OWNER</small><br/><?=$ownername?></h3>
                        </div>
                        <div class="col-sm-6 pull-right">
                                <h3><small><?=$pettype?></small><br/><?=$petname;?></h3>
                        </div>
                <?} else { ?>
                        <div class="col-sm-12">
                                <h3><?=$ownername?><small class="pull-right" style="margin-top:7px;"><?=$date?></small></h3>
                        </div>
                <? } ?>
        </div>
</div>
<div class="box-body">
	<div class="form-group">
		<label>Summary:</label>
		<input type="text" class="form-control" readonly value="<?=$summary?>">
	</div>
	<div class="form-group">
		<label>Detail:</label>
		<textarea class="form-control" readonly rows="5"><?=$detail?></textarea>
	</div>

	<?if($attachcount > 0) {?>

	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="false">
		<ol class="carousel-indicators">
			<?for ($i=0;$i<$attachcount;$i++){ ?>
                                <li data-target="#carousel-example-generic" data-slide-to="<?=$i?>" class="<?if($i == 0) echo "active";?>"></li>
			<? }?>
		</ol>

		<div class="carousel-inner">
                        <?	
                        if($attachcount > 0) 
                        { 
                                $index = 0;
                                foreach ($attaches as $file) 
                                {
                                        $attach=  $file->get('file');

                                        if(substr($attach,-4) == '.png' || substr($attach,-4) == 'jpeg' || substr($attach,-4) == '.jpg' ){
                                                $attachtype = "IMAGE";
                                        } else {
                                                $attachtype = "VIDEO";
                                        }
                                        $fileurl = $attach;
			
                                        if($attachtype == 'VIDEO') {?>

                                                <div class="item <? if($index == 0) echo 'active'; $index++; ?>" id="tel_jwplayer">
                                                        
                                                        <!--a class="fancybox " data-fancybox-type="div" data-fancybox-group="group1" title="" href="" 
                                                            -->
                                                        <div rel="group1" style='position: relative; width: 100%; height: 300px; border: 1px solid black; background: black;' onclick="tel_dialog('<?=$fileurl?>');"> 
                                                                <span style="padding-left: 50%; position: relative; padding-left: 45%; top: 40%;">
                                                                        <i class="glyphicon glyphicon-play-circle" style="font-size: 3em; color: white;"></i>
                                                                </span>
                                                        </div>
                                                        <!--/a -->    
                                                        <!-- video src="<?//=$fileurl?>" style="width:100%" ></video -->
                                                        <!--div style='position: relative; width: 100%; height: 300px; border: 1px solid black; background: black;' 
                                                             onclick="tel_dialog('<?//=$fileurl?>');">
                                                        </div-->
                                                        <!--/a-->
                                                        <!--div id='myElement'></div-->
                                                        
                                                        <!-- a href="javascript:tel_dialog(<?//=$fileurl?>);" --><!-- img width="100%" style="height: 250px;" border="0" src="<?//=base_url()?>package/img/video_screenshot.png" onclick="tel_dialog('<?//=$fileurl?>');" / --> 
                                                        <div class="carousel-caption"></div>
                                                                
                                                </div>
                                        <? } else { ?>
                                                
                                                <div class="item <?if($index == 0) echo 'active'; $index++;?>">
                                                     
                                                        <a class="fancybox " data-fancybox-type="image" data-fancybox-group="group1" title="" href="<?=$fileurl?>">
                                                                
                                                                <img src="<?=$fileurl?>" class="ratioitems" style="width:100%"/>
                                                                <div class="carousel-caption">
                                                                </div>
                                                        </a>
                                                </div>

                                        <? } ?>
		<? 
                                }
                        } 
                ?>

		</div>
		<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
			<span class="fa fa-angle-left"></span>
		</a>
		<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
			<span class="fa fa-angle-right"></span>
		</a>
	</div>
	<? } ?>
</div>


<!-- include jwplayer js file. FMRGJ-KR-->
<script src="<?=base_url()?>package/js/jwplayer-7.2.3/jwplayer.js"></script>
<script>jwplayer.key="MlsHaWRCjuj6G1V7uTKkUnxwKodOmXc+Bz5bkA==";</script>
<script type="text/javascript">
    
        function tel_dialog(fileurl){
                /*$( "#myElement" ).dialog({
                                    height: 390,
                                    width: 475,
                                    modal: true
                            });*/
                /*$("#myElement").dialog({
                        height: 550,
                        width: 675,
                        modal: false,
                        open: function(event, ui) {{

                                $('.carousel-indicators').css('visibility', 'hidden');
                                jwplayer("myElement").setup({
                                        autostart: true,
                                        file: fileurl,
                                        height: 550,
                                        width: 675
                                });

                        }},
                        autoOpen: true,
                        resizable: false, 
                        close: function(event, ui) {{
                                $('.carousel-indicators').css('visibility', 'visible');
                                jwplayer("myElement").remove();
                                $(this).dialog('close');
                        }}
                });*/

                var myVideo = this.href; // Dont forget about 'this'

                $.fancybox({
                        minWidth: 675,
                        width: 675,
                        minHeight: 550,
                        height: 550,
                        autosize: false,
                        padding : 0,
                        content: '<div id="video_container">Loading the player ... </div>',
                        afterShow: function(){
                            jwplayer("video_container").setup({
                                    autostart: true,
                                    file: fileurl,
                                    height: 550,
                                    width: 675
                            });
                        }

                });

                $("#video_container")
                    .attr("data-fancybox-group", "group1");
        }

</script>


