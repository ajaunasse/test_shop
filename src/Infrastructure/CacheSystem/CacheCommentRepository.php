<?php

declare(strict_types=1);

namespace App\Infrastructure\CacheSystem;

use App\Domain\Product\Comment;
use App\Domain\Product\CommentRepositoryInterface;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

final class CacheCommentRepository implements CommentRepositoryInterface
{
    private const COMMENT_CACHE_KEY = 'comments_product_%d';

    public function __construct(private CacheItemPoolInterface $cacheItemPool)
    {
    }

    /** @return Comment[] */
    public function findAllByProduct(int $productId): array
    {
        $comments = $this->cacheItemPool
            ->getItem(sprintf(self::COMMENT_CACHE_KEY, $productId))
            ->get();

        if ($comments === null) {
            return [];
        }

        return array_map(
            function ($value, $key) use ($productId) {
            return Comment::createFromArray([
                'commentId' => $key,
                'content' => $value,
                'productId' => $productId,
            ]);
        },
            $comments,
            array_keys($comments),
        );
    }

    public function save(Comment $comment): void
    {
        $commentCacheItem = $this->getCacheItemByProductId($comment->productId());

        $value = $commentCacheItem->get();

        $value[$comment->id()] = $comment->content();

        $commentCacheItem->set($value);

        $this->flushAndCommit($commentCacheItem);
    }

    public function update(Comment $comment): void
    {
        $commentCacheItem = $this->getCacheItemByProductId($comment->productId());

        $value = $commentCacheItem->get();

        if (!isset($value[$comment->id()])) {
            throw new ResourceNotFoundException('This comment does not exist');
        }

        $value[$comment->id()] = $comment->content();

        $commentCacheItem->set($value);

        $this->flushAndCommit($commentCacheItem);
    }

    public function delete(string $commentUuid, int $productId): void
    {
        $commentCacheItem = $this->getCacheItemByProductId($productId);

        $value = $commentCacheItem->get();

        if (!isset($value[$commentUuid])) {
            throw new ResourceNotFoundException('This comment does not exist');
        }

        unset($value[$commentUuid]);

        $commentCacheItem->set($value);

        $this->flushAndCommit($commentCacheItem);
    }

    private function getCacheItemByProductId(int $productId): CacheItemInterface
    {
        return $this->cacheItemPool
            ->getItem(sprintf(self::COMMENT_CACHE_KEY, $productId));
    }

    private function flushAndCommit(CacheItemInterface $cacheItemPool): void
    {
        $this->cacheItemPool->save($cacheItemPool);
        $this->cacheItemPool->commit();
    }
}
