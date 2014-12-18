var io = require('socket.io').listen(4000);

io.sockets.on('connection', function (socket) {
  	socket.on('siteright', function(){
		console.log("HERE");
  		socket.broadcast.emit('siteright');
	});

  	socket.on('siteleft', function(){
		console.log("HERE");
  		socket.broadcast.emit('siteleft');
	});

  	socket.on('siteup', function(){
		console.log("HERE");
  		socket.broadcast.emit('siteup');
	});

  	socket.on('sitedown', function(){
		console.log("HERE");
  		socket.broadcast.emit('sitedown');
	});
});
