<?php
namespace ClipRest\Controller;
 
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
 
class ClipRestController extends AbstractRestfulController
{
    protected $clipTable = false;

    public function getList()
    {
        return new JsonModel($this->getClipTable()->fetchAllWithSources());
    }

    public function get($id)
    {
       return new JsonModel($this->getClipTable()->getClipWithSources($id)->toArray());
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


    public function getClipTable()
    {
        if (!$this->clipTable) {
            $sm = $this->getServiceLocator();
            $this->clipTable = $sm->get('ClipRest\Model\ClipTable');
        }
        return $this->clipTable;
    }
}
