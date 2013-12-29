<?php
namespace ClipRest\Model;

class ClipSource {
    public $id;
    public $clipID;
    public $pathLocal;
    public $url;
    public $mediaType;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->clipID = (isset($data['clipID'])) ? $data['clipID'] : null;
        $this->pathLocal = (isset($data['pathLocal'])) ? $data['pathLocal'] : null;
        $this->url = (isset($data['url'])) ? $data['url'] : null;
        $this->mediaType = (isset($data['mediaType'])) ? $data['mediaType'] : null;
    }

    public function toArray() {
        return get_object_vars($this);
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }
}