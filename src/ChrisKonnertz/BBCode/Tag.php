<?php namespace ChrisKonnertz\BBCode;

class Tag {
    
    public $name = null;

    public $property = null;

    public $opening = true;

    public $valid = true;

    public $position = -1;

    public function __construct($name = null, $opening = true)
    {
        $this->name     = $name;
        $this->opening  = $opening;
    }

}