<?php

return array(
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'gii',
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array(
//                'application.gii'  //Ajax Crud template path
                'ext.giix-core', // giix generators
            ),
        ),        
    ),
    'components' => array(
        'log' => array(
            'routes' => array(
                array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'trace, info, error, warning',
                    'categories' => 'system.web.*',
                ),
            ),
        ),
    ),
);