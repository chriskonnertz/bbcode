<?php

namespace ChrisKonnertz\BBCode\Tokenizer;

use ChrisKonnertz\BBCode\BBCode;
use ChrisKonnertz\BBCode\Tag\AbstractTagType;

/**
 * "Tokenization is the process of demarcating and possibly classifying
 * sections of a string of input characters" (Source: Wikipedia)
 * The tokenizer operates on the text and tries to split it into parts.
 * The tokenizer is not very smart, it does not really care for grammar.
 */
class Tokenizer
{

    /**
     * Tokenize the text. Returns an array with the tokens.
     * Note: There can be more than one subsequent plain text tokens
     *
     * @param  string            $text      Render the passed BBCode string
     * @param  bool              $escape    Escape HTML entities? (Only "<" and ">"!)
     * @param  bool              $keepLines Keep line breaks by replacing them with <br>?
     * @return Token[]
     */
    public function tokenize($text, $escape = true, $keepLines = true)
    {
        $tokens         = [];
        $length         = mb_strlen($text);
        $value          = '';
        $insideTag      = false; // Means: The current position is between "[" and "]" (=a tag definition)
        $insideName     = false; // In a tag "[code]", "code" is the name of the tag
        $insideString   = false; // Properties of tags can be written as string with " at the start & end
        $noParse        = false; // If true, do not parse BBCode inside this tag
        $tagName        = '';   // Name of the current tag
        $tagProperty    = '';    // Property value of the current tag
        $tagOpening     = null; // True/false + null = undefined
        $tagStartPos    = 0;

        // Loop over each character of the text
        for ($pos = 0; $pos < $length; $pos++) {
            $char = mb_substr($text, $pos, 1);

            if ($keepLines) {
                // Create line break token when \n
                if ($char === "\n") {
                    $tokens[] = new Token($char, Token::TYPE_LINEBREAK, $pos);
                }
                // Ignore \r
                if ($char === "\r") {
                    continue;
                }
            }

            if (! $escape or ($char !== '<' and $char !== '>')) {
                if ($insideTag) {
                    if ($char === '"') {
                        if ($insideString) {
                            $insideString = false;
                        } else {
                            $insideString = true;
                        }
                    } else {
                        // "]" closes a tag (if it is not used in a string)
                        if ($char == ']' and ! $insideString) {
                            if (! $noParse or (! $tagOpening and $this->checkNoParse($value))) {
                                $tokenType = $tagOpening ? Token::TYPE_TAG_OPENING : Token::TYPE_TAG_CLOSING;
                                $tokens[] = new Token($tagName, $tokenType, $tagStartPos, $tagProperty);
                            } else {
                                $tokens[] = new Token($value, Token::TYPE_PLAIN_TEXT, $tagStartPos);
                            }

                            $noParse = $this->checkNoParse($value);

                            $tagName = '';
                            $value = '';
                            $insideTag = false;
                            $insideName = false;
                            continue;
                        }

                        if ($insideName and ! $insideString) {
                            // This makes the current tag a closing tag
                            if ($char === '/') {
                                $tagOpening = false;
                            } else {
                                // This means a property starts
                                if ($char === '=') {
                                    $insideName = false;
                                } elseif ($char === '[') { // Invalid tag - ignore it and start again
                                    $value = '';
                                    $tagName = '';
                                    $tagOpening = true;
                                }
                                else {
                                    $value .= mb_strtolower($char);
                                    $tagName .= mb_strtolower($char);
                                }
                            }
                        } else { // If we are not inside the name we are inside a property
                            $tagProperty .= $char;
                        }
                    }
                } else {
                    if ($char === '[') {
                        // Since a tag starts, plain text may end and we have to create a token for it
                        if ($value !== '') {
                            $tokens[] = new Token($value, Token::TYPE_PLAIN_TEXT, $tagStartPos);
                            $value = '';
                        }

                        $insideTag = true;
                        $insideName = true;
                        $tagOpening = true;
                        $tagStartPos = $pos;
                        $tagName = '';
                    } else {
                        // This is plain text
                        $value .= $char;
                    }
                }
            } else {
                // Escape HTML chars "<" and ">"
                $value .= htmlspecialchars($char);
            }

        }

        // If the text ends with plain text we have to create the final plain text token now
        if ($value !== '') {
            $tokens[] = new Token($value, Token::TYPE_PLAIN_TEXT, $tagStartPos);
        }

        return $tokens;
    }

    /**
     * Check if a tag is a tag that forbids parsing of its inner content
     *
     * @param string $tagName
     * @return bool
     */
    protected function checkNoParse($tagName)
    {
        foreach (BBCode::TAG_TYPES as $tagType) {
            if ($tagType::NAME === $tagName) {
                return $tagType::NO_PARSE;
            }
        }

        // We do not want to throw any exceptions so we just return false
        return false;
    }

}