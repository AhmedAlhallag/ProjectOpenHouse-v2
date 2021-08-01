<?php
    require_once('vendor/autoload.php');
    use MyApp\Chat ; 
    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        5555
    );

    $server->run();