<?php

declare(strict_types=1);

namespace App\UserInterface\Controller;

use App\Domain\Product\Comment;
use App\Domain\Product\CommentRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CommentController
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
        private Request $request,
        private UrlGeneratorInterface $router,
    ) {
    }

    public function post(int $productId): Response
    {
        $comment = $this->request->request->get('content');

        $comment = Comment::createFromContentAndProductId($comment, $productId);

        $this->commentRepository->save($comment);

        return new RedirectResponse($this->router->generate('product_detail', ['id' => $productId]));
    }

    public function put(int $productId, string $commentId): Response
    {
        $formData = $this->request->request->all();

        $formData['productId'] = $productId;
        $formData['commentId'] = $commentId;

        $comment = Comment::createFromArray($formData);

        $this->commentRepository->update($comment);

        return new RedirectResponse($this->router->generate('product_detail', ['id' => $productId]));
    }

    public function delete(int $productId, string $commentId): Response
    {
        $this->commentRepository->delete($commentId, $productId);

        return new RedirectResponse($this->router->generate('product_detail', ['id' => $productId]));
    }
}
