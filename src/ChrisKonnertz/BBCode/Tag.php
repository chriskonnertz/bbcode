<?php

namespace ChrisKonnertz\BBCode;

/**
 * This class represents a single BBCode tag.
 * It is just a simple class used for storing tag.
 *
 * @package ChrisKonnertz\BBCode
 */
class Tag
{

    /**
     * The name of the tag
     *
     * @var string|null
     */
    public $name = null;

    /**
     * The value of the property
     *
     * @var string
     */
    public $property = null;

    /**
     * Is this an opening tag (true)?
     *
     * @var bool
     */
    public $opening = true;

    /**
     * Is this tag valid?
     *
     * @var bool
     */
    public $valid = true;

    /**
     * Position of this tag inside the whole BBCode string
     *
     * @var int
     */
    public $position = -1;

    /**
     * Tag constructor.
     *
     * @param string|null $name    The name of the tag
     * @param bool        $opening Is this an opening tag (true)?
     */
    public function __construct($name = null, $opening = true)
    {
        if ($name !== null and ! is_string($name)) {
            throw new \InvalidArgumentException('The "name" parameter has to be of type string');
        }
        if (! is_bool($opening)) {
            throw new \InvalidArgumentException('The "opening" parameter has to be of type bool');
        }

        $this->name     = $name;
        $this->opening  = $opening;
    }

}