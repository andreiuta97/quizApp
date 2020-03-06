<?php

use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use QuizApp\Controller\UserController;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\DependencyInjection\SymfonyContainer;
use QuizApp\Entity\User;
use QuizApp\Repository\UserRepository;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\RepositoryInterface;
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


// Configure Controllers
$containerBuilder->register(UserController::class, UserController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class))
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



// .........


return new SymfonyContainer($containerBuilder);