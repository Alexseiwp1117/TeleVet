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
											<img id="prevImg" src="<?$photo=$me->get('photo'); if($photo){ echo str_replace("http://", "//", $photo->getURL()); } else { echo base_url()."src/dist/img/nophoto.png";}?>" style="width:100%;" class="ratioitems"/>
										</div>
										<div class="col-md-3">
										</div>
									</div>
									<br/>
									<form class="form-horizontal" action="setting/save" method="post" enctype="multipart/form-data" >
									<div class="box-body">
										<div class="form-group">
											<label class="col-md-4 control-label" >Photo:</label>
											<div class="col-md-8">
												<input type="file" class="form-control" name="photo" onchange="preview(event)" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label" >First Name:</label>
											<div class="col-md-8">
												<input type="text" class="form-control" required name="fname" value="<?=$me->get('firstName')?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label" >Last Name:</label>
											<div class="col-md-8">
												<input type="text" class="form-control" required name="lname" value="<?=$me->get('lastName')?>"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label" >Email:</label>
											<div class="col-md-8">
												<input type="email" class="form-control" required name="email" value="<?=$me->get('email')?>"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label" >Phone:</label>
											<div class="col-md-8">
												<input type="text" class="form-control" required name="phone" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?=$me->get('phone')?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label" >Zone:</label>
											<?$zone = $me->get('zone');?>
											<div class="col-md-8">
												<select class="form-control" name="zone" >
													<option value="PST" <?if($zone=="PST") echo "selected";?>>PST</option>
													<option value="MST" <?if($zone=="MST") echo "selected";?>>MST</option>
													<option value="CST" <?if($zone=="CST") echo "selected";?>>CST</option>
													<option value="EST" <?if($zone=="EST") echo "selected";?>>EST</option>
												</select>
											</div>
										</div>
										<br/>
										<div class="form-group">
											<div class="col-md-6">
												<button type="submit" class="btn btn-primary form-control" >Save</button>
											</div>
											<div class="col-md-6">
												<a class="btn btn-primary form-control" href="<?=base_url()?>setting">Cancel</a>
											</div>
										</div>
									</div>
									</form>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						<div><!-- /.col -->
					</div><!-- /.row -->

				</section><!-- /.content -->
			</div><!-- /.content-wrapper -->

		</div><!-- ./wrapper -->

		<!-- jQuery 2.1.4 -->
		<script src="<?=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
		<!-- Bootstrap 3.3.2 JS -->
		<script src="<?=base_url()?>src/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<!-- InputMask -->
		<script src="<?=base_url()?>src/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
		<script src="<?=base_url()?>src/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
		<script src="<?=base_url()?>src/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
		<!-- FastClick -->
		<script src="<?=base_url()?>src/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
		<!-- AdminLTE App -->
		<script src="<?=base_url()?>src/dist/js/app.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(".se-pre-con").hide();
			$(":input").inputmask();
			function preview(event){

				var reader = new FileReader();

				reader.onload = function(){
					var output = document.getElementById('prevImg');
					output.src = reader.result;
				};

				reader.readAsDataURL(event.target.files[0]);

			}

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