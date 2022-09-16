<?php

declare(strict_types=1);

namespace App\UserInterface\Controller;

use App\Domain\Product\CommentRepositoryInterface;
use App\Domain\Product\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class ProductController
{
    public function __construct(
        private Environment $templating,
        private ProductRepositoryInterface $productRepository,
        private CommentRepositoryInterface $commentRepository,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function list(): Response
    {
        return new Response($this->templating->render('list_product.html.twig', [
            'products' => $this->productRepository->findAll(),
            'router' => $this->urlGenerator,
        ]));
    }

    public function detail(int $id): Response
    {
        return new Response($this->templating->render('detail_product.html.twig', [
            'product' => $this->productRepository->findById($id),
            'comments' => $this->commentRepository->findAllByProduct($id),
            'router' => $this->urlGenerator,
        ]));
    }
}
