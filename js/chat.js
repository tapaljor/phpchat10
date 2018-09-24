$(document).ready(function() {

	//create a new WebSocket object.
	var wsUri = "ws://10.0.1.11:3022";
	websocket = new WebSocket(wsUri); 

	websocket.onopen = function(ev) {
		$(".newmessage").html('<span style="color: green;">Connected</span>'); //Client information if its connected successfully
	} 

	$("#messageform").submit( function(ev) {

		ev.preventDefault();

		var mymessage = $('#message').val(); //get message text
		var sender = $('#sender').val(); //get sender id
		var destination = $('#destination').val(); //get user name destination to be sent to
		var senderusername = $('#senderusername').val(); //get user sender username 
		var time = $('#time').val(); //get user name destination to be sent to

		//prepare json data
		var packet = {
			sender: sender,
			destination: destination,
			senderusername: senderusername,
			message: mymessage,
			time: time,
		};

		//convert and send data to server
		var data = JSON.stringify(packet);
		websocket.send(data);

		$("#message").val('');//Once message is sent the textarea is cleaned
		return false;
	});
	websocket.onmessage = function(ev) {

		//Coming from server, broadcasting all message and fellow code will sort out your msg
		ev.preventDefault();

		//msg array is coming from websocket from different IPs or computers
		var packet = JSON.parse(ev.data); //PHP sends Json data
console.log(packet);
		var sender = packet.sender; //sender 
		var destination = packet.destination; //extracting destination
		var senderusername = packet.senderusername; //extracting sender username
		var time = packet.time; //extracting time (server) message send 
		var message = packet.message; //message text

		var currentuser = $("#currentuser").val();//This variable holds user your ID which is unique, where you are logged in, coming from head.php 
		var destinationtab = $("#destination").val();//This is another TAB user ID whom you are chatting with on your browser 

		if(message != '') {

			//Receiving messages matching your user ID with particular user at particular tab
			if ( destinationtab != '' && destination === currentuser && sender === destinationtab) { 
				$('#messagebox').prepend("<div class='incoming'>"+senderusername+': <i>'+message+"</i></div>");
			}
			//Receiving message which is sent by you too, received for ya too
			if ( sender === currentuser && destination === destinationtab ) {
				$('#messagebox').prepend("<div class='incoming'>You: <i>"+message+"</i></div>");
			}
			//Receiving for ya when you have not selected user to chat, where destination host is empty
			if ( destination === currentuser ) {

				$.ajax ({
					type: "POST",
					url: "messagealert.php",
					data: { time: time, destination: destination, sender: sender, message: message },
					success: function(data) {
						$(".newmessage").html(data);
					}
				});
			}
		}
	};
	websocket.onerror = function(ev) { 

		ev.preventDefault(); 
		$(".notification").load("loading.html");
	}; 
	websocket.onclose = function(ev) {
		
		ev.preventDefault();

		$.ajax ({
			beforeSend:  function() {
				$(".notification").load('loading.html');
			},	
			success: function(html) {
				if ( ev.isTrusted == true) {
					$(".notification").html('<span style="color: red;">-</span>');
				} 
			}
		});
	}; 
});
