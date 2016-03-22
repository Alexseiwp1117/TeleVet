			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">

				<!-- Main content -->
				<section class="content">

					<div class="row">
						<div class="col-md-12">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">Active Veterinarians</h3>
								</div><!-- /.box-header -->
								<div class="box-body no-padding">
									<table class="table table-striped">
										
										<tr>
											<th style="width: 10px">#</th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>Email</th>
											<th></th>
										</tr>
										<? if(count($vets) > 0) {$count=1;foreach($vets as $vet){ 
											if($vet->get('active')){
											$id=$vet->getObjectId();
										?>
										<tr id="<?=$id?>">
											<td><?=$count++;?>.</td>
											<td><?=$vet->get('firstName')?></td>
											<td><?=$vet->get('lastName')?></td>
											<td><?=$vet->get('email')?></td>
											<td><a class="btn btn-sm btn-danger" onclick="stateVet('<?=$id?>',0)">Disable</i></a></td>
										</tr>
										<? } } }?>
										<form action="<?=base_url()?>clinic/addVet" method="post">
										<tr>
											<td></td>
											<td><input type="text" class="form-control" required name="fname" /></td>
											<td><input type="text" class="form-control" required name="lname" /></td>
											<td><input type="text" class="form-control" required name="email" /></td>
											<td><button type="submit" class="btn btn-sm btn-primary" ><i class="fa fa-plus"></i></button></td>
										</tr>
										</form>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						<div><!-- /.col -->
					</div><!-- /.row -->

					<div class="row">
						<div class="col-md-12">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">Disabled Veterinarians</h3>
								</div><!-- /.box-header -->
								<div class="box-body no-padding">
									<table class="table table-striped">
										
										<tr>
											<th style="width: 10px">#</th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>Email</th>
											<th></th>
										</tr>
										<? if(count($vets) > 0) {$count=1;foreach($vets as $vet){ 
											if(!$vet->get('active')){
											$id=$vet->getObjectId();
										?>
										<tr id="<?=$id?>">
											<td><?=$count++;?>.</td>
											<td><?=$vet->get('firstName')?></td>
											<td><?=$vet->get('lastName')?></td>
											<td><?=$vet->get('email')?></td>
											<td><a class="btn btn-sm btn-success" onclick="stateVet('<?=$id?>',1)">Activate</a></td>
										</tr>
										<? } } }?>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						<div><!-- /.col -->
					</div><!-- /.row -->

					<div class="row">
						<div class="col-md-4">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">Clinic Pricing</h3>
								</div><!-- /.box-header -->
								<div class="box-body">
									<form class="form-horizontal">
									<div class="box-body">
										<div class="form-group">
											<label class="col-md-6 control-label" >Consultations ($):</label>
											<div class="col-md-6">
												<input type="number" class="form-control" min="0" required id="conscost" value="<?=$clinic->get('consultCost')?>" />
											</div>
										</div>
									
										<div class="form-group">
											<label class="col-md-6 control-label" >Follow Ups ($):</label>
											<div class="col-md-6">
												<input type="number" class="form-control" min="0" required id="followcost" value="<?=$clinic->get('followCost')?>"/>
											</div>
										</div>
									
										<div class="form-group">
											<div class="col-md-12">
												<a onclick="updateprice()" class="btn btn-primary form-control" >Update</a>
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
		<!-- FastClick -->
		<script src="<?=base_url()?>src/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
		<!-- AdminLTE App -->
		<script src="<?=base_url()?>src/dist/js/app.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(".se-pre-con").hide();
			function stateVet(id, state){
				$(".se-pre-con").show();
				$.post( base_url+'clinic/stateVet', { id: id, state:state}	, function(data,status){
					$(".se-pre-con").hide();
					document.location.reload();
				});
			}
			function updateprice(){
				if(!$("#conscost").val()){
					return false;
				}
				if(!$("#followcost").val()){
					return false;
				}
				$(".se-pre-con").show();
				$.post( base_url+'clinic/updateprice', { consultCost: $("#conscost").val() , followCost: $("#followcost").val() }	, function(data,status){
					$(".se-pre-con").hide();
					alert("Changed Successfuly");
				});
			}
		</script>
	</body>
</html>