<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// Declare a queue. A queue will only be created if it doesn't exist already.
$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received "', $msg->body, "\"\n";
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

// The below code will block while $channel has callbacks.
while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
