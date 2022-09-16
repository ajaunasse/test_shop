<?php

declare(strict_types=1);

namespace App\Test\Unit\Domain;

use App\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @small
 */
final class ProductTest extends TestCase
{
    /** @test */
    public function it_should_create_a_product_from_array_data(): void
    {
        $id = 1;
        $title = 'Test product';
        $description = 'Test description';
        $category = 'Test category';
        $price = 10.00;
        $image = 'http://fake/image.png';
        $rate = 9.0;
        $numberOfReview = 125;

        $data = [
            'id' => $id,
            'title' => $title,
            'category' => $category,
            'description' => $description,
            'price' => $price,
            'image' => $image,
            'rating' => [
                'rate' => $rate,
                'count' => $numberOfReview,
            ],
        ];

        $product = Product::fromApiData($data);

        self::assertSame($id, $product->id());
        self::assertSame($title, $product->title());
        self::assertSame($description, $product->description());
        self::assertSame($category, $product->category());
        self::assertSame($price, $product->price());
        self::assertSame($image, $product->imageUrl());
        self::assertSame($rate, $product->rate());
        self::assertSame($numberOfReview, $product->reviewCount());
    }
}
