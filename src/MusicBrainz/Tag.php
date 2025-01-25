<?php

namespace MusicBrainz;

/**
 * Represents a MusicBrainz tag object
 * @package MusicBrainz
 */
class Tag
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $count;
    /**
     * @var array
     */
    private $data;
    /**
     * @var MusicBrainz
     */
    private $brainz;

    /**
     * @param array       $tag
     * @param MusicBrainz $brainz
     */
    public function __construct(array $tag, MusicBrainz $brainz)
    {
        $this->data   = $tag;
        $this->brainz = $brainz;

        $this->name  = $tag['name'] ?? '';
        $this->count = $tag['count'] ?? '';
    }
}
