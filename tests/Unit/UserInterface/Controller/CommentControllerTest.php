<?php

declare(strict_types=1);

namespace App\Test\Unit\UserInterface\Controller;

use App\Domain\Product\Comment;
use App\Test\Helper\Domain\StubCommentFactory;
use App\Test\Helper\Repository\InMemoryCommentRepository;
use App\UserInterface\Controller\CommentController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @internal
 *
 * @small
 */
final class CommentControllerTest extends TestCase
{
    private InMemoryCommentRepository $commentRepository;

    private UrlGeneratorInterface $urlGenerator;

    private CommentController $controller;

    protected function setUp(): void
    {
        $this->commentRepository = new InMemoryCommentRepository(StubCommentFactory::generate('123', 1));

        $this->urlGenerator = self::createMock(UrlGeneratorInterface::class);

        parent::setUp();
    }

    /** @test */
    public function post_should_add_a_comment(): void
    {
        $request = new Request([], ['content' => 'My new comment']);

        $this->controller = new CommentController(
            $this->commentRepository,
            $request,
            $this->urlGenerator,
        );

        self::assertCount(1, $this->commentRepository->findAllByProduct(1));

        $this->urlGenerator
            ->expects(self::once())
            ->method('generate')
            ->with('product_detail', ['id' => 1])
            ->willReturn('/product/detail/1');

        $response = $this->controller->post(1);

        self::assertCount(2, $this->commentRepository->findAllByProduct(1));

        self::assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /** @test */
    public function put_should_update_comment(): void
    {
        $comment = StubCommentFactory::generate('abcd', 1, 'My new content');

        $this->commentRepository->save($comment);

        $request = new Request([], ['content' => 'Edit content']);

        $this->controller = new CommentController(
            $this->commentRepository,
            $request,
            $this->urlGenerator,
        );

        self::assertCount(2, $this->commentRepository->findAllByProduct(1));

        $this->urlGenerator
            ->expects(self::once())
            ->method('generate')
            ->with('product_detail', ['id' => 1])
            ->willReturn('/product/detail/1');

        $response = $this->controller->put(1, 'abcd');

        self::assertCount(2, $this->commentRepository->findAllByProduct(1));

        /** @var Comment $comment */
        $comment = $this->commentRepository->findAllByProduct(1)['abcd'];

        self::assertSame('Edit content', $comment->content());

        self::assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /** @test */
    public function delete_shoud_remove_comment(): void
    {
        $request = new Request();

        self::assertCount(1, $this->commentRepository->findAllByProduct(1));

        $this->controller = new CommentController(
            $this->commentRepository,
            $request,
            $this->urlGenerator,
        );

        $this->urlGenerator
            ->expects(self::once())
            ->method('generate')
            ->with('product_detail', ['id' => 1])
            ->willReturn('/product/detail/1');

        $response = $this->controller->delete(1, '123');

        self::assertCount(0, $this->commentRepository->findAllByProduct(1));

        self::assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /** @test */
    public function update_a_non_existent_comment_should_throw_exception(): void
    {
        $request = new Request([], ['content' => 'Edit content']);

        $this->controller = new CommentController(
            $this->commentRepository,
            $request,
            $this->urlGenerator,
        );

        self::expectException(ResourceNotFoundException::class);

        $this->controller->put(1, 'abcd');
    }

    /** @test */
    public function uremove_a_non_existent_comment_should_throw_exception(): void
    {
        $request = new Request([], ['content' => 'Edit content']);

        $this->controller = new CommentController(
            $this->commentRepository,
            $request,
            $this->urlGenerator,
        );

        self::expectException(ResourceNotFoundException::class);

        $this->controller->delete(1, 'abcd');
    }
}
