CONNECTION_CONNECTING = 1;
CONNECTION_CONNECTED = 2;
CONNECTION_CLOSED = 3;
CONNECTION_FAILED = 4;
CONNECTION_WAITING = 5;


/*Taken from meteor's DDP protocol and adapted*/
var ClientStream = function(url){
	var self = this;
	self.url = url;
	
	self.currentStatus  = {
		connected: false,
		status: CONNECTION_CONNECTING,
		retryCount: 0,
	}

	self.retry = {
		timeout: 1000,
		exponent: 2,
		max: 60000,
		min: 10,
		min_count: 3,
		random: 0.5 //To avoid reconnecting all clients at the same time
	}

	self.CONNECT_TIMEOUT = 10000;
	self.HEARTBEAT_TIMEOUT = 60000;

	self.retryTimer = null;
	self.connectionTimer = null;
	self.heartbeatTimer = null;
	
	self.callbacks = [];

	self.socket = null;

	//Eval registered callbacks
	self.on = function(name, callback){
		if(!self.callbacks[name]){
			self.callbacks[name] = [];
		}

		self.callbacks[name].push(callback);
	}

	//Starts the SockJS connection and add listeners to it
	//In addition add a connection timer, which checks if the connection was build up successfully
	self.startConnection = function(){
		self.cleanup();
		self.socket = new SockJS(self.url);

		self.socket.onmessage = function(data){
			self.heartbeatReceived();
			if(self.currentStatus.status == CONNECTION_CONNECTING){
				self.connected(data.data);
			}else if(self.currentStatus.status == CONNECTION_CONNECTED){
				_.each(self.callbacks.message, function(callback){callback(data.data);});
			}
		}

		self.socket.onclose = function (){
			self.connectionLost();
		}

		self.socket.onerror = function(){
			console.error("ScckJSError",arguments);
		}

		self.socket.onheartbeat = function(){
			self.heartbeatReceived();
		}

		if(self.connectionTimer)
			clearTimeout(self.connectionTimer);

		self.connectionTimer = setTimeout(self.connectionLost,self.CONNECT_TIMEOUT);
	}

	//If you want to reconnect manually
	self.reconnect = function(){
		if(self.currentStatus.status == CONNECTION_CONNECTED){
			return;
		}
		
		if(self.currentStatus.status == CONNECTION_CONNECTING){
			self.connectionLost();
		}

		if(self.retryTimer){
			clearTimeout(self.retryTimer);
		}

		self.retryTimer = null;
		self.currentStatus.retryCount -= 1; //manual retries does not count
		self.retryNow();
	}

	//If we do not get a heartbeat for some time we close the connection
	self.heartbeatTimeout = function(){
		self.connectionLost();
	}

	//Resets the timeout for calling hearbeatTimeout
	self.heartbeatReceived = function(){
		if(self.heartbeatTimer)
			clearTimeout(self.heartbeatTimer);
	
		self.heartbeatTimer = setTimeout(self.heartbeatTimeout,self.HEARTBEAT_TIMEOUT);
	}


	//Method to disconnect completly from the socket
	self.disconnect = function(){
		self.cleanup();
		
		if(self.retryTimer){
			clearTimeout(self.retryTimer);
			self.retryTimer = null;
		}
		
		self.currentStatus.connected = false;
		self.currentStatus.status = CONNECTION_CLOSED;
		self.currentStatus.retryCount = 0;	

		console.log("SockJS: Disconnected");	
	}

	//Is called after getting the first message after connecting
	self.connected = function(data){
		if(self.connectionTimer){
			clearTimeout(self.connectionTimer);
			self.connectionTimer = null;
		}

		console.log("SockJS: Connected");

		//This should not happen, but if we are safe :P
		if(self.currentStatus.connected)
			return;

		
		try{
			var init_data = JSON.parse(data);
		} catch(err){
			console.error("SockJS There was something wrong with the init message.");
		}
		
		if(init_data && init_data.server_id){
			self.serverid = init_data.server_id;
		}else{
			console.error("SockJS: Invalid init data", init_data);
		} 

		self.currentStatus.status = CONNECTION_CONNECTED;
		self.currentStatus.connected = true;
		self.currentStatus.retryCount = 0;	//Reset it, if we are connected
	
		_.each(self.callbacks.reset, function(callback){callback();});
		 

	}

	//Sends a message to the server
	self.send = function(data){
		if(self.currentStatus.connected){
			self.socket.send(data);
		}
	}

	//Cleans the current session and tries to reconnect after some time	
	self.connectionLost = function(){
		self.cleanup();
		self.retryLater();
	}
	
	//Tries to recconect after some time
	self.retryLater = function(){
		var timeout = self.calculateRetryTimeout(self.currentStatus.retryCount);
		if(self.retryTimer)
			clearTimeout(self.retryTimer);
		self.retryTimer = setTimeout(self.retryNow,timeout);
		self.currentStatus.status = CONNECTION_WAITING;
		self.currentStatus.connected = false;
	}

	//Reconnects to the server
	self.retryNow = function(){
		self.currentStatus.retryCount++;
		self.currentStatus.status = CONNECTION_CONNECTING;
		self.currentStatus.connected = false;
		console.log("SockJS: trying to reconnect");
		self.startConnection();
	}

	//Calculates the time until the next reconnect
	self.calculateRetryTimeout = function(count){
		if(count < self.retry.min_count)
			return self.retry.min;

		var timeout = Math.min(self.retry.max,self.retry.timeout * Math.pow(self.retry.exponent,count));
		timeout = timeout * (Math.random()*self.retry.random +(1-self.retry.random/2));
		return timeout;		
	}

	//Cleans the current socket connection
	self.cleanup = function(){
		if(self.connectionTimer){
			clearTimeout(self.connectionTimer);
			self.connectionTimer = null;
		}

		if(self.heartbeatTimer){
			clearTimeout(self.heartbeatTimer);
			self.heartbeatTimer = null;
		}
		if(self.socket){
			self.socket.onmessage = self.socket.onclose = self.socket.onerror = self.socket.onheartbeat = function(){}
			self.socket.close();
			self.socket = null;
		}
	}
	
	//Start connection
	self.startConnection();
}

/*Includes gerneal DDP functions and settings*/
function DDP(){}

DDP.versions = ["pre1"];

DDP.parse = function(message){
	try{
		var msg = JSON.parse(message);
	}catch (e){
		return null;
	}
	
	if(msg === null || typeof msg !== 'object'){
		return null;
	}

	return msg;
}

DDP.stringify = function(obj){
	return JSON.stringify(obj);
}




var Tracker = function(options){
	var self = this;
	self.callback = options.callback;
	self.message = options.message;
	self.connection = options.connection;
	self.onResultReceived = options.onResultReceived;
	self.trackerId = options.trackerId;

	self.messageSent = false;
	self.result = null;

	self.connection.tracker[self.trackerId] = self;

	self.sendMessage = function(){
		if(self.gotResult()){
			console.error("Not allowed to send a method, which was sent successfully");
			return;
		}
		self.messageSent = true;
		
		self.connection.send(self.message);		
	}

	self.invokeCallback = function(){
		self.callback(self.result);
		delete self.connection.tracker[self.trackerId];
	}
	
	self.receiveResult = function(result){
		if(self.gotResult()){
			console.error("Cannot get result more than once");
		}
		self.result = result;
		self.onResultReceived(result);
		self.invokeCallback();
	}

	self.gotResult = function(){
		return !!self.result;
	}
}


var Connection = function(url){
	var self = this;

	self.trackers = [];

	self.stream = new ClientStream(url);

	//holds subscriptions
	self.subscriptions = {};
	self.broadcasts = {};
	
	//local documents: format table_name {row_id => {fields}, row_id => {fields}, ...}
	self.collections = {};

	self.listeners = [];

	/****************************************************************
			Gerneral functions
	****************************************************************/
	
	var onMessage = function(message){
		try{
			var data = DDP.parse(message);
		}catch(e){
			console.error("Parsing DDP not succeeded",e);
			return;
		}

		if(data === null || !data.msg){
			console.error("Message invalid", msg);
			return;
		}

		switch(data.msg){
			case 'connected':
				self.connected(data);
				break;
			case 'failed':
				self.failed(data);
				break;
			case 'nosub':
				self.nosub(data);
				break;
			case 'added':
				self.added(data);
				break;
			case 'changed':
				self.changed(data);
				break;
			case 'removed':
				self.removed(data);
				break;
			case 'ready':
				self.ready(data);
				break;
			case 'addedBefore':
				//self.addedBefore(data); Not implemented now
				break;
			case 'movedBefore':
				//self.movedBefore(data); Not implemented now
				break;
			case 'result':
				self.result(data);
				break;
			case 'updated':
				//self.updated(data); Not needed
				break;
			case 'broadcast':
				self.receiveBroadcast(data);
				break;
			case 'nosubBroadcasat':
				self.nosubBroadcasat(data);
				break;
			default:
				console.error("Unknown message type");
		}

	}

	var onReset = function(){
		var msg = {msg: 'connect'};
		msg.version = DDP.versions[0];
		msg.support = DDP.versions
		self.send(msg);
	}

	self.notifyListeners = function(coll, doc, id, message){
		if(!self.listeners[coll]){
			self.listeners[coll] = [];
			return;
		}

		var sendDoc = JSON.parse(JSON.stringify(doc)); //Do not send the original doc
		sendDoc.id = id;

		for(var i = 0; i < self.listeners[coll].length; i++){

			self.listeners[coll][i](sendDoc, message);
		}
	}

	self.on = function(name, cb){
		if(!self.listeners[name]){
			self.listeners[name] = [];
		}

		self.listeners[name].push(cb);
	}

	//Every process gets a new id
	self.idControl = function(){
		var id = 0;
		return{
			next: function(){
				return ++id + '';
			}
		}
	}();

	/****************************************************************
			Connection functions
	****************************************************************/
	self.connected = function(msg){
		console.log("DDP: connected");
		self.session = msg.session;
		
		//1. reconnect then  Send all messages, which were not send correctly
		//2. look if data has been changed for tables, which were subscribed
		//3. Update content

		jQuery.ajax({
			method: 'POST',
			url: 'register',
			data: {nodeid: self.session}
		});
		
		_.each(self.subscriptions, function(sub,id){
			self.send({
				msg: 'sub',
				id: id,
				name: sub.name,
				params: sub.params
			});
		});

		_.each(self.broadcasts, function(channel,id){
			self.send({
				msg: 'subBroadcast',
				id: id,
				channel: channel,
			});
		});

		if(self.listeners['connected']) {

			for(var i = 0; i < self.listeners['connected'].length; i++){
			     self.listeners['connected'][i](msg);
			}
	     
	    }
	}

	self.failed = function(data){
		console.error("DDP connection failed. You need version", data.version);
		self.stream.disconnect();
	}

	self.send = function(obj){
		self.stream.send(DDP.stringify(obj));
	}

	self.close = function(){
		self.stream.disconnect();
	}

	self.reconnect = function(){
		self.stream.reconnect();
	}
	/****************************************************************
			Document changing functions
	****************************************************************/
	self.added = function(data){
		var collection = data.collection;
		var id = data.id;
		var fields = data.fields;

		if(!this.collections[collection]){
			this.collections[collection] = {};
		}

		this.collections[collection][id] = data.fields;

		self.notifyListeners(collection, this.collections[collection][id], id, data.msg);
	}

	self.changed = function(data){
		var collection = data.collection;
		var id = data.id;
		var fields = data.fields;
		//var cleared = data.cleared;

		if(fields){
			for(var key in fields){
				this.collections[collection][id][key] = fields[key];
			}	
		}

		/*if(cleared){
			for(var i = 0; i < cleared.length; i++){
				delete this.collections[collection][id][cleared[i]];
			}
		}*/
		
		self.notifyListeners(collection, this.collections[collection][id], id, data.msg);
	}

	self.removed = function(data){
		var collection = data.collection;
		var id = data.id;
		
		var copy = JSON.parse(JSON.stringify(this.collections[collection][id]));
		delete this.collections[collection][id];
	
		self.notifyListeners(collection, copy, id, data.msg);
	}

	/****************************************************************
			Document subscribe functions
	****************************************************************/
	self.subscribe = function(name){
		var id = self.idControl.next();
		var params = Array.prototype.slice.call(arguments,1);
	
		var msg = {
			msg: 'sub',
			id: id,
			name: name,
			params: params
		}

		self.subscriptions[id] = {
			name: name,
			params: params
		};

		self.send(msg);
		
		var handle = {
			stop: function(){
				if(!self.subscriptions[id]){
					return;	
				}
				self.send({msg: 'unsub', id:id});
				delete self.subscriptions[id];
			}
		}	

		return handle;
	}
	
	self.nosub = function(data){
		var id = data.id;
		
		if(!self.subscriptions[id]){
			return;
		}
		
		delete self.subscriptions[id];
		
		if(data.error){
			var error = data.error;
			var reason = data.reason || "";
			var details = data.details || "";
			console.log(error.reason || 'Subscription not found');
		}	
	}	

	self.subscribeBroadcast = function (channel) {
		var id = self.idControl.next();
		var msg = {
			msg: 'subBroadcast',
			id: id,
			channel: channel
		}

		self.broadcasts[id] = channel;

		self.send(msg);
		
		var handle = {
			stop: function(){
				if(!self.broadcasts[id]){
					return;	
				}
				self.send({msg: 'unsubBroadcast', id:id});
				delete self.broadcasts[id];
			}
		}	

		return handle;
	}

	self.nosubBroadcasat = function(data){
		var id = data.id;
		
		if(!self.broadcasts[id]){
			return;
		}
		
		delete self.broadcasts[id];
		
		if(data.error){
			var error = data.error;
			var reason = data.reason || "";
			var details = data.details || "";
			console.log(error.reason || 'Broadcast not found');
		}	
	}	

	self.receiveBroadcast = function(data){
		self.notifyListeners('broadcast', data, null, data.msg);
	}

	self.sendBroadcast = function(channel, data){
		var msg = {
			msg: 'broadcast',
			channel: channel,
			data: data
		}

		self.send(msg);
	}


	/****************************************************************
			Remote Procedure Call functions
	****************************************************************/


	self.apply = function(name, args, options, callback){
	
			
		if(!callback && typeof options === 'function'){
			callback = options;
			options = {};
		}

		options = options || {};
		
		
		if(!callback){
			callback = function(){};
		}

		var id = self.idControl.next();
		
		var tracker = new Tracker({
			methodId: id,
			callback: callback,
			connection: self,
			onResultReceived: options.onResultReceived,
			message: {
				msg: 'method',
				method: name,
				params: args,
				id: id
			}
		});

		self.trackers.push(tracker);
		tracker.sendMessage();
	}
	

	self.result = function(data){
		var id = data.id;

		var tracker;
		for(var i = 0; i < trackers.length; i++){
			if(trackers[i].methodId == id){
				tracker = trackers[i];
				break;
			}
		}
		
		if(!tracker){
			return;
		}


		if(data.error){
			var error = data.error;
			var reason = data.reason || "";
			var details = data.details || "";
			tracker.receiveResult("Error: " + error);
		}else{
			tracker.receiveResult(undefined, data.result);
		}
	}
	
	//Add Listeners to stream
	self.stream.on('message', onMessage);
	self.stream.on('reset', onReset);

}

var url = "http://"+location.hostname+":3000/sockjs";
var connection = new Connection(url);
