<?php

namespace MusicBrainz;

/**
 * Represents a MusicBrainz release group
 *
 */
class ReleaseGroup
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var array
     */
    private $data;
    /**
     * @var MusicBrainz
     */
    private $brainz;
    /**
     * @var Release[]
     */
    private $releases = array();
    /**
     * @var Artist[]
     */
    private $artists = array();
    /**
     * @var Tag[]
     */
    private $tags = array();


    /**
     * @param array       $releaseGroup
     * @param MusicBrainz $brainz
     */
    public function __construct(array $releaseGroup, MusicBrainz $brainz)
    {
        $this->data   = $releaseGroup;
        $this->brainz = $brainz;

        $this->id = $releaseGroup['id'] ?? '';
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->data['title'];
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return (int)($this->data['score'] ?? 0);
    }

    /**
     * @return Release[]
     */
    public function getReleases()
    {
        if (!empty($this->releases)) {
            return $this->releases;
        }

        foreach ($this->data['releases'] as $release) {
            array_push($this->releases, new Release($release, $this->brainz));
        }

        return $this->releases;
    }

    /**
     * @return Artist[]
     */
    public function getArtists()
    {
        if (!empty($this->artists)) {
            return $this->artists;
        }

        $artistCredit = $this->data["artist-credit"] ?? array();
        $this->artists = Artist::fromArray($artistCredit, $this->brainz);

        return $this->artists;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->data['count'];
    }

    /**
     * @return string
     */
    public function getPrimaryType()
    {
        return $this->data['primary-type'] ?? '';
    }

    /**
     * @return string
     */
    public function getFirstReleaseDate()
    {
        return $this->data['first-release-date'] ?? '';
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        if (!empty($this->tags)) {
            return $this->tags;
        }

        $this->tags = array_map(fn($tag) => new Tag($tag, $this->brainz), $this->data["tags"] ?? array());

        return $this->tags;
    }
}
