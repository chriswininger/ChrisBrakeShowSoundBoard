<?php
namespace ClipRest\Model;

class Clip {
    public $id;
    public $title;
    public $defaultImage;
    public $playingImage;
    public $info;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->defaultImage = (isset($data['defaultImage'])) ? $data['defaultImage'] : null;
        $this->playingImage = (isset($data['playingImage'])) ? $data['playingImage'] : null;
        $this->info = (isset($data['info'])) ? $data['info'] : null;
    }

    public function toArray() {
        return get_object_vars($this);
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }
 }