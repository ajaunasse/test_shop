<?php

declare(strict_types=1);

namespace App\Domain\Product;

interface CommentRepositoryInterface
{
    /** @return Comment[] */
    public function findAllByProduct(int $productId): array;

    public function save(Comment $comment): void;

    public function update(Comment $comment): void;

    public function delete(string $commentUuid, int $productId): void;
}
