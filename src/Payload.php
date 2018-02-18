<?php

namespace UDP;

class Payload
{
    private $payloadId;
    private $totalChunks;
    private $chunks = [];

    /**
     * Payload constructor.
     *
     * @param int $payloadId
     * @param int $totalChunks
     */
    public function __construct(int $payloadId, int $totalChunks)
    {
        $this->payloadId = $payloadId;
        $this->totalChunks = $totalChunks;
    }

    /**
     * Adds a chunk to the payload.
     *
     * @param int $chunkNumber
     * @param string $chunkData
     */
    public function addChunk(int $chunkNumber, string $chunkData)
    {
        $chunk = new Chunk($chunkNumber, $chunkData);
        $this->chunks[] = $chunk;
    }

    /**
     * Determines if the payload is complete. The payload is complete when we have all of the chunks that make up the
     * payload.
     *
     * @return bool
     */
    public function isComplete()
    {
        return $this->totalChunks === count($this->chunks);
    }

    /**
     * Returns the data for the payload by sorting the chunks into order and appending their data together.
     *
     * @return string
     */
    public function getData()
    {
        if (! $this->isComplete()) {
            throw new \RuntimeException('Cannot retrieve data for a payload that is not complete');
        }

        // sort the chunks into order
        uasort($this->chunks, function (Chunk $a, Chunk $b) {
            return $a->chunkNumber() <=> $b->chunkNumber();
        });

        $data = '';

        /** @var Chunk $chunk */
        foreach ($this->chunks as $chunk) {
            $data .= $chunk->data();
        }

        return $data;
    }

    /**
     * Returns the payload ID.
     *
     * @return int
     */
    public function payloadId()
    {
        return $this->payloadId;
    }
}
