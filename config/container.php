<?php

declare(strict_types=1);

use App\Domain\Product\CommentRepositoryInterface;
use App\Domain\Product\ProductRepositoryInterface;
use App\Infrastructure\Api\ProductApiRepository;
use App\Infrastructure\CacheSystem\CacheCommentRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function DI\autowire;

$routes = require __DIR__ . '/routes.php';

$config = [
    GuzzleHttp\Client::class => static fn (ContainerInterface $c): GuzzleHttp\Client => new GuzzleHttp\Client(
        ['base_uri' => 'https://fakestoreapi.com'],
    ),
    ProductRepositoryInterface::class => autowire(ProductApiRepository::class),
    CommentRepositoryInterface::class => autowire(CacheCommentRepository::class),
    App\UserInterface\Controller\ProductController::class => autowire(),
    Environment::class => static fn (ContainerInterface $c): Environment => new Environment(new FilesystemLoader([__DIR__ . '/../templates/'])),
    UrlGeneratorInterface::class => new UrlGenerator($routes, new RequestContext()),
    CacheItemPoolInterface::class => new FilesystemAdapter(),
];

// Configure container
$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions($config);
$containerBuilder->addDefinitions($routes->all());
$containerBuilder->useAnnotations(false);
$containerBuilder->useAutowiring(true);

// Build container
$container = $containerBuilder->build();

// Inject config
$container->set('config', $config);
$container->set('routes', $routes);

return $container;
