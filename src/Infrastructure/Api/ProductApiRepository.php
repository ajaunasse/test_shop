<?php

declare(strict_types=1);

namespace App\Infrastructure\Api;

use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

final class ProductApiRepository implements ProductRepositoryInterface
{
    public const PRODUCT_LIST_URI = '/products';
    public const PRODUCT_DETAIL_URI = '/products/%d';

    public function __construct(private Client $apiClient)
    {
    }

    /** @return Product[] */
    public function findAll(): array
    {
        $content = $this->apiClient
            ->request(Request::METHOD_GET, self::PRODUCT_LIST_URI)
            ->getBody()
            ->getContents();

        $data = json_decode($content, true);

        return array_map(fn ($value) => Product::fromApiData($value), $data);
    }

    public function findById(int $idProduct): Product
    {
        $content = $this->apiClient
            ->request(Request::METHOD_GET, sprintf(self::PRODUCT_DETAIL_URI, $idProduct))
            ->getBody()
            ->getContents();

        $data = json_decode($content, true);

        if ($data === null) {
            throw new ResourceNotFoundException(sprintf('Product id %s does not exist', $idProduct));
        }

        return Product::fromApiData($data);
    }
}
