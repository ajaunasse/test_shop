<?php

declare(strict_types=1);

namespace App\Test\Unit\UserInterface\Controller;

use App\Test\Helper\Domain\StubCommentFactory;
use App\Test\Helper\Domain\StubProductFactory;
use App\Test\Helper\Repository\InMemoryCommentRepository;
use App\Test\Helper\Repository\InMemoryProductRepository;
use App\UserInterface\Controller\ProductController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @internal
 *
 * @small
 */
final class ProductControllerTest extends TestCase
{
    private InMemoryProductRepository $productRepository;

    private InMemoryCommentRepository $commentRepository;

    private Environment $environment;

    private UrlGeneratorInterface $urlGenerator;

    private ProductController $controller;

    protected function setUp(): void
    {
        $this->productRepository = new InMemoryProductRepository(StubProductFactory::generate(1));
        $this->commentRepository = new InMemoryCommentRepository(StubCommentFactory::generate('123', 1));
        $this->environment = self::createMock(Environment::class);
        $this->urlGenerator = self::createMock(UrlGeneratorInterface::class);

        $this->controller = new ProductController(
            $this->environment,
            $this->productRepository,
            $this->commentRepository,
            $this->urlGenerator,
        );

        parent::setUp();
    }

    /** @test */
    public function list_should_render_list_of_products(): void
    {
        $this->environment
            ->expects(self::once())
            ->method('render')
            ->with(
                'list_product.html.twig',
                [
                    'products' => $this->productRepository->findAll(),
                    'router' => $this->urlGenerator,
                ],
            );

        $response = $this->controller->list();
        self::assertSame(200, $response->getStatusCode());
    }

    /** @test */
    public function detail_should_render_product_detail_and_comments(): void
    {
        $this->environment
            ->expects(self::once())
            ->method('render')
            ->with(
                'detail_product.html.twig',
                [
                    'product' => $this->productRepository->findById(1),
                    'comments' => $this->commentRepository->findAllByProduct(1),
                    'router' => $this->urlGenerator,
                ],
            );

        $response = $this->controller->detail(1);

        self::assertSame(200, $response->getStatusCode());
    }

    /** @test */
    public function detail_with_a_non_existent_product_should_throw_an_exception(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        $this->controller->detail(2);
    }
}
