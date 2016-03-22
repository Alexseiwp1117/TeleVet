			<!-- Content Wrapper. Contains page content -->
                        <div class="content-wrapper" onclick="">

                                <!-- Main content -->
                                <section class="content">
                                        <!-- Info boxes -->
                                        <div class="row">
                                                <div class="col-md-4">
                                                        <?=$this->load->Module('consult')->index($consultId)?>
                                                </div>
                                                <div class="col-md-4">
                                                        <?$this->load->Module('pet')->index($consult->get('pet'));?>
                                                </div>
                                                <div class="col-md-4">
                                                        <?if($status){ ?>
                                                                <h3>Video Conference</h3>
                                                                <div class="form-group">
                                                                        <input type="text" class="datetimepicker form-control" id="booktime" 
                                                                               <?if($consult->get('videoChat')==false) {echo "placeholder='Owner Not Available' disabled";} 
                                                                               else { ?>
                                                                                        <?
                                                                                        try{
                                                                                                $datetime= $consult->get('scheduledAt');
                                                                                                if(!empty($datetime)){

                                                                                                        $zone= $this->session->userdata('sess_user_zone');

                                                                                                        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

                                                                                                        $tz=new DateTimeZone($zone);
                                                                                                        $heredate= date_timezone_set($datetime, $tz);
                                                                                                        $showdate = $heredate->format("m/d/Y h:i A");

                                                                                                        echo "value='".$showdate."'"; 
                                                                                                }
                                                                                        }
                                                                                        catch(Exception $e) {
                                                                                        } 
                                                                                        ?>
                                                                                ><? } ?>

                                                                </div>
                                                                <button onclick="book('<?=$consultId?>','<?=$consult->get('videoChat')?>')" class="btn <?if($consult->get('videoChat')==false) {echo 'disabled';}?> btn-primary form-control">Book</button>
                                                                <br/>
                                                        <? } 
                                                        else { ?>
                                                                <h3>Case History</h3>
                                                                <div class="form-group">
                                                                        <label>Created:</label>
                                                                        <?
                                                                        try{
                                                                                $datetime= $consult->getCreatedAt();
                                                                                $zone= $this->session->userdata('sess_user_zone');
                                                                                $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

                                                                                $tz=new DateTimeZone($zone);
                                                                                $heredate= date_timezone_set($datetime, $tz);
                                                                                $showdate = $heredate->format("m/d/Y h:i A");

                                                                                echo $showdate;
                                                                        } catch(Exception $e) { }?>

                                                                </div>
                                                                <div class="form-group">
                                                                        <label>Assigned:</label>

                                                                        <?
                                                                        try{
                                                                                $datetime= $consult->get("openedAt");

                                                                                $zone= $this->session->userdata('sess_user_zone');

                                                                                $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

                                                                                $tz=new DateTimeZone($zone);
                                                                                $heredate= date_timezone_set($datetime, $tz);
                                                                                $showdate = $heredate->format("m/d/Y h:i A");

                                                                                echo $showdate;
                                                                        } 
                                                                        catch(Exception $e) { echo "N/A"; }
                                                                        ?>

                                                                </div>
                                                                <div class="form-group">
                                                                        <label>Appointment:</label>
                                                                        <?
                                                                        try{
                                                                                $datetime= $consult->get('scheduledAt');

                                                                                if(!empty($datetime)){
                                                                                        $zone= $this->session->userdata('sess_user_zone');
                                                                                        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

                                                                                        $tz=new DateTimeZone($zone);
                                                                                        $heredate= date_timezone_set($datetime, $tz);
                                                                                        $showdate = $heredate->format("m/d/Y h:i A");

                                                                                        echo $showdate; 
                                                                                } 
                                                                                else { echo "N/A"; }
                                                                        } 
                                                                        catch(Exception $e) { echo "N/A"; }
                                                                        ?>
                                                                </div>
                                                                <div class="form-group">
                                                                        <label>Resolved:</label>
                                                                        <? 
                                                                        if($consult->get('state') == 'RESOLVED') {?>
                                                                                <?
                                                                                try{
                                                                                        $datetime= $consult->get("finishedAt");
                                                                                        $zone= $this->session->userdata('sess_user_zone');
                                                                                        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

                                                                                        $tz=new DateTimeZone($zone);
                                                                                        $heredate= date_timezone_set($datetime, $tz);
                                                                                        $showdate = $heredate->format("m/d/Y h:i A");

                                                                                        echo $showdate;
                                                                                } 
                                                                                catch(Exception $e) { } 
                                                                        } else { echo "N/A";}?>
                                                                </div>
                                                                <div class="form-group">
                                                                        <label>Closed:</label>
                                                                        <? 
                                                                        if($consult->get('state') == 'CLOSED') {?>
                                                                                <?try{
                                                                                        $datetime= $consult->get("finishedAt");
                                                                                        $zone= $this->session->userdata('sess_user_zone');
                                                                                        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

                                                                                        $tz=new DateTimeZone($zone);
                                                                                        $heredate= date_timezone_set($datetime, $tz);
                                                                                        $showdate = $heredate->format("m/d/Y h:i A");

                                                                                        echo $showdate;
                                                                                } 
                                                                                catch(Exception $e) { } 
                                                                        } else { echo "N/A"; }  ?>
                                                                </div>
                                                        <? } ?>
                                                        <br/>
                                                        <?$this->load->Module('chat')->index($consultId);?>
                                                </div>
                                        </div><!-- /.row -->

                                        <div class="row">
                                                <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label>Treatment:</label>
                                                                <textarea id="treat" rows="5" name="treat" class="form-control" <?if(!$status) echo "readonly";?>><?=$consult->get('treat')?></textarea>
                                                        </div>
                                                </div>
                                                <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label>Notes:</label>
                                                                <textarea id="note" rows="5" name="note" class="form-control"><?=$consult->get('note')?></textarea>
                                                        </div>
                                                </div>
                                        </div><!-- /.row -->

                                        <div class="row">
                                                <div class="col-md-12 pull-right">
                                                        <? $petid = $consult->get('pet') ?>
                                                        <form action="<?=base_url('consult/do_exportpdf/'.$petid.'/'.$consultId)?>" method="post" id="form_export">
                                                        </form>
                                                        <form action="<?=base_url('consult/do_printpdf/'.$petid.'/'.$consultId)?>" method="post" id="form_print">
                                                        </form>
                                                        <form action="<?=base_url('consult/do_tcpdf')?>" method="post" id="form_tcpdf">
                                                        </form>
                                                        <button type="submit" form="form_export" style="margin:0 5px;" class="btn btn-primary pull-right">Export</button>
                                                        <button type="submit" form="form_print" style="margin:0 5px;" class="btn btn-primary pull-right">Print</button>

                                                        <?if($status) { ?>
                                                                <button onclick="cclose('<?=$consultId?>',0)" style="margin:0 5px;" class="btn btn-primary pull-right">Close</button>
                                                                <button onclick="cclose('<?=$consultId?>',1)" style="margin:0 5px;" class="btn btn-primary pull-right">Resolve</button>
                                                        <? } ?>
                                                        <button onclick="update('<?=$consultId?>')" style="margin:0 5px;" class="btn btn-primary pull-right">Save</button>

                                                </div>
                                        </div><!-- /.row -->

                                </section><!-- /.content -->
			</div><!-- /.content-wrapper -->
		</div><!-- ./wrapper -->

                <!-- jQuery 2.1.4 -->
		<script src="<?=base_url()?>src/dist/js/jquery.mousewheel-3.0.6.pack.js" type="text/javascript"></script>
		<script src="<?=base_url()?>src/dist/js/jquery.fancybox.js" type="text/javascript"></script>
		<script src="<?=base_url()?>src/dist/js/jquery.fancybox-media.js" type="text/javascript"></script>
		<!-- Bootstrap 3.3.2 JS -->
		<script src="<?=base_url()?>src/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<!-- FastClick -->
		<script src="<?=base_url()?>src/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
		<!-- AdminLTE App -->
		<script src="<?=base_url()?>src/dist/js/app.min.js" type="text/javascript"></script>
		<!-- Sparkline -->
		<script src="<?=base_url()?>src/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
		<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script> -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
		<script src="<?=base_url()?>src/plugins/datetimepicker/moment.js"></script>
		<script src="<?=base_url()?>src/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
                <!-- include jquery-ui js file. FMRGJ-KR-->
                <!-- script src="<?//=base_url()?>package/js/jquery-ui/jquery-ui.js"></script-->
                                
                <!-- script src="<?//=base_url()?>package/js/jwplayer-tel/jwplayer-tel.js"></script -->
                
                <script type="text/javascript">
                        
                        
			$(document).ready(function () {
                                $(".fancybox").fancybox({
                                        openEffect: 'none',
                                        closeEffect: 'none',
                                        nextEffect: 'none',
                                        prevEffect: 'none',
                                        padding: 0,
                                        margin: [20, 0, 20, 0]
                                });
                                
                                
                                
			});

			$(".datetimepicker").datetimepicker({format: 'MM/DD/YYYY hh:mm A'});
			$(".se-pre-con").hide();
			$(".direct-chat-messages").height(280);
                        
			function book(id,flag){
                                if(flag==false){
                                        return;
                                }
                                if($('#booktime').val()==""){
                                        alert('Select date and time.');
                                        return false;
                                }
                                $(".se-pre-con").show();
                                $.post(base_url+"consult/book",
                                        {
                                                consult: id,
                                                time: $('#booktime').val() 
                                        }, 
                                        function(data, status) {
                                                $(".se-pre-con").hide();
                                                eval("res="+data);
                                                if(res.code == "SUCC") {
                                                        alert('Booked successfully.');
                                                } else {
                                                        alert("ERROR: "+res.code + "Message: " + res.msg);
                                                }
                                        }); 
			}

			function update(id){
                                $(".se-pre-con").show();
                                $.post(base_url+"consult/update",
                                        {
                                                consult: id,
                                                treat: $("#treat").val(),
                                                note: $("#note").val()
                                        }, 
                                        function(data, status) {
                                                $(".se-pre-con").hide();
                                                alert('Saved successfully.');
                                        }); 
			}

			function cclose(id, state){
                                $(".se-pre-con").show();
                                if(state == 1){
                                        $.post(base_url+"consult/update",
                                        {
                                                consult: id,
                                                treat: $("#treat").val(),
                                                note: $("#note").val()
                                        }, 
                                        function(data, status) {
                                        }); 
                                }
                                $.post(base_url+"consult/finish",
                                        {
                                                consult: id,
                                                state: state
                                        }, 
                                        function(data, status) {
                                                $(".se-pre-con").hide();
                                                alert('Consultation finished.');
                                                document.location.reload();
                                        }); 
			}

			$(document).ready(function() {      
                                $('.carousel').carousel('pause');
			});			

			var $items = $(".ratioitems");
			var firstWidth = 0;

			$items.each( function(){
                                var w = $(this)[0].clientWidth; //I want the CURRENT width, not original!!
                                if( w != 0)
                                        firstWidth = w;

                                //$(this).css('max-height', firstWidth / 9 * 16);
			});
                        
                        
                        
                        function cancelJWPlayer(){
                                /*alert($("#myElement").data('myElement').isOpen);
                                if ($("#myElement").is(":visible") == true){
                                        jQuery('#myElement').dialog('close');
                                        
                                }*/
                        }
                        

		</script>        
                
	</body>
</html>