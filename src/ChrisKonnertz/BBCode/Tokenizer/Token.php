<?php

namespace ChrisKonnertz\BBCode\Tokenizer;

/**
 * The tokenizer splits a term into an array of tokens.
 * Tokens are the parts of a text.
 */
class Token
{

    /**
     * Defines the type of a token.
     * Example token value of a token with this type:
     * '\n'
     *
     * @const int
     */
    const TYPE_LINEBREAK = 0;

    /**
     * Defines the type of a token.
     * Example token value of a token with this type:
     * 'Hello world'
     *
     * @const int
     */
    const TYPE_PLAIN_TEXT = 1;

    /**
     * Defines the type of a token.
     * Example token value of a token with this type:
     * '[b]'
     *
     * @const int
     */
    const TYPE_TAG_OPENING = 2;

    /**
     * Defines the type of a token.
     * Example token value of a token with this type:
     * '[/b]'
     *
     * @const int
     */
    const TYPE_TAG_CLOSING = 3;

    /**
     * The raw value of the token. Numbers are stored as string.
     *
     * @var string
     */
    protected $value = null;

    /**
     * The type of the token. One of these constants:
     * self::TYPE_WORD|self::TYPE_NUMBER|self::TYPE_CHARACTER
     *
     * @var int
     */
    protected $type;

    /**
     * Position of the token in the input stream.
     * It is stored as a debugging information.
     *
     * @var int
     */
    protected $position;

    /**
     * The property value of the token (empty string = none)
     *
     * @var string
     */
    protected $property;

    /**
     * Token constructor. The position must be >= 0.
     *
     * @param string $value    The value of the token
     * @param string $type     The type of the token - one of these: self::TYPE_<NAME>
     * @param int    $position The position of the token in the original text
     * @param string $property Optional: The property value of the token
     */
    public function __construct($value, $type, $position, $property = '')
    {
        if (! is_string($value)) {
            throw new \InvalidArgumentException(
                'Error: Argument "value" has to be of type string but is of type "'.gettype($value).'"'
            );
        }
        $this->value = $value;

        if (! in_array($type, $this->getAllTypes())) {
            throw new \InvalidArgumentException(
                'Error: Argument "type" does not have the value of a known token type'
            );
        }
        $this->type = $type;

        if (! is_int($position)) {
            throw new \InvalidArgumentException('Error: Argument "position" has to be of type int');
        }
        if ($position < 0) {
            throw new \InvalidArgumentException('Error: Value of parameter "position" has to be >= zero');
        }
        $this->position = $position;

        if (! is_string($property)) {
            throw new \InvalidArgumentException(
                'Error: Argument "property" has to be of type string but is of type "'.gettype($property).'"'
            );
        }
        $this->property = $property;
    }

    /**
     * Returns an array that contains the values of all
     * possible types of token type constants.
     *
     * @see self::TYPE_<NAME>
     *
     * @return int[]
     */
    public function getAllTypes()
    {
        return [self::TYPE_LINEBREAK, self::TYPE_PLAIN_TEXT, self::TYPE_TAG_OPENING, self::TYPE_TAG_CLOSING];
    }

    /**
     * Getter for the value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Getter for the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Getter for the position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

}