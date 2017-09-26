<?php

namespace ChrisKonnertz\BBCode;

class Tag
{

    /**
     * @var string|null
     */
    public $name = null;

    /**
     * @var string
     */
    public $property = null;

    /**
     * @var bool
     */
    public $opening = true;

    /**
     * @var bool
     */
    public $valid = true;

    /**
     * @var int
     */
    public $position = -1;

    /**
     * Tag constructor.
     *
     * @param string|null $name
     * @param bool        $opening
     */
    public function __construct($name = null, $opening = true)
    {
        $this->name     = $name;
        $this->opening  = $opening;
    }

}