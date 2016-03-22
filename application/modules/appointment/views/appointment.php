                    <!-- Content Wrapper. Contains page content -->
                    <div class="content-wrapper">
                            
                            <!-- Main content -->
                            <section class="content">

                                    <div class="row">
                                            <div class="col-md-12">
                                                    <a class="btn btn-primary pull-right" onclick="newAppointment()">New Appointment</a>
                                            </div>
                                            <div class="col-md-12" id="appointment_table">
                                                    <div class="box">
                                                            <div class="box-header">
                                                                    <h3 class="box-title">Appointments</h3>
                                                            </div><!-- /.box-header -->
                                                            <div class="box-body no-padding">
                                                                    <table class="table table-striped">
                                                                            <tr>
                                                                                    <th style="width: 10px">#</th>
                                                                                    <th>Owner Name</th>
                                                                                    <th>Pet Name</th>
                                                                                    <th>Type of Pet</th>
                                                                                    <th>Case Summary</th>
                                                                                    <th>Appointment Date</th>
                                                                            </tr>
                                                                            <? if(count($appoints) > 0) 
                                                                            {       
                                                                                    $count=1;
                                                                                    foreach($appoints as $consult){ ?>
                                                                                            <tr style="cursor:pointer" onclick="toCase('<?=$consult["id"]?>')">
                                                                                                    <td><?=$count++;?>.</td>
                                                                                                    <td><?=$consult['ownerName']?></td>
                                                                                                    <td><?=$consult['petName']?></td>
                                                                                                    <td><?=$consult['petType']?></td>
                                                                                                    <td><?=$consult['summary']?></td>
                                                                                                    <td><?=$consult["date"]?></td>
                                                                                            </tr>
                                                                                    <? } 
                                                                            } ?>
                                                                    </table>
                                                            </div><!-- /.box-body -->
                                                    </div><!-- /.box -->
                                            <div><!-- /.col -->
                                    </div><!-- /.row -->

                            </section><!-- /.content -->
                    </div><!-- /.content-wrapper -->

            </div><!-- ./wrapper -->

<div class="modal fade" id="appointmentForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="box box-primary box-solid">
			<div class="box-header with-border">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="box-title">Create Appointment</h3>
			</div>
			<form action="" id="newappointmentform" class="form-horizontal" method="post">
                                <div class=" modal-body box-body">
                                        <div class="form-group">
                                                <label class="col-md-4 control-label">Owner:</label>
                                                <div class="col-md-8">
                                                        <input type="text" class="form-control" list="ownerlist" id="owner1" name="owner1" onchange="getPet()" >
                                                        <datalist id="ownerlist">
                                                        <?foreach($owners as $owner) {?>
                                                                <option value="<?=$owner?>"></option>
                                                        <? } ?>
                                                        </datalist>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                <label class="col-md-4 control-label">Pet:</label>
                                                <div class="col-md-8">
                                                        <select class="form-control" name="pet" id="pet">
                                                        </select>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                <label class="col-md-4 control-label">Appointment Date:</label>
                                                <div class="col-md-8">
                                                        <input type="text" id="scheduledData" name="scheduledDate" class="form-control datetimepicker" />
                                                </div>
                                        </div>

                                        <div class="form-group">
                                                <label class="col-md-4 control-label">Summary:</label>
                                                <div class="col-md-8">
                                                        <input type="text" id="summary" name="summary" class="form-control" />
                                                </div>
                                        </div>

                                        <div class="form-group">
                                                <label class="col-md-4 control-label">Detail:</label>
                                                <div class="col-md-8">
                                                        <textarea class='form-control' id="detail" name="detail" rows="5"></textarea>
                                                </div>
                                        </div>

                                </div>
                                <div class="modal-footer">
                                        <a class="btn btn-default pull-left" data-dismiss="modal">Close</a>
                                        <button type="submit" class="btn btn-primary pull-right">Create</button>
                                </div>
			</form>
		</div>
	</div>
</div>

		<!-- jQuery 2.1.4 -->
		<script src="<?=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
		<!-- Bootstrap 3.3.2 JS -->
		<script src="<?=base_url()?>src/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<!-- Slimscroll -->
		<script src="<?=base_url()?>src/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
		<!-- FastClick -->
		<script src="<?=base_url()?>src/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
		<!-- AdminLTE App -->
		<script src="<?=base_url()?>src/dist/js/app.min.js" type="text/javascript"></script>
		<script src="<?=base_url()?>src/plugins/datetimepicker/moment.js"></script>
		<script src="<?=base_url()?>src/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
		<script type="text/javascript">
			$(".se-pre-con").hide();

			$(".datetimepicker").datetimepicker({format: 'MM/DD/YYYY hh:mm A'});
			function toCase(id){
				document.location.href=base_url+"appointment/detail/"+id;
			}
			function newAppointment(){
                                tel_modal_refresh();
				$("#appointmentForm").modal('toggle');
				//getPet();
			}
			function getPet(){
				$(".se-pre-con").show();
				$.post(base_url+"appointment/getpet", {owner: $("#owner1").val() }, function(data, status) {
					$("#pet").html(data);
					$(".se-pre-con").hide();
				});
			}
                        function tel_modal_refresh(){
                                $("#owner").val('');
                                $("#pet").html('');
                                $("#scheduledData").val('');
                                $("#summary").val('');
                                $("#detail").val('');
                        }

			var frm = $('#newappointmentform');
			frm.submit(function (ev) {
				if($("#pet").val() == null)
					return false;
				$(".se-pre-con").show();
				$.ajax({
					type: frm.attr('method'),
					url: /*frm.attr('action')*/"<?=base_url()?>appointment/newappointment",
					data: frm.serialize(),
					success: function (data) {
						eval('res='+data);
						if(res.code == "SUCC"){
                                                        $(".se-pre-con").hide();
							$("#appointmentForm").modal('toggle');
							//document.location.reload();
                                                        $("#new_appointments_notification").load('<?=base_url();?>header/do_appointmentNotification');
                                                        $("#appointment_table").load('<?=base_url();?>appointment/update_appointment_table');
                                                        /*$.ajax({
                                                                url: "<?=base_url()?>header/do_appointmentNotification",
                                                                type: "POST",
                                                                data: {},
                                                                dataType: "html",
                                                                success: function(data) {
                                                                        $("#new_appointments_notification").html(data);
                                                                }
                                                        });*/
                                                        
						} else {
							$(".se-pre-con").hide();
							$("#appointmentForm").modal('toggle');
							alert("Code: " + res.code + "\n\rMessage: " + res.msg);
						}
					}
				});

				ev.preventDefault();
			});
		</script>
	</body>
</html>