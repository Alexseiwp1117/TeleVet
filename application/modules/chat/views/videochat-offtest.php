<!DOCTYPE html>
<html>
    <head>
            <meta charset="utf-8" />
            <meta name="format-detection" content="telephone=no" />
            <meta name="msapplication-tap-highlight" content="no" />
            <!-- WARNING: for iOS 7, remove the width=device-width and height=device-height attributes. See https://issues.apache.org/jira/browse/CB-4323 -->
            <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
            <link rel="stylesheet" type="text/css" href="<?=base_url()?>package/css/index.css" />
            <title>PhoneRTC Demo</title>
    </head>
    <body>

		<div class="row">
			<div class="col-md-8">
				<div id="videoContainer"></div>
			</div>
			<div class="col-md-4">
				<div id="localContainer"></div>
			</div>
		</div>
            

		<input type="hidden" id="videovetId" value="<?=$vetId?>">
		<input type="hidden" id="videoownerId" value="<?=$ownerId?>">
		<input type="hidden" id="videoconsultId" value="<?=$consultId?>">

        <script type="text/javascript" src="<?=base_url()?>package/cordova.js"></script>
        <script src="<?=base_url()?>src/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?=base_url()?>src/dist/js/socket.io.js"></script>

        <script type="text/javascript">

			var socket = io.connect('https://stage.gettelevet.com:3000'),
                session, username, callingTo, duplicateMessages = [];
            
            username = $("#videovetId").val();
			consult = $("#videoconsultId").val();
			ownername = $("#videoownerId").val();

			callingTo= JSON.stringify({
						name : ownername,
						consult : consult
					});


			socket.emit('connecting',username);

            socket.emit('loginVideoRoom', JSON.stringify({
				name : username,
				consult : consult
			}));
   
			socket.on('login_error', function(message){
				console.log('login_error');
			});

			socket.on('login_successful', function(res){
				console.log('login_successful');

				cordova.plugins.phonertc.setVideoView({
					container: document.getElementById('videoContainer'),
					local: {
						position: [480, 360],
						size: [160, 120]
					}
				});

				//try to get offer.
				if(res == true){
					socket.emit('sendMessage', callingTo, {type: 'call'});
				} else {

				}
			});

			socket.on('messageReceived', onVideoMessageReceived);

			socket.on('callEnded', function(res){
				alert("Call Ended.");
				document.location.href=base_url + "appointment/detail/" + consult;

			});

			socket.on('disconnect', function(){
				session.close();
			});

			function call(isInitiator){
				startCall(isInitiator);
			}

			function startCall(isInitiator){
				var config = {
					isInitiator: isInitiator,
					turn: {
						host: "turn:stage.gettelevet.com",
						username: "rui",
						password: "garcia"
					},
					streams: {
						audio: true,
						video: true
					}
				}

				session = new cordova.plugins.phonertc.Session(config);
				cordova.plugins.phonertc.setVideoView({
					container: document.getElementById('videoContainer'),
					local: {
						position: [480, 360],
						size: [160, 120]
					}
				});
				session.on('sendMessage', function (data) {
					socket.emit('sendMessage', callingTo, { 
					  type: 'phonertc_handshake',
					  data: JSON.stringify(data)
					});
				});
				
				session.on('answer', function () {
					console.log('answered');
				});
				session.on('disconnect', function () {
					session.close();
					socket.emit('sendMessage', callingTo, { type: 'ignore' });
				});
				session.call();
			}

			function onVideoMessageReceived(rec, message){
				switch (message.type){
					case 'call':
						call(false);
						setTimeout(function(){
							 socket.emit('sendMessage', callingTo, { type: 'answer' });
						}, 1500);

						break;
					case 'answer':
						console.log(username + ' he answered');
						call(true);
						break;
					case 'phonertc_handshake':
						if (duplicateMessages.indexOf(message.data) === -1) {
							session.receiveMessage(JSON.parse(message.data));
							duplicateMessages.push(message.data);
						}
						break;
					case 'ignore':
						/*need to add session close for other cases as well*/
						session.close();
						socket.emit('sendMessage', callingTo, { type: 'ignore' });
						break;
				}
			}
			function logoutVideoRoom(){
				socket.emit('logoutVideoRoom', JSON.stringify({
						name : username,
						consult : consult
					}));
				document.location.href=base_url + "appointment/detail/" + consult;
			}

			function hangup(){
				socket.emit('sendMessage', callingTo, { type: 'ignore' });
			}

        </script>
    </body>
</html>
<!-- need to add AWS tutorial-->
