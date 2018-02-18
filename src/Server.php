<?php

namespace UDP;

class Server
{
    private $socket;
    private $payloads = [];
    private $payloadProcessor;

    const DATAGRAM_SIZE = 512;

    /**
     * Server constructor.
     *
     * @param int $port
     * @param string $address
     */
    public function __construct(int $port = 9001, string $address = '0.0.0.0')
    {
        if (! $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) {
            throw new \RuntimeException('Failed to create socket:' . socket_strerror(socket_last_error()));
        }

        if (! socket_bind($this->socket, $address, $port)) {
            throw new \RuntimeException('Failed to bind socket:' . socket_strerror(socket_last_error()));
        }
    }

    /**
     * Continuously listens for incoming UDP packets.
     */
    public function listen()
    {
        while (true) {
            $data = socket_read($this->socket, static::DATAGRAM_SIZE);
            $parts = explode('|', $data);
            $payloadId = (int) $parts[0];
            $chunkNumber = (int) $parts[1];
            $totalChunks = (int) $parts[2];
            $chunkData = $parts[3];

            $payload = $this->getPayload($payloadId, $totalChunks);
            $payload->addChunk($chunkNumber, $chunkData);

            if ($payload->isComplete()) {
                // we have all the chunks for the payload, return it to caller
                yield $payload->getData();
            }
        }
    }

    /**
     * Finds an existing payload by its ID, or creates and returns a new one if it doesn't exist yet.
     *
     * @param int $payloadId
     * @param int $totalChunks
     * @return Payload
     */
    private function getPayload(int $payloadId, int $totalChunks)
    {
        /** @var Payload $payload */
        foreach ($this->payloads as $payload) {
            if ($payload->payloadId() === $payloadId) {
                return $payload;
            }
        }

        // no existing payload found, create a new one
        $payload = new Payload($payloadId, $totalChunks);
        $this->payloads[] = $payload;

        return $payload;
    }
}
