<?php

declare(strict_types=1);

namespace App\Test\Helper\Repository;

use App\Domain\Product\Comment;
use App\Domain\Product\CommentRepositoryInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

final class InMemoryCommentRepository implements CommentRepositoryInterface
{
    private array $comments = [];

    public function __construct(Comment ...$comments)
    {
        foreach ($comments as $comment) {
            $this->save($comment);
        }
    }

    public function findAllByProduct(int $productId): array
    {
        return $this->comments[$productId];
    }

    public function save(Comment $comment): void
    {
        $this->comments[$comment->productId()][$comment->id()] = $comment;
    }

    public function update(Comment $comment): void
    {
        if (!isset($this->comments[$comment->productId()][$comment->id()])) {
            throw new ResourceNotFoundException('Comment does not exist');
        }

        $this->comments[$comment->productId()][$comment->id()] = $comment;
    }

    public function delete(string $commentUuid, int $productId): void
    {
        if (!isset($this->comments[$productId][$commentUuid])) {
            throw new ResourceNotFoundException('Comment does not exist');
        }

        unset($this->comments[$productId][$commentUuid]);
    }
}
