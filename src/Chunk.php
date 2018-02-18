<?php

namespace UDP;

class Chunk
{
    private $chunkNumber;
    private $data;

    /**
     * Chunk constructor.
     *
     * @param int $chunkNumber
     * @param string $data
     */
    public function __construct(int $chunkNumber, string $data)
    {
        $this->chunkNumber = $chunkNumber;
        $this->data = $data;
    }

    /**
     * Returns this chunk's number.
     *
     * @return int
     */
    public function chunkNumber()
    {
        return $this->chunkNumber;
    }

    /**
     * Returns this chunk's data.
     *
     * @return string
     */
    public function data()
    {
        return $this->data;
    }
}
