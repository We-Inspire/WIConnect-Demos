require('./modules.js');
require('./config.js');

var httpServer = http.createServer();
httpServer.listen(CONFIG.wsport,'0.0.0.0');

var sockjsServer = new SockJSServer(httpServer);
//var conLogServer = new ConnectionLogServer(sockjsServer);
var ddpServer = new DDPServer(sockjsServer);

// ddpServer.publishSubscription("devices",function(){
// 	var obj = {};
// 	//obj.images = {};
// 	obj.table = "devices";
// 	return obj;
// });
ddpServer.publishSubscription("chats",function(){
	var obj = {};
	obj.table = "chats";
	return obj;
});
var dnode = new DNode;
dnode.on("getUserSession",ddpServer.setUserSession);
dnode.on("getData",ddpServer.dataSetChanged);

require("./control.js");
