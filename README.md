Proof of concept for sending a JSON payload over UDP with a maximum datagram size of 512 bytes.

This works by splitting the JSON into 512 byte chunks, where the data looks like this:

    <payloadId>|<chunkNumber>|<totalChunks>|<chunkData>
    
`payloadId` is the random ID given to the JSON payload.  
`chunkNumber` is a sequential integer representing which chunk is being sent. This is required to re-assemble the chunks
into the original JSON payload on the server.  
`totalChunks` is the total number of chunks that the JSON payload got split into. This is required so that the server
knows when it has received all of the chunks for a JSON payload.  
`chunkData` is the actual data of the chunk, which represents a portion of the whole JSON payload.

`client.php` will take care of splitting the payload into chunks and forwarding them to the UDP server.  
`server.php` takes care of running a UDP server (on port 9001) to receive the chunks and re-construct the payloads.

To use this, run `php server.php` to start listening, and then run `php client.php <message>` in another terminal.
`<message>` will be added to a JSON payload for you. If everything works, the server should print out the reconstructed
JSON.
