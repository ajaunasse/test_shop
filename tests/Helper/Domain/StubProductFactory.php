<?php

declare(strict_types=1);

namespace App\Test\Helper\Domain;

use App\Domain\Product\Product;

final class StubProductFactory
{
    public static function generate(int $productId = 0)
    {
        if ($productId === 0) {
            $productId = random_int(1, 1000);
        }

        return Product::fromApiData([
            'id' => $productId,
            'title' => 'Mac book pro',
            'category' => 'Laptop',
            'description' => 'Laptop from Apple brand',
            'price' => 1099.99,
            'image' => 'http://fake-image/mac-book-pro.png',
            'rating' => [
                'rate' => 9.5,
                'count' => 1200,
            ],
        ]);
    }
}
