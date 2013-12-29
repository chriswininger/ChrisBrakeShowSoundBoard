<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ClipRest\Controller\ClipRest' => 'ClipRest\Controller\ClipRestController',
            'ClipRest\Controller\ClipSourceRest' => 'ClipRest\Controller\ClipSourceRestController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'clip-source-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/clip-rest/source[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ClipRest\Controller\ClipSourceRest',
                    ),
                ),
            ),
            'clip-rest' => array(
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
