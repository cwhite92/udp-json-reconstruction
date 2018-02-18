<?php

require_once 'vendor/autoload.php';

use UDP\Server;

$server = new Server;
foreach ($server->listen() as $payload) {
    echo "Received payload: $payload\n\n";
}
