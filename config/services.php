<?php

use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use Framework\Controller\UserController;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\DependencyInjection\SymfonyContainer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder = new ContainerBuilder();

$baseDir = dirname(__DIR__);
$config=require $baseDir . '/config/config.php';


$containerBuilder->setParameter('routerConfig', $config['router']);
$containerBuilder->register(RouterInterface::class, Router::class)
    ->addArgument('%routerConfig%');

$containerBuilder->setParameter('rendererConfig', $config['renderer']['base_view_path']);
$containerBuilder->register(RendererInterface::class, Renderer::class)
    ->addArgument('%rendererConfig%');

$containerBuilder->register(UserController::class, UserController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addTag('controller');

$containerBuilder->setParameter('dispatcherConfig', $config['dispatcher']);
$containerBuilder->register(DispatcherInterface::class, Dispatcher::class)
    ->addArgument('%dispatcherConfig%');

$dispatcher = $containerBuilder->getDefinition(DispatcherInterface::class);

foreach ($containerBuilder->findTaggedServiceIds('controller') as $id => $value) {
    $controller = $containerBuilder->getDefinition($id);
    $dispatcher->addMethodCall('addController', [$controller]);
}

// .........


return new SymfonyContainer($containerBuilder);