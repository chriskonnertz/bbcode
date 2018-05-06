<?php

namespace ChrisKonnertz\BBCode\Tags;

/**
 * This class is the abstract base class for all BBCode tag classes.
 */
abstract class AbstractTag
{
    /**
     * The name of the tag type (lower cased).
     * The sub class has to overwrite this constant.
     */
    const NAME = '';

    /**
     * If true, inner tags will be treated as plain text
     * The sub class has to overwrite this constant.
     */
    const NO_PARSE = false;

    /**
     * This method renders a tag of this type.
     * Has to return something, at least an empty string.
     *
     * @param string $html    The generated HTML code so far - passed by reference
     * @param bool   $opening Is the tag opening (true) or closing (false)?
     * @return void
     */
    abstract public function render(&$html, $opening);

}