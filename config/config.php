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
            'logout_page' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'logout',
            ],
            'get_quizzes' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/listQuizzes',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'getQuizzes',
            ],
            'get_users' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/listUsers',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'getUsers',
            ],
            'get_questions' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/listQuestions',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'getQuestions',
            ],
            'get_results' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/listResults',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'getResults',
            ],
            'add_new_question' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/newQuestion',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'addNewQuestion',
            ],
            'add_question' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/newQuestion',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'addQuestion',
            ],
            'edit_question' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/editQuestion/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'editQuestion',
            ],
            'update_question' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/editQuestion/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'updateQuestion',
            ],
            'delete_question' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/deleteQuestion/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'deleteQuestion',
            ],
            'add_new_user' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/newUser',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'addNewUser',
            ],
            'add_user' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/newUser',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'addUser',
            ],
            'edit_user' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/editUser/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'editUser',
            ],
            'update_user' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/editUser/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'updateUser',
            ],
            'delete_user' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/deleteUser/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'deleteUser',
            ],
        ]
    ],
];
