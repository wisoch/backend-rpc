<?php

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'routes' => [
        'home' => [
            'type' => Literal::class,
            'options' => [
                'route'    => '/',
                'defaults' => [
                    'controller' => Controller\DashboardController::class,
                    'action'     => 'home',
                ],
            ]
        ],
        'gateway' => [
            'type' => Literal::class,
            'options' => [
                'route'    => '/gateway',
                'defaults' => [
                    'controller' => Controller\GatewayController::class,
                    'action'     => 'dispatch',
                ],
            ]
        ],
    ],
];
