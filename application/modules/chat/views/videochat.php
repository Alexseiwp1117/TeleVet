<link rel="stylesheet" type="text/css" href="<?=base_url()?>package/css/index.css" />

<div class="row">
	<div class="col-md-12">
		<div id="videoContainer"></div>
		<div style="clear:both;"></div>
		<div id="localContainer"></div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<a class="btn btn-success form-control" disabled id="btncall" onclick="letCall()">Call</a>
	</div>
	<div class="col-md-6">
		<a class="btn btn-danger form-control" onclick="hangup()">Hangup</a>
	</div>
</div>

<input type="hidden" id="videoownerId" value="<?=$ownerId?>">
<input type="hidden" id="videoconsultId" value="<?=$consultId?>">

<script type="text/javascript">

	consult = $("#videoconsultId").val();
	ownername = $("#videoownerId").val();

	var session;

	callingTo= JSON.stringify({
				name : ownername,
				consult : consult
			});

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
				position: [0, 0],
				size: [160, 120]
			}
		});

		//try to get offer.
		if(res == true){
			$("#btncall").removeAttr("disabled");
			//socket.emit('sendMessage', callingTo, {type: 'call'});
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

		console.log(JSON.stringify(config));

		session = new cordova.plugins.phonertc.Session(config);
		cordova.plugins.phonertc.setVideoView({
			container: document.getElementById('videoContainer'),
			local: {
				position: [0, 0],
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
			socket.emit('sendMessage', callingTo, { type: 'ignore' });
			document.location.reload();
			$("#videoContainer").html("");
			session.close();
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
				console.log("ignore recieved");
				/*need to add session close for other cases as well*/
				socket.emit('sendMessage', callingTo, { type: 'ignore' });
				document.location.reload();
				session.close();
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
		document.location.reload();
	}

	function letCall(){
		if($("#localContainer").html() !=""){
			socket.emit('sendMessage', callingTo, { type: 'call' });
			$("#btncall").attr("disabled",true);
		}
	}

	window.onresize = function(event){
		var videoContainer = document.getElementById('videoContainer');
		var width = videoContainer.offsetWidth;
		cordova.plugins.phonertc.setVideoView({
			container: document.getElementById('videoContainer'),
			local: {
				position: [0, 0],
				size: [160, 120]
			}
		});
	}
</script>