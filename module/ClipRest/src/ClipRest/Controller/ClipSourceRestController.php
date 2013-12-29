<?php
namespace ClipRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ClipSourceRestController extends AbstractRestfulController
{
    protected $clipSourceTable = false;

    public function getList()
    {
        return new JsonModel($this->getClipSourceTable()->fetchAll()->toArray());
    }

    public function get($id)
    {
        return new JsonModel($this->getClipSourceTable()->getClipSource($id)->toArray());
    }

    public function create($data)
    {
        // # code...
    }

    public function update($id, $data)
    {
        // # code...
    }

    public function delete($id)
    {
        // # code...
    }


    public function getClipSourceTable()
    {
        if (!$this->clipSourceTable) {
            $sm = $this->getServiceLocator();
            $this->clipSourceTable = $sm->get('ClipRest\Model\ClipSourceTable');
        }
        return $this->clipSourceTable;
    }
}
