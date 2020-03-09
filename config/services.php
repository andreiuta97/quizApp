<?php

use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use QuizApp\Controller\QuestionTemplateController;
use QuizApp\Controller\QuizTemplateController;
use QuizApp\Controller\UserController;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\DependencyInjection\SymfonyContainer;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Entity\User;
use QuizApp\Repository\QuestionTemplateRepository;
use QuizApp\Repository\QuizTemplateRepository;
use QuizApp\Repository\UserRepository;
use QuizApp\Service\QuestionTemplateService;
use QuizApp\Service\QuizTemplateService;
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
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
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

$containerBuilder->register(QuizTemplateRepository::class, QuizTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizTemplate::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

// Configure Services

$containerBuilder->register(QuestionTemplateService::class, QuestionTemplateService::class)
    ->addArgument(new Reference(QuestionTemplateRepository::class));

$containerBuilder->register(QuizTemplateService::class, QuizTemplateService::class)
    ->addArgument(new Reference(QuizTemplateRepository::class));


// Configure Controllers
$containerBuilder->register(UserController::class, UserController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addTag('controller');

$containerBuilder->register(QuestionTemplateController::class, QuestionTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(QuestionTemplateService::class))
    ->addTag('controller');

$containerBuilder->register(QuizTemplateController::class, QuizTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
    ->addArgument(new Reference(QuizTemplateService::class))
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