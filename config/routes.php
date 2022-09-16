<?php

declare(strict_types=1);

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('product_list', new Route('/', [
    '_controller' => 'App\UserInterface\Controller\ProductController::list',
]));

$routes->add('product_detail', new Route('/product/{id}', [
    '_controller' => 'App\UserInterface\Controller\ProductController::detail',
    '_requirements' => [
        'id' => '\d+',
    ],
]));

$routes->add('add_comment', new Route('/product/{productId}/comment/add', [
    '_controller' => 'App\UserInterface\Controller\CommentController::post',
    '_requirements' => [
        'productId' => '\d+',
    ],
]));

$routes->add('edit_comment', new Route('/product/{productId}/comment/{commentId}/edit', [
    '_controller' => 'App\UserInterface\Controller\CommentController::put',
    '_requirements' => [
        'productId' => '\d+',
    ],
]));

$routes->add('delete_comment', new Route('/product/{productId}/comment/delete/{commentId}', [
    '_controller' => 'App\UserInterface\Controller\CommentController::delete',
    '_requirements' => [
        'productId' => '\d+',
    ],
]));

return $routes;
