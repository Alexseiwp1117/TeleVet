                    <!-- Content Wrapper. Contains page content -->
                    <div class="content-wrapper">
                        <!-- Main content -->
                        <section class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box">
                                        <div class="box-header">
                                            <h3 class="box-title">Records</h3>
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <table id="recordTable" class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">#</th>
                                                        <th>Owner Name</th>
                                                        <th>Pet Name</th>
                                                        <th>Type of Pet</th>
                                                        <th>Case Summary</th>
                                                        <th>Status</th>
                                                        <th>Status Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <? if(count($records) > 0) 
                                                    {
                                                        $count=1;
                                                        foreach($records as $consult)
                                                        { ?>
                                                            <tr style="cursor:pointer" onclick="toCase('<?=$consult["id"]?>')" >
                                                                <td><?=$count++;?>.</td>
                                                                <td><?=$consult['ownerName']?></td>
                                                                <td><?=$consult['petName']?></td>
                                                                <td><?=$consult['petType']?></td>
                                                                <td><?=$consult['summary']?></td>
                                                                <td><span class="badge <?if($consult['state'] == "RESOLVED"){ echo 'bg-green'; } else { echo 'bg-red';}?>"><?=$consult['state']?></span></td>
                                                                <td><?=$consult["date"]?></td>
                                                            </tr>
                                                        <? 
                                                        } 
                                                    } ?>
                                                </tbody>
                                            </table>
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
		<!-- DATA TABES SCRIPT -->
		<script src="<?=base_url()?>src/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
		<script src="<?=base_url()?>src/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
		<script type="text/javascript">
                    $(".se-pre-con").hide();
                    $('#recordTable').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false
                    });
                    function toCase(id){
                        document.location.href=base_url+"consult/detail/"+id;
                    }
		</script>
	</body>
</html>