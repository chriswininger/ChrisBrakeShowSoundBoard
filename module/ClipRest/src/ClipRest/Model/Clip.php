<?php
namespace ClipRest\Model;

class Clip {
    public $id;
    public $title;
    public $defaultImage;
    public $playingImage;
    public $info;
    public $clipSources;

    private $serviceLocator;
    private $clipSourceTable;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->defaultImage = (isset($data['defaultImage'])) ? $data['defaultImage'] : null;
        $this->playingImage = (isset($data['playingImage'])) ? $data['playingImage'] : null;
        $this->info = (isset($data['info'])) ? $data['info'] : null;
        $this->clipSources = (isset($data['clipSources'])) ? $data['clipSources'] : null;
    }

    public function getClipSources() {
        return $this->getClipSourceTable()->getClipSourceByClip($this->id)->toArray();
    }

    public function setServiceLocator($sm) {
        $this->serviceLocator = $sm;
    }


    public function getClipSourceTable()
    {
        if (!$this->clipSourceTable) {
            $sm = $this->serviceLocator;
            $this->clipSourceTable = $sm->get('ClipRest\Model\ClipSourceTable');
        }
        return $this->clipSourceTable;
    }


    public function toArray() {
        return get_object_vars($this);
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

 }