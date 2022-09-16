<?php

declare(strict_types=1);

namespace App\Domain\Product;

final class Product
{
    public function __construct(
        private int $id,
        private string $title,
        private string $category,
        private string $description,
        private float $price,
        private string $imageUrl,
        private float $rate,
        private int $reviewCount,
    ) {
    }

    public static function fromApiData(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title'],
            category: $data['category'],
            description: $data['description'],
            price: (float) $data['price'],
            imageUrl: $data['image'],
            rate: (float) $data['rating']['rate'],
            reviewCount: (int) $data['rating']['count'],
        );
    }

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function imageUrl(): string
    {
        return $this->imageUrl;
    }

    public function rate(): float
    {
        return $this->rate;
    }

    public function reviewCount(): int
    {
        return $this->reviewCount;
    }
}
