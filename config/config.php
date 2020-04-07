<?php

namespace Framework\Config;

use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\Dispatcher\Dispatcher;

return [
    'host' => 'local.quiz.com',
    'schema' => 'http',
    'renderer' => [
        Renderer::CONFIG_KEY_BASE_VIEW_PATH => '../src/views/'
    ],
    'dispatcher' => [
        Dispatcher::CONFIG_CONTROLLER_NAMESPACE => 'QuizApp\Controller',
        Dispatcher::CONFIG_CONTROLLER_SUFFIX => 'Controller',
    ],
    'router' => [
        'routes' => [
            'get_login_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/',
                Router::CONFIG_KEY_CONTROLLER => 'authentication',
                Router::CONFIG_KEY_ACTION => 'getLogin',
            ],
            'login_page' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/login',
                Router::CONFIG_KEY_CONTROLLER => 'authentication',
                Router::CONFIG_KEY_ACTION => 'redirectAtLogin',
            ],
            'admin_dashboard' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin/dashboard',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'adminDashboard',
            ],
            'candidate_homepage' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'candidateHomepage',
            ],
            'logout_page' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/',
                Router::CONFIG_KEY_CONTROLLER => 'authentication',
                Router::CONFIG_KEY_ACTION => 'logout',
            ],
            'get_quizzes' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/listQuizzes',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
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
                Router::CONFIG_KEY_CONTROLLER => 'result',
                Router::CONFIG_KEY_ACTION => 'getResults',
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
            'add_new_quiz' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/newQuiz',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ACTION => 'addNewQuiz',
            ],
            'add_quiz' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/newQuiz',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ACTION => 'addQuiz',
            ],
            'edit_quiz' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/editQuiz/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ACTION => 'editQuiz',
            ],
            'update_quiz' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/editQuiz/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ACTION => 'updateQuiz',
            ],
            'delete_quiz' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/deleteQuiz/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ACTION => 'deleteQuiz',
            ],
            //CANDIDATE
            'get_quizzes_candidate' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate/homepage',
                Router::CONFIG_KEY_CONTROLLER => 'quizInstance',
                Router::CONFIG_KEY_ACTION => 'getQuizzes',
            ],
            'start_quiz' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/start/quiz/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'quizInstance',
                Router::CONFIG_KEY_ACTION => 'startQuiz',
            ],
            'get_question' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/quiz/question/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'questionInstance',
                Router::CONFIG_KEY_ACTION => 'getQuestionInstance',
            ],
            'next_answer' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/user/answer/(?<id>\d+)/(?<offset>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'answerInstance',
                Router::CONFIG_KEY_ACTION => 'saveAnswerInstance',
            ],
            'save_answer' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/user/answer/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'answerInstance',
                Router::CONFIG_KEY_ACTION => 'submitQuiz',
            ],
            'get_overview_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate/overview',
                Router::CONFIG_KEY_CONTROLLER => 'quizInstance',
                Router::CONFIG_KEY_ACTION => 'showOverview',
            ],
            'get_success_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate/success',
                Router::CONFIG_KEY_CONTROLLER => 'quizInstance',
                Router::CONFIG_KEY_ACTION => 'showSuccess',
            ],
            'view_result' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/result/(?<id>\d+)/(?<quiz_instance_id>\d+)',
                Router::CONFIG_KEY_CONTROLLER => 'result',
                Router::CONFIG_KEY_ACTION => 'getResult',
            ],
        ]
    ],
];
