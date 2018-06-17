<?php

namespace ChrisKonnertz\BBCode;

use Closure;

/*
 * BBCode to HTML converter
 *
 * Inspired by Kai Mallea (kmallea@gmail.com)
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */
class BBCode
{

    /**
     * Constants with the names of the built-in default tags
     * Call getDefaultTagNames() to get them as an array.
     */
    const TAG_NAME_B         = 'b';
    const TAG_NAME_I         = 'i';
    const TAG_NAME_S         = 's';
    const TAG_NAME_U         = 'u';
    const TAG_NAME_CODE      = 'code';
    const TAG_NAME_EMAIL     = 'email';
    const TAG_NAME_URL       = 'url';
    const TAG_NAME_IMG       = 'img';
    const TAG_NAME_LIST      = 'list';
    const TAG_NAME_LI_STAR   = '*';
    const TAG_NAME_LI        = 'li';
    const TAG_NAME_QUOTE     = 'quote';
    const TAG_NAME_YOUTUBE   = 'youtube';
    const TAG_NAME_FONT      = 'font';
    const TAG_NAME_SIZE      = 'size';
    const TAG_NAME_COLOR     = 'color';
    const TAG_NAME_LEFT      = 'left';
    const TAG_NAME_CENTER    = 'center';
    const TAG_NAME_RIGHT     = 'right';
    const TAG_NAME_SPOILER   = 'spoiler';

    /**
     * The current version number
     */
    const VERSION = '1.1.2';

    /**
     * The text with BBCodes
     *
     * @var string|null
     */
    protected $text = null;

    /**
     * Array with custom tag Closures
     *
     * @var Closure[]
     */
    protected $customTagClosures = array();

    /**
     * Array of (name of) tags that are ignored
     *
     * @var string[]
     */
    protected $ignoredTags = array();

    /**
     * Widht (in pixels) of the YouTube iframe element
     *
     * @var int
     */
    protected $youTubeWidth = 640;

    /**
     * Height (in pixels) of the YouTube iframe element
     *
     * @var int
     */
    protected $youTubeHeight = 385;

    /**
     * BBCode constructor.
     *
     * @param string|null $text The text - might include BBCode tags
     */
    public function __construct($text = null)
    {
        $this->setText($text);
    }

    /**
     * Set the raw text - might include BBCode tags
     *
     * @param string $text The text
     * @retun void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Renders only the text without any BBCode tags.
     *
     * @param  string $text Optional: Render the passed BBCode string instead of the internally stored one
     * @return string
     */
    public function renderPlain($text = null)
    {
        if ($this->text !== null and $text === null) {
            $text = $this->text;
        }

        return preg_replace("/\[(.*?)\]/is", '', $text);
    }

    /**
     * Renders only the text without any BBCode tags.
     * Alias for renderRaw().
     *
     * @deprecated Deprecated since 1.1.0
     *
     * @param  string $text Optional: Render the passed BBCode string instead of the internally stored one
     * @return string
     */
    public function renderRaw($text = null)
    {
        return $this->renderPlain($text);
    }

    /**
     * Renders BBCode to HTML
     *
     * @param  string  $text      Optional: Render the passed BBCode string instead of the internally stored one
     * @param  bool    $escape    Escape HTML entities? (Only "<" and ">"!)
     * @param  bool    $keepLines Keep line breaks by replacing them with <br>?
     * @return string
     */
    public function render($text = null, $escape = true, $keepLines = true)
    {
        if ($this->text !== null and $text === null) {
            $text = $this->text;
        }

        $html     = '';
        $len      = mb_strlen($text);
        $inTag    = false;            // True if current position is inside a tag
        $inName   = false;            // True if current pos is inside a tag name
        $inStr    = false;            // True if current pos is inside a string
        /** @var Tag|null $tag */
        $tag      = null;
        $openTags = array();

        /*
         * Loop over each character of the text
         */
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);

            if ($keepLines) {
                if ($char == "\n") {
                    $html .= '<br/>';
                }
                if ($char == "\r") {
                    continue;
                }
            }

            if (! $escape or ($char != '<' and $char != '>')) {
                /*
                 * $inTag == true means the current position is inside a tag definition
                 * (= inside the brackets of a tag)
                 */
                if ($inTag) {
                    if ($char == '"') {
                        if ($inStr) {
                            $inStr = false;
                        } else {
                            if ($inName) {
                                $tag->valid = false;
                            } else {
                                $inStr = true;
                            }
                        }
                    } else {
                        /*
                         * This closes a tag
                         */
                        if ($char == ']' and ! $inStr) {
                            $inTag  = false;
                            $inName = false;

                            if ($tag->valid) {
                                $code = null;

                                if ($tag->opening) {
                                    $code = $this->generateTag($tag, $html, null, $openTags);
                                } else {
                                    $openingTag = $this->popTag($openTags, $tag);
                                    if ($openingTag) {
                                        $code = $this->generateTag($tag, $html, $openingTag, $openTags);
                                    }
                                }

                                if ($code !== null and $tag->opening) {
                                    $openTags[$tag->name][] = $tag;
                                }

                                $html .= $code;
                            }
                            continue;
                        }

                        if ($inName and ! $inStr) {
                            /*
                             * This makes the current tag a closing tag
                             */
                            if ($char == '/') {
                                if ($tag->name) {
                                    $tag->valid = false;
                                } else {
                                    $tag->opening = false;
                                }
                            } else {
                                /*
                                 * This means a property starts
                                 */
                                if ($char == '=') {
                                    if ($tag->name) {
                                        $inName = false;
                                    } else {
                                        $tag->valid = false;
                                    }
                                } else {
                                    $tag->name .= mb_strtolower($char);
                                }
                            }
                        } else { // If we are not inside the name we are inside a property
                            $tag->property .= $char;
                        }
                    }
                } else {
                    /*
                     * This opens a tag
                     */
                    if ($char == '[') {
                        $inTag          = true;
                        $inName         = true;
                        $tag            = new Tag();
                        $tag->position  = mb_strlen($html);
                    } else {
                        $html .= $char;
                    }
                }
            } else {
                $html .= htmlspecialchars($char);
            }
        }

        /*
         * Check for tags that are not closed and close them.
         */
        foreach ($openTags as $name => $openTagsByType) {
            $closingTag = new Tag($name, false);

            foreach ($openTagsByType as $openTag) {
                $html .= $this->generateTag($closingTag, $html, $openTag);
            }
        }

        return $html;
    }

    /**
     * Generates and returns the HTML code of the current tag
     *
     * @param  Tag      $tag        The current tag
     * @param  string   $html       The current HTML code passed by reference - might be altered!
     * @param  Tag|null $openingTag The opening tag that is linked to the tag (or null)
     * @param  Tag[]    $openTags   Array with tags that are opned but not closed
     * @return string
     */
    protected function generateTag(Tag $tag, &$html, Tag $openingTag = null, array $openTags = [])
    {
        $code = null;

        if (in_array($tag->name, $this->ignoredTags)) {
            return $code;
        }

        switch ($tag->name) {
            case self::TAG_NAME_B:
                if ($tag->opening) {
                    $code = '<strong>';
                } else {
                    $code = '</strong>';
                }
                break;
            case self::TAG_NAME_I:
                if ($tag->opening) {
                    $code = '<em>';
                } else {
                    $code = '</em>';
                }
                break;
            case self::TAG_NAME_S:
                if ($tag->opening) {
                    $code = '<del>';
                } else {
                    $code = '</del>';
                }
                break;
            case self::TAG_NAME_U:
                if ($tag->opening) {
                    $code = '<span style="text-decoration: underline">';
                } else {
                    $code = '</span>';
                }
                break;
            case self::TAG_NAME_CODE:
                if ($tag->opening) {
                    $code = '<pre><code>';
                } else {
                    $code = '</code></pre>';
                }
                break;
            case self::TAG_NAME_EMAIL:
                if ($tag->opening) {
                    if ($tag->property) {
                        $code = '<a href="mailto:'.$tag->property.'">';
                    } else {
                        $code = '<a href="mailto:';
                    }
                } else {
                    if ($openingTag->property) {
                        $code = '</a>';
                    } else {
                        $code .= '">'.mb_substr($html, $openingTag->position + 16).'</a>';
                    }
                }
                break;
            case self::TAG_NAME_URL:
                if ($tag->opening) {
                    if ($tag->property) {
                        $code = '<a href="'.$tag->property.'">';
                    } else {
                        $code = '<a href="';
                    }
                } else {
                    if ($openingTag->property) {
                        $code = '</a>';
                    } else {
                        $partial = mb_substr($html, $openingTag->position + 9);
                        $html = mb_substr($html, 0, $openingTag->position + 9)
                            .strip_tags($partial).'">'.$partial.'</a>';
                    }
                }
                break;
            case self::TAG_NAME_IMG:
                if ($tag->opening) {
                    $code = '<img src="';
                } else {
                    $code = '" />';
                }
                break;
            case self::TAG_NAME_LIST:
                if ($tag->opening) {
                    $listType = '<ul>';

                    if ($tag->property) {
                        $listType = '<ol>';

                        if ($tag->property == 'i') {
                            $listType = '<ol style="list-style-type: lower-roman">';
                        } elseif ($tag->property == 'a') {
                            $listType = '<ol style="list-style-type: lower-alpha">';
                        }
                    }

                    $code = $listType;
                } else {
                    if ($this->endsWith($html, '<ul>')) {
                        $code = '</ul>';
                    } elseif ($this->endsWith($html, '<ol>')) {
                        $code = '</ol>';
                    } elseif ($this->endsWith($html, '<ol style="list-style-type: lower-roman">')) {
                        $code = '</ol>';
                    } elseif ($this->endsWith($html, '<ol style="list-style-type: lower-alpha">')) {
                        $code = '</ol>';
                    } elseif ($this->endsWith($html, '</li>') and $openingTag->property) {
                        $code = '</ol>';
                    } elseif ($this->endsWith($html, '</li>') and ! $openingTag->property) {
                        $code = '</ul>';
                    } elseif ($openingTag->property) {
                        $code = '</li></ol>';
                    } else {
                        $code = '</li></ul>';
                    }
                }
                break;
            case self::TAG_NAME_LI_STAR:
                if ($tag->opening) {
                    /*
                     * We require that the list item is inside a list
                     */
                    if (isset($openTags['list']) and sizeof($openTags['list']) > 0) {
                        $tag->opening = false;

                        if ($this->endsWith($html, '<ul>')) {
                            $code = '<li>';
                        } else {
                            $code = '</li><li>';
                        }
                    }
                }
                break;
            case self::TAG_NAME_LI:
                if ($tag->opening) {
                    /*
                     * We require that the list item is inside a list
                     */
                    if (isset($openTags['list']) and sizeof($openTags['list']) > 0) {
                        $code = '<li>';
                    }
                } else {
                    $code = '</li>';
                }
                break;
            case self::TAG_NAME_QUOTE:
                if ($tag->opening) {
                    if ($tag->property) {
                        $code = '<blockquote><span class="author">'.$tag->property.':</span><br/>';
                    } else {
                        $code = '<blockquote>';
                    }
                } else {
                    $code = '</blockquote>';
                }
                break;
            case self::TAG_NAME_YOUTUBE:
                if ($tag->opening) {
                    $code = '<iframe class="youtube-player" type="text/html" width="' . $this->youTubeWidth . '"\
                        height="' . $this->youTubeHeight . '" src="https://www.youtube.com/embed/';
                } else {
                    $code = '" frameborder="0"></iframe>';
                }
                break;
            case self::TAG_NAME_FONT:
                if ($tag->opening) {
                    if ($tag->property) {
                        $code = '<span style="font-family: '.$tag->property.'">';
                    }
                } else {
                    $code = '</span>';
                }
                break;
            case self::TAG_NAME_SIZE:
                if ($tag->opening) {
                    if ($tag->property) {
                        $code = '<span style="font-size: '.$tag->property.'%">';
                    }
                } else {
                    $code = '</span>';
                }
                break;
            case self::TAG_NAME_COLOR:
                if ($tag->opening) {
                    if ($tag->property) {
                        $code = '<span style="color: '.$tag->property.'">';
                    }
                } else {
                    $code = '</span>';
                }
                break;
            case self::TAG_NAME_LEFT:
                if ($tag->opening) {
                    $code = '<div style="text-align: left">';
                } else {
                    $code = '</div>';
                }
                break;
            case self::TAG_NAME_CENTER:
                if ($tag->opening) {
                    $code = '<div style="text-align: center">';
                } else {
                    $code = '</div>';
                }
                break;
            case self::TAG_NAME_RIGHT:
                if ($tag->opening) {
                    $code = '<div style="text-align: right">';
                } else {
                    $code = '</div>';
                }
                break;
            case self::TAG_NAME_SPOILER:
                if ($tag->opening) {
                    $code = '<div class="spoiler">';
                } else {
                    $code = '</div>';
                }
                break;
            default:
                // Custom tags:
                foreach ($this->customTagClosures as $name => $closure) {
                    if ($tag->name === $name) {
                        $code .= $closure($tag, $html, $openingTag);
                    }
                }
        }

        return $code;
    }

    /**
     * Magic method __toString()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Returns the last tag of a given type and removes it from the array.
     *
     * @param  Tag[]    $tags Array of tags
     * @param  Tag      $tag  Return the last tag of the type of this tag
     * @return Tag|null
     */
    protected function popTag(array &$tags, $tag)
    {
        if (! isset($tags[$tag->name])) {
            return null;
        }

        $size = sizeof($tags[$tag->name]);

        if ($size === 0) {
            return null;
        } else {
            return array_pop($tags[$tag->name]);
        }
    }

    /**
     * Adds a custom tag (with name and a Closure)
     *
     * Example:
     *
     * $bbcode->addTag('example', function($tag, &$html, $openingTag) {
     *     if ($tag->opening) {
     *         return '<span class="example">';
     *     } else {
     *         return '</span>';
     *     }
     * });
     *
     * @param string  $name    The name of the tag
     * @param Closure $closure The Closure that renders the tag
     * @return void
     */
    public function addTag($name, Closure $closure)
    {
        $this->customTagClosures[$name] = $closure;
    }

    /**
     * Remove the custom tag with the given name
     *
     * @param  string $name
     * @return void
     */
    public function forgetTag($name)
    {
        unset($this->customTagClosures[$name]);
    }

    /**
     * Add a tag to the array of ignored tags
     *
     * @param  string $name The name of the tag
     * @return void
     */
    public function ignoreTag($name)
    {
        if (! in_array($name, $this->ignoredTags)) {
            $this->ignoredTags[] = $name;
        }
    }

    /**
     * Remove a tag from the array of ignored tags
     *
     * @param  string $name The name of the tag
     * @return void
     */
    public function permitTag($name)
    {
        $key = array_search($name, $this->ignoredTags);

        if ($key !== false) {
            unset($this->ignoredTags[$key]);
        }
    }

    /**
     * Returns an array with the name of the tags that are ignored
     *
     * @return string[]
     */
    public function getIgnoredTags()
    {
        return $this->ignoredTags;
    }

    /**
     * Get the width of the YouTube iframe element
     *
     * @return int
     */
    public function getYouTubeWidth()
    {
        return $this->youTubeWidth;
    }

    /**
     * Set the width of the YouTube iframe element
     *
     * @param int $youTubeWidth
     * @return void
     */
    public function setYouTubeWidth($youTubeWidth)
    {
        $this->youTubeWidth = $youTubeWidth;
    }

    /**
     * Get the height of the YouTube iframe element
     *
     * @return int
     */
    public function getYouTubeHeight()
    {
        return $this->youTubeHeight;
    }

    /**
     * Set the height of the YouTube iframe element
     *
     * @param int $youTubeHeight
     * @return void
     */
    public function setYouTubeHeight($youTubeHeight)
    {
        $this->youTubeHeight = $youTubeHeight;
    }

    /**
     * Returns an array with the names of all default tags
     *
     * @return string[]
     */
    public function getDefaultTagNames()
    {
        return [
            self::TAG_NAME_B,
            self::TAG_NAME_I,
            self::TAG_NAME_S,
            self::TAG_NAME_U,
            self::TAG_NAME_CODE,
            self::TAG_NAME_EMAIL,
            self::TAG_NAME_URL,
            self::TAG_NAME_IMG,
            self::TAG_NAME_LIST,
            self::TAG_NAME_LI_STAR,
            self::TAG_NAME_LI,
            self::TAG_NAME_QUOTE,
            self::TAG_NAME_YOUTUBE,
            self::TAG_NAME_FONT,
            self::TAG_NAME_SIZE,
            self::TAG_NAME_COLOR,
            self::TAG_NAME_LEFT,
            self::TAG_NAME_CENTER,
            self::TAG_NAME_RIGHT,
            self::TAG_NAME_SPOILER,
        ];
    }

    /**
     * Returns true if $haystack ends with $needle
     *
     * @param  string $haystack
     * @param  string $needle
     * @return bool
     */
    protected function endsWith($haystack, $needle)
    {
        return ($needle === '' or mb_substr($haystack, -mb_strlen($needle)) === $needle);
    }
}
