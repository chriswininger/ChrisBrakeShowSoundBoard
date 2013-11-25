<?php
namespace ClipRest\Model;
use Zend\Db\TableGateway\TableGateway;

class ClipTable {
    protected $tableGateway;


    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getClip($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveClip(Clip $clip)
    {
        $data = array(
            'title' => $clip->title,
            'defaultImage'  => $clip->defaultImage,
            'playingImage' => $clip->playingImage,
            'info'  => $clip->info,
        );

        $id = (int)$clip->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getClip($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
} 