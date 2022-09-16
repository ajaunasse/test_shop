<?php

declare(strict_types=1);

namespace App\Test\Helper\Domain;

use App\Domain\Product\Comment;

final class StubCommentFactory
{
    public static function generate(string $uuid = null, int $productId = 0, string $content = null): Comment
    {
        if ($productId === 0) {
            $productId = random_int(1, 1000);
        }

        if ($content === null) {
            $content = 'My comment test';
        }

        if ($uuid === null) {
            return Comment::createFromContentAndProductId($content, $productId);
        }

        return Comment::createFromArray([
            'productId' => $productId,
            'content' => $content,
            'commentId' => $uuid,
        ]);
    }
}
