<?php

use DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;


require __DIR__ . '/vendor/autoload.php';

/** @var Container $container */
$container = require __DIR__ . '/config/container.php';

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);
$container->set(Request::class, $request);

$urlMatcher = new UrlMatcher($container->get('routes'), $context);
$generator = $container->get(UrlGenerator::class);
$generator->setContext($context);

$controllerResolver = new ContainerControllerResolver($container);
$argumentResolver = new ArgumentResolver();

try {
    $request->attributes->add($urlMatcher->match($request->getPathInfo()));

    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);

} catch (ResourceNotFoundException $e) {
    $response = new Response("Page does not exist", 404);
} catch (Exception $e) {
    $response = new Response("An error has occurred", 500);
}

$response->send();