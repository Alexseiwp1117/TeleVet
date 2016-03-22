                    <!-- Content Wrapper. Contains page content -->
                    <div class="content-wrapper">

                        <!-- Main content -->
                        <section class="content">
                            <!-- Info boxes -->
                            <div class="row">
                                <div class="col-md-2 col-sm-4 col-xs-12">
                                    <div class="small-box bg-aqua" style="text-align:center;">
                                        <div class="inner">
                                            <h3><?=$news?></h3>
                                            <p>New Cases</p>
                                        </div><hr/>
                                        This Month: <?=$opened?>
                                    </div>
                                </div><!-- /.col -->

                                <div class="col-md-2 col-sm-4 col-xs-12">
                                    <div class="small-box bg-green" style="text-align:center;">
                                        <div class="inner">
                                            <h3><?=$schedules?></h3>
                                            <p>Scheduled</p>
                                        </div><hr/>
                                        This Month: <?=$scheduled?>
                                    </div>
                                </div><!-- /.col -->

                                <div class="col-md-2 col-sm-4 col-xs-12">
                                    <div class="small-box bg-yellow" style="text-align:center;">
                                        <div class="inner">
                                            <h3><?=$resolved?></h3>
                                            <p>Resolved</p>
                                        </div><hr/>
                                        This Month: <?=$resolved+$closed?>
                                    </div>
                                </div><!-- /.col -->

                                <div class="clearfix visible-sm-block"></div>

                                <div class="col-md-6 col-sm-12 ">
                                    <div class="info-box">

                                    </div><!-- /.info-box -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box">
                                        <div class="box-header">
                                                <h3 class="box-title">New Consultations</h3>
                                        </div><!-- /.box-header -->
                                        <div class="box-body no-padding">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th style="width: 10px">#</th>
                                                    <th>Owner Name</th>
                                                    <th>Pet Name</th>
                                                    <th>Type of Pet</th>
                                                    <th>Case Summary</th>
                                                    <th>Date Submitted</th>
                                                </tr>
                                                <? if(count($newConsults) > 0) {
                                                    $count=1;
                                                    foreach($newConsults as $consult){ ?>
                                                        <tr style="cursor:pointer;" onclick="assign('<?=$consult["id"]?>','<?=$consult["petid"]?>')">
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

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box">
                                        <div class="box-header">
                                            <h3 class="box-title">My Consultations</h3>
                                        </div><!-- /.box-header -->
                                        <div class="box-body no-padding">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th style="width: 10px">#</th>
                                                    <th>Owner Name</th>
                                                    <th>Pet Name</th>
                                                    <th>Type of Pet</th>
                                                    <th>Case Summary</th>
                                                    <th>Date Submitted</th>
                                                </tr>
                                                <? if(count($myConsults) > 0) {
                                                    $count=1;
                                                    foreach($myConsults as $consult){ ?>
                                                        <tr style="cursor:pointer;" onclick="toCase('<?=$consult["id"]?>')">
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

            <div class="modal fade" id="assignForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="width:800px;">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h3 class="box-title">Assign</h3>
                        </div>
                        <div class=" modal-body box-body">
                            <div class="row">
                                    <div class="col-md-6" id="consultinfo" >
                                    </div>
                                    <div class="col-md-6" id="petinfo">
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> -->
                            <form action="<?=base_url()?>dashboard/assign" method="post">
                                    <input type="hidden" id="hiddenconsultid" name="consult">
                                    <button type="submit" class="btn btn-primary">Assign to me</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


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
                $(".se-pre-con").hide();
                function assign(id,petid){
                        $(".se-pre-con").show();
                        $.get(base_url+"consult/index/"+id, function(data, status){
                                $("#consultinfo").html(data);
                                $.get(base_url+"pet/index/"+petid, function(data, status){
                                        $(".se-pre-con").hide();
                                        $("#petinfo").html(data);
                                        $("#hiddenconsultid").val(id);
                                        $("#assignForm").modal('toggle');
                                });
                        });
                }
                function toCase(id){
                        document.location.href=base_url+"consult/detail/"+id;
                }
            </script>		
	</body>
</html>