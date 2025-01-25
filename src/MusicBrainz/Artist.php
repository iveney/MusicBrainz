<?php

namespace MusicBrainz;

/**
 * Represents a MusicBrainz artist object
 * @package MusicBrainz
 */
class Artist
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var MusicBrainz
     */
    protected $brainz;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $sortName;
    /**
     * @var string
     */
    public $gender;
    /**
     * @var string
     */
    public $country;
    /**
     * @var string
     */
    private $beginDate;
    /**
     * @var string
     */
    private $endDate;

    /**
     * @var string
     */
    public $disambiguation;

    /**
     * @var array
     */
    private $data;
    /**
     * @var array
     */
    private $releases;

    /**
     * @param array       $artist
     * @param MusicBrainz $brainz
     *
     * @throws Exception
     */
    public function __construct(array $artist, MusicBrainz $brainz)
    {
        if (!isset($artist['id']) || isset($artist['id']) && !$brainz->isValidMBID($artist['id'])) {
            throw new Exception('Can not create artist object. Missing valid MBID');
        }

        $this->data   = $artist;
        $this->brainz = $brainz;

        $this->id        = $artist['id'] ?? '';
        $this->type      = $artist['type'] ?? '';
        $this->name      = $artist['name'] ?? '';
        $this->sortName  = $artist['sort-name'] ?? '';
        $this->gender    = $artist['gender'] ?? '';
        $this->country   = $artist['country'] ?? '';
        $this->disambiguation   = $artist['disambiguation'] ?? '';
        $this->beginDate = $artist['life-span']['begin'] ?? null;
        $this->endDate   = $artist['life-span']['ended'] ?? null;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return isset($this->data['score']) ? (int)$this->data['score'] : 0;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getReleases()
    {
        if (null === $this->releases) {
            $this->releases = $this->brainz->browseRelease('artist', $this->getId());
        }

        return $this->releases;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getArea() {
        return $this->data["area"] ?? array();
    }

    /**
     * @return array
     */
    public function getAliases() {
        return $this->data["aliases"] ?? array();
    }

    /**
     * converts an artist-credit array to Artist[]
     * @return Artist[]
     */ 
    public static function fromArray(array $artistCredit, MusicBrainz $brainz) {
        return array_map(fn($credit) => new Artist($credit['artist'], $brainz), $artistCredit);
    }

    /**
     * Util function to implode Artist[] as a string
     * @return string
     */ 
    public static function arrayToString(array $artists, string $concat = " & ") {
        $artistNames = array_map(fn($artist) => $artist->name, $artists);
        return implode($concat, $artistNames);
    }
}

