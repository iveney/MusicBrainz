<?php

namespace MusicBrainz;

/**
 * Represents a MusicBrainz label object
 */
class Label
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;
    /**
     * @var array
     */
    public $aliases;
    /**
     * @var int
     */
    public $score;
    /**
     * @var string
     */
    public $sortName;
    /**
     * @var string
     */
    public $country;

    /**
     * @var array
     */
    private array $data;

    /**
     * @var MusicBrainz
     */
    protected MusicBrainz $brainz;

    /**
     * @param array       $label
     * @param MusicBrainz $brainz
     */
    public function __construct(array $label, MusicBrainz $brainz)
    {
        $this->data   = $label;
        $this->brainz = $brainz;

        $this->id       = $label['id'] ?? '';
        $this->type     = $label['type'] ?? '';
        $this->score    = (int)($label['score'] ?? 0);
        $this->sortName = $label['sort-name'] ?? '';
        $this->name     = $label['name'] ?? '';
        $this->country  = $label['country'] ?? '';
        $this->aliases  = $label['aliases'] ?? array();
    }

    /**
     * converts label-info array to Label[]
     * @return Label[]
     */
    public static function fromArray(array $labelInfo, MusicBrainz $brainz): array
    {
        return array_map(fn($info) => new Label($info['label'] ?? array(), $brainz), $labelInfo);
    }

    /**
     * Util function to implode Label[] as a string
     * @return string
     */
    public static function arrayToString(array $labels, string $concat = ", "): string
    {
        $labels = array_map(fn($label) => $label->name, $labels);
        return implode($concat, $labels);
    }
}
