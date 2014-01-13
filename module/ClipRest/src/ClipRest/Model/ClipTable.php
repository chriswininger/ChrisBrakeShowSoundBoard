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

    public function fetchAllWithSources()
    {
        $resultSet = $this->tableGateway->select();
        $resultSet->buffer();

        $out = array();
        foreach ($resultSet as $row) {
            $row->clipSources = $row->getClipSources();
            array_push($out, $row);
        }
        unset($row);

        return $out;
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

    public function getClipWithSources($id)
    {
       $row = $this->getClip($id);
       $row->clipSources = $row->getClipSources();
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

    public function deleteClip($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
} 