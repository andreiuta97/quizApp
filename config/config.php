<?php

namespace Framework\Config;

use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\Dispatcher\Dispatcher;

return [
    'renderer' => [
        Renderer::CONFIG_KEY_BASE_VIEW_PATH => dirname(__DIR__) . '/View/'
    ],
    'dispatcher' => [
        Dispatcher::CONFIG_CONTROLLER_NAMESPACE => 'Framework\Controller',
        Dispatcher::CONFIG_CONTROLLER_SUFFIX => 'Controller',
    ],
    'router' => [
        'routes' => [
            'view' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/user',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'get2',
            ],
            'view_user' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/user/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'get',
            ],
            'delete_user' => [
                Router::CONFIG_KEY_METHOD => 'DELETE',
                Router::CONFIG_KEY_PATH => '/user/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'delete',
            ],
            'view_user_role' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/user/(?<id>\d+)/setRole/(?<role>(ADMIN|GUEST))\?p=(?<priority>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'post',
            ],
        ]
    ],
    ''


];