			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">

				<!-- Main content -->
				<section class="content">
					<!-- Info boxes -->
					<div class="row">
						<div class="col-md-8" id="videoConferenceArea">
							<?$this->load->Module('chat')->index($consultId);?>
							<div class="row">
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
									<a id="toConference" onclick="toVideo()" class="btn btn-primary form-control">Enter Conference</a>
								</div>
								<div class="col-md-4">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="row">
								<div class="col-md-6">
									<h3>Video Conference</h3>
									<div class="form-group">
										<input type="text" class="datetimepicker form-control" id="booktime" 
										<?
											try{
												$datetime= $consult->get('scheduledAt');

												$zone= $this->session->userdata('sess_user_zone');

												$date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

												$tz=new DateTimeZone($zone);
												$heredate= date_timezone_set($datetime, $tz);
												$showdate = $heredate->format("m/d/Y h:i A");
												
												echo "value='".$showdate."'"; 
											} catch(Exception $e) { }?>
										>
									</div>
									<button onclick="book('<?=$consultId?>','<?=$consult->get('videoChat')?>')" class="btn <?if($consult->get('videoChat')==false) {echo "btn-disable";}?> btn-primary form-control">Book</button>
								</div>
								<div class="col-md-6">
									<h3>Case History</h3>
									<div class="form-group">
										<label>Created:</label><br/>
										<?try{
										$datetime= $consult->getCreatedAt();

										$zone= $this->session->userdata('sess_user_zone');

										$date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

										$tz=new DateTimeZone($zone);
										$heredate= date_timezone_set($datetime, $tz);
										$showdate = $heredate->format("m/d/Y h:i A");

										echo $showdate;
										} catch(Exception $e) { echo "N/A"; }?>
									</div>
									<div class="form-group">
										<label>Assigned:</label><br/>
										<?try{
										$datetime= $consult->get("openedAt");

										$zone= $this->session->userdata('sess_user_zone');

										$date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

										$tz=new DateTimeZone($zone);
										$heredate= date_timezone_set($datetime, $tz);
										$showdate = $heredate->format("m/d/Y h:i A");

										echo $showdate;
										} catch(Exception $e) { echo "N/A"; }?>
									</div>
								</div>
							</div>
							<?=$this->load->Module('consult')->index($consultId)?>
						</div>
					</div><!-- /.row -->

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Treatment:</label>
								<textarea id="treat" rows="5" name="treat" class="form-control" ><?=$consult->get('treat')?></textarea>
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
						<div class="col-md-3 pull-right">
							<button onclick="update('<?=$consultId?>')" class="btn btn-primary">Save</button>
							<?if($status) { ?>
							<button onclick="cclose('<?=$consultId?>',1)" class="btn btn-primary">Resolve</button>
							<button onclick="cclose('<?=$consultId?>',0)" class="btn btn-primary">Close</button>
							<? } ?>
						</div>
					</div><!-- /.row -->

				</section><!-- /.content -->
			</div><!-- /.content-wrapper -->
		</div><!-- ./wrapper -->

		<!-- jQuery 2.1.4 -->
		<script src="<?=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
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
			$(".direct-chat-messages").height(430);
			function toCase(id){
				document.location.href=base_url+"consult/detail/"+id;
			}
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
						document.location.href=base_url+"dashboard";
					}); 
			}

			$(document).ready(function() {      
                                $('.carousel').carousel('pause');
			});

			
			function toVideo(){
				$(".se-pre-con").show();
				$.get(base_url + "chat/videochat/" + "<?=$consultId?>",null,function(data,status){
					$(".se-pre-con").hide();
					$("#videoConferenceArea").html(data);
				});
			}

			var $items = $(".ratioitems");
			var firstWidth = 0;

			$items.each( function(){
				var w = $(this)[0].clientWidth; //I want the CURRENT width, not original!!
				if( w != 0)
					firstWidth = w;

				//$(this).css('max-height', firstWidth / 9 * 16);
			});

		</script>		
	</body>
</html>