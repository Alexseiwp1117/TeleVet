/* create http server with express. FMRGJ-KR*/

var fs = require('fs');
var https = require('https');
var app = require('express')();

var options = {
		key: fs.readFileSync('./key.pem', 'utf8'),
		cert: fs.readFileSync('./server.crt', 'utf8')
};

/* require socket.io and lodash. FMRGJ-KR*/
var server = https.createServer(options, app);
var io = require('socket.io')(server);

var _ = require('lodash-node');

var rooms = [];
var users = [];
var chatrooms = [];
var server_port = 3300;

app.get('/', function (req, res){
        /* send the response to index.html file. FMRGJ-KR*/
        res.sendfile('index.html');
});

io.on('connection', function (socket) {

        /* when connected to the server. FMRGJ-KR*/
        socket.on('connecting', function (name){

                if(_.findIndex(users, { name: name }) !== -1){
                        var currentUser = _.find(users, { name: name });
                        currentUser.socket = socket.id;
                        return;
                }

                users.push({
                        name: name,
                        socket: socket.id
                });

                socket.broadcast.emit('online',name);
                console.log(name + " connected");

        });

        socket.on('notice', function (name, message) {

                var currentUser = _.find(users, { socket: socket.id });
                if (!currentUser) { return; }
                var contact = _.find(users, { name: name });

                if (!contact) {
                        socket.emit('notice_error');
                        return;
                }

                try{
                        io.to(contact.socket)
                            .emit('noticeReceived', currentUser.name , message);
                } catch(e) {

                }
        });

        socket.on('loginVideoRoom', function (_message) {

                var message = JSON.parse(_message);
                // if this socket is already connected,
                // send a failed login message

                if (_.findIndex(rooms, { id: message.consult }) !== -1) {

                        var currentRoom = _.find(rooms, { id: message.consult });
                        var cusers = currentRoom.users;

                        if (_.findIndex(cusers, { name: message.name, socket : socket.id }) !== -1) {
                            //socket.emit('login_error', 'You are already connected.');
                            //return;

                        } else if (_.findIndex(cusers, { name: message.name }) !== -1){
                                var currentUser = _.find(cusers, { name: message.name });
                                currentUser.socket = socket.id;
                            //return;
                        } else {
                                fuser= _.find(users, { name: message.name });
                                cusers.push(fuser);
                        }

                } else {
                        var cusers = [];
                        fuser= _.find(users, { name: message.name });
                        cusers.push(fuser);
                        rooms.push({
                                id: message.consult,
                                users: cusers
                        });
                }

                var res= true;
                if(cusers.length < 2)
                        res = false;

                try{
                        socket.emit('login_successful', res);
                } catch(e) {
                        console.log("login-catch");
                }
                console.log("________________users start_____________________");
                console.log(cusers);
                console.log("________________users end_____________________");
                console.log(message.name + ' logged in to ' + message.consult + " room.");
        });

        socket.on('logoutVideoRoom', function(_message){
                var message = JSON.parse(_message);
                room = _.find(rooms, { id: message.consult });
                console.log(message.name + "is loging out video room");
                cusers= room.users;

                var userindex = _.findIndex( cusers, { socket: socket.id });
                if (userindex !== -1) {
                        console.log(cusers[userindex].name + ' logout video room from ' + room.id );
                        cusers.splice(userindex, 1);
                }

                for(index in cusers){
                        cuser = cusers[index];
                        try{
                                cuser.socket.emit('callEnded',false) ;
                        } catch(e) {

                        }
                }	
                console.log("________________users start_____________________");
                console.log(cusers);
                console.log("________________users end_____________________");
        });

        socket.on('sendMessage', function (_user, message) {

                console.log("____________________");
                console.log(_user);
                console.log(message);
                console.log("____________________");

                user = JSON.parse(_user);
                consult = user.consult;

                var currentRoom = _.find(rooms, { id: consult });
                var cusers = currentRoom.users;
                var currentUser = _.find(cusers, { socket: socket.id });

                if (!currentUser) { return; }

                var contact = _.find(cusers, { name: user.name });
                if (!contact) {
                        try{
                                socket.emit('send_error');
                        } catch(e) {

                        }
                        return;
                }

                try{
                        io.to(contact.socket)
                            .emit('messageReceived', { name: currentUser.name, consult: consult } , message);
                } catch(e) {

                }

                console.log("________________users start_____________________");
                console.log(cusers);
                console.log("________________users end_____________________");
        });

        socket.on('disconnect', function () {

                for (index in rooms )
                {
                        room = rooms[index];
                        cusers= room.users;

                        try
                        {
                                for (i = cusers.length -1; i >= 0; i--)
                                {
                                        user = cusers[i];
                                        if (typeof user === 'undefined')
                                        {
                                                cusers.splice(i, 1);
                                        }
                                }	

                                var userindex = _.findIndex( cusers, { socket: socket.id });

                                if (userindex !== -1) {
                                        console.log(cusers[userindex].name + ' disconnected from ' + room.id );
                                        cusers.splice(userindex, 1);
                                }

                                for(userindex in cusers){
                                        cuser = cusers[userindex];
                                        try{
                                                cuser.socket.emit('callEnded',false) ;
                                        } catch(e) {

                                        }
                                }
                        }
                        catch (e)
                        {

                        }
                }

                for (index in chatrooms )
                {
                        room = chatrooms[index];
                        cusers= room.users;
                        var userindex = _.findIndex( cusers, { socket: socket.id });

                        if (userindex !== -1) {
                                console.log(cusers[userindex].name + ' disconnected from ' + room.id );
                                cusers.splice(userindex, 1);
                        }

                        for(index in cusers){
                                cuser = cusers[index];
                                try{
                                        cuser.socket.emit('chatOpposite',false, false) ;
                                } catch (e){

                                }
                        }	
                }

                var index = _.findIndex( users, { socket: socket.id });

                if (index !== -1) {
                        socket.broadcast.emit('offline',users[index].name);
                        console.log(users[index].name + ' disconnected');
                        users.splice(index, 1);
                }
                console.log("GLOBAL USERS______________________________________DISCONNECT");
                console.log(users);
                console.log("GLOBAL USERS______________________________________DISCONNECT");

        });



        socket.on('logoutChatRoom', function(_message){
                var message = JSON.parse(_message);
                room = _.find(chatrooms, { id: message.consult });
                cusers= room.users;
                var userindex = _.findIndex( cusers, { socket: socket.id });

                if (userindex !== -1) {
                        console.log(cusers[userindex].name + ' disconnected from ' + room.id );
                        cusers.splice(userindex, 1);
                }

                for(index in cusers){
                        cuser = cusers[index];
                        try{
                                cuser.socket.emit('chatOpposite', false, true) ;
                        } catch(e) {

                        }
                }

                console.log("________________users start_____________________");
                console.log(cusers);
                console.log("________________users end_____________________");
        });

        socket.on('loginChatRoom', function (_message, oppositename) {
                var message = JSON.parse(_message);
                // if this socket is already connected,
                // send a failed login message

                if (_.findIndex(chatrooms, { id: message.consult }) !== -1) {

                        var currentRoom = _.find(chatrooms, { id: message.consult });
                        var cusers = currentRoom.users;

                        if (_.findIndex(cusers, { name: message.name, socket : socket.id }) !== -1) {
                            //socket.emit('login_error', 'You are already connected.');
                            //return;
                        } else if (_.findIndex(cusers, { name: message.name }) !== -1){
                                var currentUser = _.find(cusers, { name: message.name });
                                currentUser.socket = socket.id;
                                return;
                        } else {
                                fuser= _.find(users, { name: message.name });
                                cusers.push(fuser);
                        }

                } else {
                        var cusers = [];
                        fuser= _.find(users, { name: message.name });
                        cusers.push(fuser);
                        chatrooms.push({
                                id: message.consult,
                                users: cusers,
                                vc: true
                        });

                }

                var res= true;
                if(cusers.length < 2) {
                        res = false;
                }

                for(index in cusers){
                        cuser = cusers[index];
                        opplive = false;
                        if (_.findIndex(users, { name: oppositename }) !== -1) {
                                opplive = true;
                        }

                        try{
                                io.to(cuser.socket).emit('chatOpposite', res, opplive) ;
                        } catch(e) {

                        }
                }
                console.log("________________users start_____________________");
                console.log(cusers);
                console.log("________________users end_____________________");
                console.log(message.name + ' logged in to ' + message.consult + " chat room.");
        });

        socket.on('sendChatMessage', function (_user, message) {
                user = JSON.parse(_user);
                consult = user.consult;
                console.log(user+ "   " + message);

                var currentRoom = _.find(chatrooms, { id: consult });
                var cusers = currentRoom.users;
                var currentUser = _.find(cusers, { socket: socket.id });
                if (!currentUser) { return; }
                var contact = _.find(cusers, { name: user.name });

                if (!contact) { return; }

                try{
                        io.to(contact.socket)
                            .emit('chatMessageReceived', { name: currentUser.name, consult: consult } , message);
                } catch(e) {

                }

                console.log("________________users start_____________________");
                console.log(cusers);
                console.log("________________users end_____________________");
        });
});

/* waiting the server at portnumber:3000. FMRGJ-KR*/
/*http.listen(3000, function(){
        console.log('listening on *:3000');
});*/
server.listen(server_port, function() {
		console.log('server up and running at %s port', server_port);
});