<?php

namespace ChrisKonnertz\BBCode\Tags;

/**
 * This class represents a single BBCode tag.
 */
class NoParseTag extends AbstractTag
{
    /**
     * The name of the tag type (lower cased)
     *
     * @var string
     */
    const NAME = 'noparse';

    /**
     * If true, inner tags will be treated as plain text
     *
     * @var bool
     */
    const NO_PARSE = true;

    /**
     * This method renders a tag of this type.
     * Has to return something, at least an empty string.
     *
     * @param string $html HTML code - passed by reference
     * @param bool   $opening Is the tag opening (true) or closing (false)?
     * @return void
     */
    public function render(&$html, $opening)
    {
        // Do nothing
    }

}