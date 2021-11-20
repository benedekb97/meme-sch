<?php

declare(strict_types=1);

namespace App\Http\Api;

use App\Http\Api\Entity\ProfileInterface;
use App\Http\Api\Factory\ProfileFactory;
use App\Http\Api\Factory\ProfileFactoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use http\Exception\RuntimeException;

class AuthSchApi implements AuthSchApiInterface
{
    private Client $client;

    private ProfileFactoryInterface $profileFactory;

    public function __construct(
        Client $client,
        ProfileFactory $profileFactory
    ) {
        $this->client = $client;
        $this->profileFactory = $profileFactory;
    }

    /**
     * @throws GuzzleException
     */
    public function getAccessToken(string $authorizationCode): string
    {
        $response = $this->client->post(
            $this->buildAccessTokenUrl($authorizationCode),
            [
                'form_params' => [
                    'code' => $authorizationCode,
                    'grant_type' => 'authorization_code',
                ],
                'auth' => [
                    config('auth.sch.client_id'),
                    config('auth.sch.client_key'),
                ]
            ]
        );

        $response = json_decode($response->getBody()->getContents(), true);

        if (!array_key_exists('access_token', $response)) {
            throw new RuntimeException(
                'auth.sch response does not contain access token!'
            );
        }

        return (string)$response['access_token'];
    }

    public function getProfile(string $accessToken): ProfileInterface
    {
        $response = $this->client->get(
            $this->buildProfileUrl($accessToken)
        );

        $response = json_decode($response->getBody()->getContents(), true);

        return $this->profileFactory->createFromAuthSchResponse($response);
    }

    private function buildAccessTokenUrl(string $authorizationCode): string
    {
        return self::ENDPOINT_ACCESS_TOKEN;
    }

    private function buildProfileUrl(string $accessToken): string
    {
        return sprintf(
            '%s?access_token=%s',
            self::ENDPOINT_PROFILE,
            $accessToken
        );
    }
}
