<?php

namespace Framework\Config;

use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\Dispatcher\Dispatcher;

return [
    'renderer' => [
        Renderer::CONFIG_KEY_BASE_VIEW_PATH => '/var/www/quizApp/src/views/'
    ],
    'dispatcher' => [
        Dispatcher::CONFIG_CONTROLLER_NAMESPACE => 'QuizApp\Controller',
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
            'get_login_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'getLogin',
            ],
            'login_page' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/login',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'toGoTo',
            ],
            'admin_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-dashboard',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'getAdminDashboard',
            ],
        ]
    ],
    ''


];