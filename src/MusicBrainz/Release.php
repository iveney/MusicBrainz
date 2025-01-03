<?php

namespace MusicBrainz;

/**
 * Represents a MusicBrainz release object
 * @package MusicBrainz
 */
class Release
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $quality;
    /**
     * @var string
     */
    public $language;
    /**
     * @var string
     */
    public $script;
    /**
     * @var string
     */
    public $date;
    /**
     * @var string
     */
    public $country;
    /**
     * @var string
     */
    public $barcode;

    /**
     * @var int
     */
    public $trackCount = 0;

    /**
     * @var Artist[]
     */
    public $artists = array();

    /**
     * @var Label[]
     */
    public $labels = array();
    
    /**
     * @var
     */
    protected $releaseDate;
    /**
     * @var array
     */
    private $data;

    /**
     * @var MusicBrainz
     */
    protected $brainz;

    /**
     * @param array       $release
     * @param MusicBrainz $brainz
     */
    public function __construct(array $release, MusicBrainz $brainz)
    {
        $this->data   = $release;
        $this->brainz = $brainz;

        $this->id       = isset($release['id']) ? (string)$release['id'] : '';
        $this->title    = isset($release['title']) ? (string)$release['title'] : '';
        $this->status   = isset($release['status']) ? (string)$release['status'] : '';
        $this->quality  = isset($release['quality']) ? (string)$release['quality'] : '';
        $this->language = isset($release['text-representation']['language']) ? (string)$release['text-representation']['language'] : '';
        $this->script   = isset($release['text-representation']['script']) ? (string)$release['text-representation']['script'] : '';
        $this->date     = isset($release['date']) ? (string)$release['date'] : '';
        $this->country  = isset($release['country']) ? (string)$release['country'] : '';
        $this->barcode  = isset($release['barcode']) ? (string)$release['barcode'] : '';
        $this->trackCount = $release['track-count'] ?? 0;

        $artist_info = isset($release['artist-credit']) ? (array)$release['artist-credit'] : array();
        $this->artists = array_map(fn($info) => new Artist($info['artist'], $brainz), $artist_info);
        $labels = isset($release['label-info']) ? $release['label-info'] : array();
        $this->labels = array_map(fn($label) => new Label($label['label'], $brainz), $labels);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get's the earliest release date
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        if (null != $this->releaseDate) {
            return $this->releaseDate;
        }

        // If there is no release date set, look through the release events
        if (!isset($this->data['date']) && isset($this->data['release-events'])) {
            return $this->getReleaseEventDates($this->data['release-events']);
        } elseif (isset($this->data['date'])) {
            return new \DateTime($this->data['date']);
        }

        return new \DateTime();
    }

    /**
     * @param array $releaseEvents
     *
     * @return array
     */
    public function getReleaseEventDates(array $releaseEvents)
    {

        $releaseDate = new \DateTime();

        foreach ($releaseEvents as $releaseEvent) {
            if (isset($releaseEvent['date'])) {
                $releaseDateTmp = new \DateTime($releaseEvent['date']);

                if ($releaseDateTmp < $releaseDate) {
                    $releaseDate = $releaseDateTmp;
                }
            }
        }

        return $releaseDate;
    }
}
