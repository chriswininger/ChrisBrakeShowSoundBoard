<?php
namespace ClipRest\Model;
use Zend\Db\TableGateway\TableGateway;

class ClipSourceTable {
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

    public function getClipSource($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getClipSourceByClip($clipID)
    {
        $clipID = (int) $clipID;
        $rowset = $this->tableGateway->select(array('clipID' => $clipID ));
        return $rowset;
    }

    public function saveClipSource(Clip $clip)
    {
        $data = array(
            'clipID' => $clip->clipID,
            'pathLocal'  => $clip->pathLocal,
            'url' => $clip->url,
            'mediaType'  => $clip->mediaType,
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

    public function deleteClipSource($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
} 