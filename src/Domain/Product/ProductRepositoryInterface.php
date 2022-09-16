<?php

declare(strict_types=1);

namespace App\Domain\Product;

interface ProductRepositoryInterface
{
    /** @return Product[] */
    public function findAll(): array;

    public function findById(int $idProduct): Product;
}
