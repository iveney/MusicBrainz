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

    /**
     * Util function to implode Tag[] as a string
     * @return string
     */ 
    public static function arrayToString(array $tags, string $concat = ", ") {
        $tagCounts = array_map(fn($tag) => "$tag->name ($tag->count)", $tags);
        return implode($concat, $tagCounts);
    }
}
