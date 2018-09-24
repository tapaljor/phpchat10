<?php

require __DIR__ .'/vendor/autoload.php';
require_once("classes/class.chat.php");

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use ChatApp\Chat;

$server = IoServer::factory(
    	new HttpServer(
            	new WsServer(
	   		new Chat()
		)
	),
	3022	
);

$server->run();
