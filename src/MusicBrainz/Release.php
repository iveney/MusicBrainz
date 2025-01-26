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
     * @var array
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

        $this->id       = $release['id'] ?? '';
        $this->title    = $release['title'] ?? '';
        $this->status   = $release['status'] ?? '';
        $this->quality  = $release['quality'] ?? '';
        $this->language = $release['text-representation']['language'] ?? '';
        $this->script   = $release['text-representation']['script'] ?? '';
        $this->date     = $release['date'] ?? '';
        $this->country  = $release['country'] ?? '';
        $this->barcode  = $release['barcode'] ?? '';
        $this->trackCount = (int)($release['track-count'] ?? 0);

        $artistCredit = $release['artist-credit'] ?? array();
        $this->artists = Artist::fromArray($artistCredit, $brainz);
        $labelInfo = $release['label-info'] ?? array();
        $this->labels = Label::fromArray($labelInfo, $brainz);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the earliest release date
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        if ($this->releaseDate != null) {
            return $this->releaseDate;
        }

        // If there is no release date set, look through the release events
        if (!isset($this->data['date']) && isset($this->data['release-events'])) {
            return $this->getReleaseEventDates($this->data['release-events']);
        }

        if (isset($this->data['date'])) {
            return new \DateTime($this->data['date']);
        }

        return new \DateTime();
    }

    /**
     * @param array $releaseEvents
     *
     * @return \DateTime
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

    /**
     * @return int
     */
    public function getScore()
    {
        return (int)($this->data['score'] ?? 0);
    }
}
