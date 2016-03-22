			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">

				<!-- Main content -->
				<section class="content">

					<div class="row">
						<div class="col-md-4">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">Profile</h3>
								</div><!-- /.box-header -->
								<div class="box-body no-padding">
									<div class="row">
										<div class="col-md-3">
										</div>
										<div class="col-md-6">
											<img src="<?$photo=$me->get('photo'); if($photo){ echo str_replace("http://", "//", $photo->getURL()); } else { echo base_url()."src/dist/img/nophoto.png";}?>" style="width:100%;" class="ratioitems"/>
										</div>
										<div class="col-md-3">
										</div>
									</div>
									<br/>
									<div class="form-group">
										<label class="col-md-4 control-label" >Name:</label>
										<?=$me->get('firstName').' '.$me->get('lastName')?>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" >Email:</label>
										<?=$me->get('email')?>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" >Zone:</label>
										<?=$me->get('zone')?>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" >Phone:</label>
										<?=$me->get('phone')?>
									</div>

									

									<div class="form-group">
										<div class="col-md-12">
											<a href="setting/edit" class="btn btn-primary form-control" >Edit</a>
										</div>
									</div>
									<br/><br/>
									<div class="form-group">
										<div class="col-md-12">
											<a onclick="passwordform()" class="btn btn-warning form-control" >Update Password</a>
										</div>
									</div>
									<br/><br/>
									<div class="form-group">
										<div class="col-md-12">
											<a onclick="gethelp()" class="btn btn-info form-control" >Get Help</a>
										</div>
									</div>
									<br/><br/>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						<div><!-- /.col -->
					</div><!-- /.row -->

				</section><!-- /.content -->
			</div><!-- /.content-wrapper -->

		</div><!-- ./wrapper -->

<div class="modal fade" id="passwordForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:300px;">
		<div class="box box-primary box-solid">
			<div class="box-header with-border">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="box-title">Change Password</h3>
			</div>
			<form action="<?=base_url()?>setting/password" id="passform" method="post">
			<div class=" modal-body box-body">

				<div class="form-group">
					<input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="New Password" required>
				</div>

				<div class="form-group">
					<input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
					<span id='passmessage'></span>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				<input type="hidden" id="hiddenconsultid" name="consult">
				<button type="submit" id="passsub" class="btn btn-primary">Change Password</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="helpform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" >
		<div class="box box-primary box-solid">
			<div class="box-header with-border">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="box-title">Help</h3>
			</div>
			<div class=" modal-body box-body">

				<div class="form-group">
					<input type="checkbox" id="helpsms" name="helpsms" value="1"> Contact by phone
				</div>

				<div class="form-group">
					<input type="checkbox" id="helpemail" name="helpemail" value="1"> Contact by email
				</div>

				<div class="form-group">
					<label>Message</label>
					<textarea id="helpmessage" name="helpmessage" class="form-control" placeholder="Enter your message" rows="3"></textarea>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				<button onclick="sendhelp()" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</div>
</div>

		<!-- jQuery 2.1.4 -->
		<script src="<?=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
		<!-- Bootstrap 3.3.2 JS -->
		<script src="<?=base_url()?>src/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<!-- FastClick -->
		<script src="<?=base_url()?>src/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
		<!-- AdminLTE App -->
		<script src="<?=base_url()?>src/dist/js/app.min.js" type="text/javascript"></script>
		<!-- iCheck -->
		<script src="<?=base_url()?>src/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
		<script>
			$(".se-pre-con").hide();
			function passwordform(){
				$("#passwordForm").modal('toggle');
			}
			function gethelp(){
				$("#helpform").modal('toggle');
			}

			function sendhelp(){
				var sms=$("#helpsms").is(":checked");
				var email= $("#helpemail").is(":checked");
				if(sms == 0 && email == 0){
					alert("Please select SMS or Email");
					return false;
				}

				var formData = new FormData();
				formData.append('sms',sms);
				formData.append('email',email);
				formData.append('message',$("#helpmessage").val());

				$.ajax({
					url : base_url+"setting/help",
					type : 'POST',
					data : formData,
					processData: false,  // tell jQuery not to process the data
					contentType: false,  // tell jQuery not to set contentType
					success : function(data) {
						alert("Submitted");
					}
				});
			}

			$('#newpassword, #confirmpassword').on('keyup', function () {
				if ($('#newpassword').val() == $('#confirmpassword').val()) {
					$('#passmessage').html('Matching').css('color', 'green');
					$("#passsub").removeAttr('disabled');
				} else {
					$('#passmessage').html('Not Matching').css('color', 'red');
					$("#passsub").attr('disabled',true);
				}
			});

			var $items = $(".ratioitems");
			var firstWidth = 0;

			$items.each( function(){
				var w = $(this)[0].clientWidth; //I want the CURRENT width, not original!!
				if( w != 0)
					firstWidth = w;

				$(this).css('max-height', firstWidth / 9 * 16);
			});

		</script>
	</body>
</html>