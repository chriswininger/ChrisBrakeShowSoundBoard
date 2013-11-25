<?php
namespace ClipRest;

use ClipRest\Model\Clip;
use ClipRest\Model\ClipTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public  function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ClipRest\Model\ClipTable' =>  function($sm) {
                        $tableGateway = $sm->get('ClipTableGateway');
                        $table = new ClipTable($tableGateway);
                        return $table;
                    },
            'ClipTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Clip());
                    return new TableGateway('soundboard_clip', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
