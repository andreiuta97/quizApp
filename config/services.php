<?php

use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use Framework\Contracts\SessionInterface;
use Framework\Session\Session;
use QuizApp\Controller\AnswerInstanceController;
use QuizApp\Controller\AnswerTemplateController;
use QuizApp\Controller\AuthenticationController;
use QuizApp\Controller\QuestionInstanceController;
use QuizApp\Controller\QuestionTemplateController;
use QuizApp\Controller\QuizInstanceController;
use QuizApp\Controller\QuizTemplateController;
use QuizApp\Controller\ResultController;
use QuizApp\Controller\UserController;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\DependencyInjection\SymfonyContainer;
use QuizApp\Entity\AnswerInstance;
use QuizApp\Entity\AnswerTemplate;
use QuizApp\Entity\QuestionInstance;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Entity\User;
use QuizApp\Repository\AnswerInstanceRepository;
use QuizApp\Repository\AnswerTemplateRepository;
use QuizApp\Repository\QuestionInstanceRepository;
use QuizApp\Repository\QuestionTemplateRepository;
use QuizApp\Repository\QuizInstanceRepository;
use QuizApp\Repository\QuizTemplateRepository;
use QuizApp\Repository\UserRepository;
use QuizApp\Service\AnswerInstanceService;
use QuizApp\Service\AnswerTemplateService;
use QuizApp\Service\AuthenticationService;
use QuizApp\Service\HashingService;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuestionTemplateService;
use QuizApp\Service\QuizInstanceService;
use QuizApp\Service\QuizTemplateService;
use QuizApp\Service\UserService;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Hydrator\Hydrator;
use ReallyOrm\Test\Repository\RepositoryManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder = new ContainerBuilder();

$baseDir = dirname(__DIR__);
$config = require $baseDir . '/config/config.php';
$configDB = require $baseDir . '/config/db_config.php';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];


$containerBuilder->setParameter('routerConfig', $config['router']);
$containerBuilder->setParameter('rendererConfig', $config['renderer']['base_view_path']);
$containerBuilder->setParameter('dispatcherConfig', $config['dispatcher']);
$containerBuilder->setParameter('dsn', "mysql:host={$configDB['host']};dbname={$configDB['db']};charset={$configDB['charset']}");
$containerBuilder->setParameter('username', $configDB['user']);
$containerBuilder->setParameter('password', $configDB['pass']);
$containerBuilder->setParameter('options', $options);


// Configure Router
$containerBuilder->register(RouterInterface::class, Router::class)
    ->addArgument('%routerConfig%');

// Configure Renderer
$containerBuilder->register(RendererInterface::class, Renderer::class)
    ->addArgument('%rendererConfig%');

// Configure Repository Manager
$containerBuilder->register(RepositoryManagerInterface::class, RepositoryManager::class);

// Configure Hydrator
$containerBuilder->register(HydratorInterface::class, Hydrator::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class));

// Configure PDO
$containerBuilder->register(PDO::class, PDO::class)
    ->addArgument('%dsn%')
    ->addArgument('%username%')
    ->addArgument('%password%')
    ->addArgument('%options%');

// Configure Session
$containerBuilder->register(SessionInterface::class, Session::class);

// Configure Hashing
$containerBuilder->register(HashingService::class, HashingService::class);

// Configure Repository
$containerBuilder->register(UserRepository::class, UserRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(User::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$containerBuilder->register(QuestionTemplateRepository::class, QuestionTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuestionTemplate::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$containerBuilder->register(AnswerTemplateRepository::class, AnswerTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(AnswerTemplate::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$containerBuilder->register(QuizTemplateRepository::class, QuizTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizTemplate::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$containerBuilder->register(QuestionInstanceRepository::class, QuestionInstanceRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuestionInstance::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$containerBuilder->register(AnswerInstanceRepository::class, AnswerInstanceRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(AnswerInstance::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$containerBuilder->register(QuizInstanceRepository::class, QuizInstanceRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizInstance::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

// Configure Services
$containerBuilder->register(UserService::class, UserService::class)
    ->addArgument(new Reference(UserRepository::class))
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument(new Reference(HashingService::class));


$containerBuilder->register(AuthenticationService::class, AuthenticationService::class)
    ->addArgument(new Reference(UserRepository::class))
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument(new Reference(HashingService::class));

$containerBuilder->register(AnswerTemplateService::class, AnswerTemplateService::class)
    ->addArgument(new Reference(AnswerTemplateRepository::class))
    ->addArgument(new Reference(SessionInterface::class));

$containerBuilder->register(QuestionTemplateService::class, QuestionTemplateService::class)
    ->addArgument(new Reference(QuestionTemplateRepository::class))
    ->addArgument(new Reference(AnswerTemplateRepository::class))
    ->addArgument(new Reference(SessionInterface::class));

$containerBuilder->register(QuizTemplateService::class, QuizTemplateService::class)
    ->addArgument(new Reference(QuizTemplateRepository::class))
    ->addArgument(new Reference(SessionInterface::class));

$containerBuilder->register(AnswerInstanceService::class, AnswerInstanceService::class)
    ->addArgument(new Reference(AnswerInstanceRepository::class))
    ->addArgument(new Reference(SessionInterface::class));

$containerBuilder->register(QuestionInstanceService::class, QuestionInstanceService::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(QuestionInstanceRepository::class))
    ->addArgument(new Reference(AnswerInstanceRepository::class))
    ->addArgument(new Reference(SessionInterface::class));

$containerBuilder->register(QuizInstanceService::class, QuizInstanceService::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(QuizInstanceRepository::class))
    ->addArgument(new Reference(SessionInterface::class));


// Configure Controllers
$containerBuilder->register(UserController::class, UserController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(UserService::class))
    ->addArgument(new Reference(AuthenticationService::class))
    ->addTag('controller');

$containerBuilder->register(AuthenticationController::class, AuthenticationController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(AuthenticationService::class))
    ->addArgument(new Reference(HashingService::class))
    ->addTag('controller');

$containerBuilder->register(QuestionTemplateController::class, QuestionTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(QuestionTemplateService::class))
    ->addTag('controller');

$containerBuilder->register(AnswerTemplateController::class, AnswerTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(AnswerTemplateService::class))
    ->addTag('controller');

$containerBuilder->register(QuizTemplateController::class, QuizTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(QuizTemplateService::class))
    ->addArgument(new Reference(QuestionTemplateService::class))
    ->addTag('controller');

$containerBuilder->register(QuestionInstanceController::class, QuestionInstanceController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(QuestionInstanceService::class))
    ->addArgument(new Reference(QuestionInstanceRepository::class))
    ->addArgument(new Reference(AnswerInstanceRepository::class))
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument(new Reference(QuizInstanceService::class))
    ->addTag('controller');

$containerBuilder->register(AnswerInstanceController::class, AnswerInstanceController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(AnswerInstanceService::class))
    ->addTag('controller');

$containerBuilder->register(QuizInstanceController::class, QuizInstanceController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(QuizTemplateRepository::class))
    ->addArgument(new Reference(QuizInstanceService::class))
    ->addArgument(new Reference(QuestionInstanceService::class))
    ->addTag('controller');

$containerBuilder->register(ResultController::class, ResultController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(QuizInstanceService::class))
    ->addTag('controller');

// Configure Dispatcher
$containerBuilder->register(DispatcherInterface::class, Dispatcher::class)
    ->addArgument('%dispatcherConfig%');

$dispatcher = $containerBuilder->getDefinition(DispatcherInterface::class);

foreach ($containerBuilder->findTaggedServiceIds('controller') as $id => $value) {
    $controller = $containerBuilder->getDefinition($id);
    $dispatcher->addMethodCall('addController', [$controller]);
}

$repoManager = $containerBuilder->getDefinition(RepositoryManagerInterface::class);

foreach ($containerBuilder->findTaggedServiceIds('repository') as $id => $value) {
    $repository = $containerBuilder->getDefinition($id);
    $repoManager->addMethodCall('addRepository', [$repository]);
}

return new SymfonyContainer($containerBuilder);