<?php

declare(strict_types=1);

namespace App\Test\Unit\Domain;

use App\Domain\Product\Comment;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @small
 */
final class CommentTest extends TestCase
{
    /** @test */
    public function it_should_create_comment_from_content_and_product_id(): void
    {
        $productId = 1;
        $content = 'Test comment';

        $comment = Comment::createFromContentAndProductId($content, $productId);

        self::assertSame($productId, $comment->productId());
        self::assertSame($content, $comment->content());
    }

    /** @test */
    public function it_should_create_comment_from_array_data(): void
    {
        $productId = 1;
        $content = 'Test comment';
        $commentId = '12qsdq21';

        $data = [
            'productId' => $productId,
            'content' => $content,
            'commentId' => $commentId,
        ];

        $comment = Comment::createFromArray($data);

        self::assertSame($productId, $comment->productId());
        self::assertSame($content, $comment->content());
        self::assertSame($commentId, $comment->id());
    }

    /** @test */
    public function it_should_trim_comment_content_from_content_and_product_id(): void
    {
        $productId = 1;
        $content = '    Test comment      ';
        $expectedContent = 'Test comment';

        $comment = Comment::createFromContentAndProductId($content, $productId);

        self::assertSame($productId, $comment->productId());
        self::assertSame($expectedContent, $comment->content());
    }

    /** @test */
    public function it_should_trim_comment_content_from_array_data(): void
    {
        $productId = 1;
        $content = '    Test comment      ';
        $expectedContent = 'Test comment';
        $commentId = '12qsdq21';

        $data = [
            'productId' => $productId,
            'content' => $content,
            'commentId' => $commentId,
        ];

        $comment = Comment::createFromArray($data);

        self::assertSame($productId, $comment->productId());
        self::assertSame($expectedContent, $comment->content());
        self::assertSame($commentId, $comment->id());
    }
}
