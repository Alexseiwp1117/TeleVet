<div class="box box-primary direct-chat direct-chat-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Direct Chat</h3>
		<div class="box-tools pull-right">
			<!-- <span data-toggle="tooltip" title="3 New Messages" class="badge bg-yellow">3</span> -->
			<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<!-- <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
		</div>
	</div><!-- /.box-header -->
	<div class="box-body">
		<input type="hidden" id="chatOwnerId" value="<?=$ownerId?>">
		<input type="hidden" id="chatConsultId" value="<?=$consultId?>">
		<!-- Conversations are loaded here -->
		<div class="direct-chat-messages" id="chat-content">
			<!-- Message. Default to the left -->

			<?if(count($messages) > 0) {
				foreach($messages as $message){
					$usertype= $message->get('userType');
					$messagetype= $message->get('type');
                                ?>
                                        <!-- Message to the right -->
                                        <div class="direct-chat-msg <?if($usertype == "VET") echo "right";?>">
                                                <div class="direct-chat-info clearfix">
                                                        <!-- <span class="direct-chat-name pull-right">Sarah Bullock</span>
                                                        <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span> -->
                                                </div><!-- /.direct-chat-info -->

                                                <?if($usertype=="OWNER"){
                                                        if($message->get('read') == false) {
                                                                $message->set('read', true);
                                                                $message->save();
                                                        }

                                                        ?>
                                                        <img class="direct-chat-img" src="<?=$owner_photo?>" alt="message user image" />
                                                <?} else { ?>
                                                        <img class="direct-chat-img" src="<?=$vet_photo?>" alt="message user image" />
                                                <? } ?>

                                                <?if($messagetype=="TEXT") {?>
                                                        <div class="direct-chat-text">
                                                                <?=$message->get('detail');?>
                                                        </div><!-- /.direct-chat-text -->

                                                <?} else if($messagetype=="FILE") {?>

                                                        <?$attach=$message->get('attach');
                                                        if(!empty($attach)){
                                                                if(substr($attach,-4) == '.png' || substr($attach,-4) == 'jpeg' || substr($attach,-4) == '.jpg' ){
                                                                        $type = "IMAGE";
                                                                } else {
                                                                        $type = "VIDEO";
                                                                }
                                                        ?>
                                                                <div class="attachment">
                                                                        <?if($usertype=="OWNER"){?>
                                                                                <div class="pull-left " >
                                                                                        <?if($type=="IMAGE"){?>
                                                                                                <img style="width:200px;border-radius:20px;" src="<?=$attach?>"></img>
                                                                                        <?} else {?>
                                                                                                <video controls style="width:200px;border-radius:20px;" src="<?=$attach?>"></video>
                                                                                        <?}?>
                                                                                </div>
                                                                        <? } else { ?>
                                                                                <div class="pull-right ">
                                                                                        <?if($type=="IMAGE"){?>
                                                                                                <img style="width:200px;border-radius:20px;" src="<?=$attach?>"></img>
                                                                                        <?} else {?>
                                                                                                <video controls style="width:200px;border-radius:20px;" src="<?=$attach?>"></video>
                                                                                        <?}?>
                                                                                </div>
                                                                        <? } ?>
                                                                </div><!-- /.attachment -->
                                                        <?}?>

                                                <? } ?>

                                        </div><!-- /.direct-chat-msg -->

			<?} }?>

		</div><!--/.direct-chat-messages-->	
	</div><!-- /.box-body -->
	<div class="box-footer">
		<?if($state == "CREATED" || $state == "OPENED" || $state == "SCHEDULED" ) {?>
                        <form action="<?=base_url()?>chat/newmessage" id="chatform" method="post" enctype="multipart/form-data" >

                                <input type="hidden" name="consultId" value="<?=$consultId?>">
                                <div class="form-group">
                                        <a class="btn btn-primary btn-flat" id="btnfile" onclick="getfile()">Attach</a>
                                        <input type="file" class="form-control" id='attach' name="attach" accept="image/*,video/*" style="display:none;" onchange="sendFile()"/>
                                </div>
                                <div class="input-group">
                                        <textarea class="form-control" placeholder="Type Message ..." id="sdmessage" style="resize:none" name="message" ></textarea>
                                        <a class="btn btn-primary btn-flat input-group-addon " onclick="sendMessage()" style="color:white;background-color:#3c8dbc;border-color:#367fa9;" id="btnmessage">Send</a>
                                </div>

                        </form>
                        <!--script src="<?//=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script -->
                        <script type="text/javascript">

                                var opposite = false;
                                var ownerlive = false;

                                consult = $("#chatConsultId").val();
                                ownername = $("#chatOwnerId").val();
                                
                                // convert javascript value to json string. FMRGJ-KR
                                callingTo= JSON.stringify({
                                        name : ownername,
                                        consult : consult 
                                });

                                socket.emit('loginChatRoom', JSON.stringify({
                                        name : username,
                                        consult : consult
                                }), ownername);

                                socket.on('login_error', function(message){
                                        console.log('login_error');
                                });

                                socket.on('chatOpposite', function(res, opplive){
                                        opposite = res;
                                        ownerlive =opplive;
                                        //try to get offer.
                                });

                                socket.on('online', function(name){
                                        if (name == ownername )
                                        {
                                                ownerlive = true;
                                        }
                                });

                                socket.on('offline', function(name){
                                        if (name == ownername )
                                        {
                                                ownerlive = false;
                                        }
                                });

                                socket.on('chatMessageReceived', onMessageReceived);

                                function receiveMessage(_message){
                                        message = JSON.parse(_message);

                                        header='<div class="direct-chat-msg"><div class="direct-chat-info clearfix"></div>';
                                        photo='<img class="direct-chat-img" src="<?=$owner_photo?>" alt="message user image" />';

                                        if(message.type == "TEXT" ){
                                                text = message.data;
                                                content= '<div class="direct-chat-text">'+text+'</div>';
                                                add=header+photo+content+"</div>";
                                                $("#chat-content").append(add);

                                                $("#chat-content").animate({ scrollTop: 9999 }, "slow");
                                        } else if(message.type == "FILE"){

                                                add='<div class="attachment"><div class="pull-right ">';
                                                url = message.data;
                                                var extension = url.split('.').pop(); 

                                                if(extension == "jpg" || extension == "jpeg" || extension == "png"){
                                                        add = add + '<img style="width:200px;border-radius:20px;" src="' + url + '"></img>';
                                                } else {
                                                        add = add + '<video controls style="width:200px;border-radius:20px;" src="' + url + '"></video>';
                                                }
                                                add= add+"</div></div>";

                                                content=header+photo+add+"</div>";
                                                $("#chat-content").append(content);

                                                $("#chat-content").animate({ scrollTop: 9999 }, "slow");
                                        }
                                }

                                function onMessageReceived(_from, message){
                                        receiveMessage(message);
                                }

                                $("#chat-content").scrollTop(99999);

                                function getfile(){
                                        $("#attach").click();
                                }

                                function sendFile(){

                                        header='<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"></div>';
                                        photo='<img class="direct-chat-img" src="<?=$vet_photo?>" alt="message user image" />';

                                        var file= document.getElementById('attach').files[0];

                                        if(!file){
                                                return;
                                        }

                                        /*$("#btnfile").attr('disabled',true);
                                        $("#btnmessage").attr('disabled',true);*/

                                        var imageType = /image.*/;
                                        var videoType = /video.*/;
                                        var add='<div class="attachment"><div class="pull-right ">';
                                        var src="";

                                        var reader = new FileReader();

                                        reader.onload = function (e) {
                                                src=e.target.result;

                                                if (file.type.match(imageType)) {
                                                add = add + '<img style="width:200px;border-radius:20px;" src="' + src + '"></img>';
                                                } else if (file.type.match(videoType)) {
                                                        add = add + '<video controls style="width:200px;border-radius:20px;" src="' + src + '"></video>';
                                                }
                                                add= add+"</div></div>";

                                                content=header+photo+add+"</div>";
                                                $("#chat-content").append(content);
                                                content="";
                                                add="";

                                                $("#chat-content").animate({ scrollTop: 9999 }, "slow");

                                                var formData = new FormData();
                                                formData.append('consultId','<?=$consultId?>');
                                                formData.append('attach',file);
                                                if(!opposite) {
                                                        formData.append('push',"false");
                                                } else {
                                                        formData.append('push',"true");
                                                }
                                                formData.append('ownerId',ownername);

                                                var url=base_url+'chat/newmessage';

                                                $.ajax({
                                                        url : url,
                                                        type : 'POST',
                                                        data : formData,
                                                        processData: false,  // tell jQuery not to process the data
                                                        contentType: false,  // tell jQuery not to set contentType
                                                        success : function(data) {

                                                                if(opposite){
                                                                        //send via socket.io
                                                                        socket.emit("sendChatMessage",callingTo,JSON.stringify({type:"FILE", data: data}));

                                                                } else {
                                                                        //direct ....

                                                                }

                                                                /*$("#btnfile").removeAttr('disabled');
                                                                $("#btnmessage").removeAttr('disabled');*/

                                                        }
                                                });

                                        }

                                        reader.readAsDataURL(file);

                                }


                                function sendMessage(){
                                
                                        header='<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"></div>';
                                        photo='<img class="direct-chat-img" src="<?=$vet_photo?>" alt="message user image" />';

                                        /*$("#btnfile").attr('disabled',true);
                                        $("#btnmessage").attr('disabled',true);*/

                                        //message
                                        text= $("#sdmessage").val();
                                        content='<div class="direct-chat-text">'+text+'</div>';
                                        add=header+photo+content+"</div>";
                                        $("#chat-content").append(add);

                                        // scroll down to the bottom. FMRGJ-KR
                                        $("#chat-content").animate({ scrollTop: 9999 }, "slow");

                                        var formData = new FormData();
                                        formData.append('consultId','<?=$consultId?>');
                                        formData.append('message',$('#sdmessage').val() );
                                        if(!opposite) {
                                                formData.append('push',"false");
                                        } else {
                                                formData.append('push',"true");
                                        }
                                        formData.append('ownerId',ownername);

                                        $("#sdmessage").val("");

                                        var message;

                                        var url=base_url+'chat/newmessage';

                                        document.getElementById('sdmessage').focus();

                                        $.ajax({
                                                url : url,
                                                type : 'POST',
                                                data : formData,
                                                processData: false,  // tell jQuery not to process the data
                                                contentType: false,  // tell jQuery not to set contentType
                                                success : function(data) {
                                                        if(opposite){
                                                                //send via socket.io
                                                                socket.emit("sendChatMessage",callingTo,JSON.stringify({type:"TEXT", data: data}));
                                                        } else {
                                                                //send push
                                                        }
                                                        /*$("#btnfile").removeAttr('disabled');
                                                        $("#btnmessage").removeAttr('disabled');*/

                                                }
                                        });
                                        return false;
                                        
                                }
                        </script>
		<? } ?>
	</div><!-- /.box-footer-->
</div><!--/.direct-chat -->