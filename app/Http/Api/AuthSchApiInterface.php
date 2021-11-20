<?php

declare(strict_types=1);

namespace App\Http\Api;

use App\Http\Api\Entity\ProfileInterface;

interface AuthSchApiInterface
{
    public const ENDPOINT_ACCESS_TOKEN = 'https://auth.sch.bme.hu/oauth2/token';
    public const ENDPOINT_PROFILE = 'https://auth.sch.bme.hu/api/profile';

    public function getAccessToken(string $authorizationCode): string;

    public function getProfile(string $accessToken): ProfileInterface;
}
