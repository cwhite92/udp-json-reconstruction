<?php

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

// The message you want to send to the server.
$message = json_encode([
    'timestamp' => date('c'),
    'message' => $argv[1]
]);

// message ID is 5 bytes
// then 1 byte for |
// chunk number is 2 bytes
// then 1 byte for |
// total chunks is 2 bytes
// then 1 byte for |
// message payload has 512 - 5 - 1 - 2 - 1 - 2 - 1 bytes to work with, so split it into 500 byte chunks
$messageId = mt_rand(10000, 99999);
$chunks = str_split($message, 500);
$currentChunk = 1;
$totalChunks = count($chunks);

foreach ($chunks as $chunk) {
    // prepend the chunk with the message id, the chunk number, and the total number of chunks
    $chunk = "$messageId|$currentChunk|$totalChunks|$chunk";

    // send the chunk to the server
    socket_sendto($socket, $chunk, strlen($chunk), 0, '127.0.0.1', 9001);

    // increment current chunk for the next iteration
    $currentChunk++;
}
