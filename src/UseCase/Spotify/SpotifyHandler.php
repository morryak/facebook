<?php

declare(strict_types=1);

namespace App\UseCase\Spotify;

use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

use function dd;
use function json_decode;

readonly class SpotifyHandler
{
    public function __construct(
        private ClientInterface $client,
        private Manager $manager
    ) {
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     * @throws JsonException
     */
    public function handle(): void
    {
//        $access_token = $this->manager->getToken();
        $access_token = 'BQC3jCtQ16dNmenHb7dU1uXY1R7I2f7-I9veCTOS8qd5rAxmWD4R-WstNOBYepeVr9Jt0RtpKNiE4UVSdSClwXoKUG23nd-iloSZk6M28a4E67_ZN3cuHl6sNTA9VmdTqbIaANwrrM3EI4yJe3IK04oHzxdMw5HQevntkkl-hGEdrr9d8FuLr26-jp2PM0DhpZwuRqbxslkWTz48gpmlwGM';

        try {
            // авторизовыываемся и получаем
            $this->getTrackList($access_token);
        } catch (Throwable $e) {
            if ($e->getCode() === Response::HTTP_UNAUTHORIZED) {
                $access_token = $this->getAccessTokenByRefreshToken();
                $this->getTrackList($access_token);
            }
        }
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws \Exception
     */
    private function getTrackList(string $access_token)
    {
        $url = 'https://api.spotify.com/v1/me/tracks';
        $response = $this->client->request(
            Request::METHOD_GET,
            $url,
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
            ]
        );
        $body = json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

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
        dd(1);
        dd($body);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     * @throws JsonException
     */
    private function getAccessTokenByRefreshToken(): string
    {
        $response = $this->client->request(
            Request::METHOD_POST,
            'https://accounts.spotify.com/api/token',
            [
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'refresh_token',
                    'client_id' => 'c9a3b360210748bebaf3d72c5834c743',
                    'client_secret' => 'b670999c179647df88d8ef91653f641a',
                    'refresh_token' => 'AQA6LRfkdE-YiGvlbYwiQp04cQnLcsHRl92S3ftMm1SODLBg_CmHjAmEINaUPopPmq-tTca94bhBrt5Fkc4FeslqVyqJS9KKs9sKQfxWiZUsR9mEoWSXK7isV3_N3fGQbsw',
                ],
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]
        );

        $body = json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->manager->updateToken($body['access_token']);

        return $body['access_token'];
    }
}
