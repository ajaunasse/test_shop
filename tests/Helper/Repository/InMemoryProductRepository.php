<?php

declare(strict_types=1);

namespace App\Test\Helper\Repository;

use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

final class InMemoryProductRepository implements ProductRepositoryInterface
{
    /** @var Product[] */
    private array $products = [];

    public function __construct(Product ...$products)
    {
        foreach ($products as $product) {
            $this->save($product);
        }
    }

    public function findAll(): array
    {
        return $this->products;
    }

    public function findById(int $idProduct): Product
    {
        $products = array_filter($this->products, function (Product $product) use ($idProduct) {
            if ($product->id() === $idProduct) {
                return $product;
            }
        });

        if (empty($products)) {
            throw new ResourceNotFoundException('Product does not exist');
        }

        return $products[0];
    }

    public function save(Product $product): void
    {
        $this->products[] = $product;
    }
}
