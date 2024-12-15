<?php

declare(strict_types=1);

namespace App\UseCase\ProcessMusic;

use App\UseCase\ProcessMusic\Spotify\Manager;
use App\UseCase\ProcessMusic\Spotify\SpotifyApiService;
use App\UseCase\ProcessMusic\Vk\VkService;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

readonly class ProcessMusicHandler
{
    public function __construct(
        private Manager $manager,
        private SpotifyApiService $spotifyApiService,
        private VkService $vkService
    ) {
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     * @throws JsonException
     */
    public function handle(): void
    {
        $accessToken = $this->manager->getToken();

        try {
            $this->process($accessToken);
        } catch (Throwable $e) {
            if ($e->getCode() === Response::HTTP_UNAUTHORIZED) {
                $accessToken = $this->spotifyApiService->getAccessToken();
                $this->process($accessToken);
            }
        }
    }

    /**
     * @throws JsonException
     */
    private function process(string $accessToken): void
    {
        $body = $this->spotifyApiService->getTracks($accessToken);
        $today = (new DateTimeImmutable())->format('Y-m-d');
        $trackList = [];

        foreach ($body['items'] as $trackInfo) {
            if ($today !== (new DateTimeImmutable($trackInfo['added_at']))->format('Y-m-d')) {
                continue;
            }

            $trackName = $trackInfo['track']['name'];
            $artistList = [];

            foreach ($trackInfo['track']['artists'] as $artist) {
                $artistList[] = $artist['name'];
            }

            $trackList[] = sprintf('%s %s', implode(', ', $artistList), $trackName);
        }

        if (empty($trackList)) {
            return;
        }

        $this->vkService->processTrackList($trackList);
    }
}
