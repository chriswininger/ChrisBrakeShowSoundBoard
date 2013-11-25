<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ClipRest\Controller\ClipRest' => 'ClipRest\Controller\ClipRestController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'album-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/clip-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ClipRest\Controller\ClipRest',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'strategies' => array(
           'ViewJsonStrategy',
        ),
    ),
);
