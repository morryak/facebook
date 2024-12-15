<?php

declare(strict_types=1);

namespace App\UseCase\ProcessMusic\Spotify;

use Doctrine\DBAL\Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class SpotifyApiService
{
    public function __construct(
        private ClientInterface $client,
        private Manager $manager,
    ) {
    }
    private const SPOTIFTY_TOKEN_URL = 'https://accounts.spotify.com/api/token';
    private const SPOTIFTY_TRACK_URL = 'https://api.spotify.com/v1/me/tracks';

    /**
     * @throws GuzzleException
     * @throws Exception
     * @throws JsonException
     */
    public function getAccessToken(): string
    {
        $response = $this->client->request(
            Request::METHOD_POST,
            self::SPOTIFTY_TOKEN_URL,
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

    public function getTracks(string $accessToken)
    {
        $response = $this->client->request(
            Request::METHOD_GET,
            self::SPOTIFTY_TRACK_URL,
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]
        );

        return json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}