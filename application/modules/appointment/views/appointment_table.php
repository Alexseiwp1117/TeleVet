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