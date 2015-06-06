var socket;

function init() {
	var host = "ws://crea.arduinogt.com:8000"; // SET THIS TO YOUR SERVER
	try {
		socket = new WebSocket(host);
		log('WebSocket - status '+socket.readyState);
		socket.onopen    = function(msg) {
							   log("Conectado - status "+this.readyState);
						   };
		socket.onmessage = function(msg) {
							   log("Recibido: "+msg.data);
						   };
		socket.onclose   = function(msg) {
							   log("Disconectado - status "+this.readyState);
						   };
	}
	catch(ex){
		log(ex);
	}
	$("msg").focus();
}

function send(){
	var txt,msg;
	txt = $("msg");
	msg = txt.value;
	if(!msg) {
		alert("No puede enviar mensajes vacios");
		return;
	}
	txt.value="";
	txt.focus();
	try {
		socket.send(msg);
		log('Enviado: '+msg);
	} catch(ex) {
		log(ex);
	}
}
function quit(){
	if (socket != null) {
		log("Adios!");
		socket.close();
		socket=null;
	}
}

function reconnect() {
	quit();
	init();
}

// Utilities
function $(id){ return document.getElementById(id); }
function log(msg){ $("log").innerHTML+="<br>"+msg; }
function onkey(event){ if(event.keyCode==13){ send(); } }
