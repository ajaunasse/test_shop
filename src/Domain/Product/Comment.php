<?php

declare(strict_types=1);

namespace App\Domain\Product;

final class Comment
{
    private function __construct(
        private string $uuid,
        private int $productId,
        private string $content,
    ) {
    }

    public static function createFromContentAndProductId(string $content, int $productId): self
    {
        return new self(
            uuid: uniqid(),
            productId: $productId,
            content: trim($content),
        );
    }

    /** @param array<string, string|int> $data */
    public static function createFromArray(array $data): self
    {
        return new self(
            uuid: (string) $data['commentId'],
            productId: (int) $data['productId'],
            content: trim((string) $data['content']),
        );
    }

    public function id(): string
    {
        return $this->uuid;
    }

    public function productId(): int
    {
        return $this->productId;
    }

    public function content(): string
    {
        return $this->content;
    }
}
