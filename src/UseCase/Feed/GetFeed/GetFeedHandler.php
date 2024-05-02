<?php

declare(strict_types=1);

namespace App\UseCase\Feed\GetFeed;

use App\AppBundle\Manager\RedisManager;
use App\Entity\User;
use Doctrine\DBAL\Exception as DbalException;
use JsonException;

use function json_decode;
use function json_encode;

readonly class GetFeedHandler
{
    private const CACHE_TTL = 3_600;

    public function __construct(private GetFeedManager $manager, private RedisManager $redisManager)
    {
    }

    /**
     * @throws DbalException
     * @throws JsonException
     */
    public function handle(?User $user, int $limit, int $offset): array
    {
        $cacheKey = 'user_feed_' . $user->getId();
        $redis = $this->redisManager->getConnection();
        $cachedResult = $redis->get($cacheKey);

        if ($cachedResult) {
            return json_decode($cachedResult, true, 512, JSON_THROW_ON_ERROR);
        }

        $feedList = $this->manager->getFeedList($user, $limit, $offset);

        if ($feedList) {
            $redis->setex(
                $cacheKey,
                self::CACHE_TTL,
                json_encode($feedList, JSON_THROW_ON_ERROR)
            );
        }

        return $feedList;
    }
}
