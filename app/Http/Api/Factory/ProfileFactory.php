<?php

declare(strict_types=1);

namespace App\Http\Api\Factory;

use App\Http\Api\Entity\Profile;
use App\Http\Api\Entity\ProfileInterface;
use App\Services\Provider\GroupProvider;
use App\Services\Provider\GroupProviderInterface;
use Illuminate\Support\Arr;

class ProfileFactory implements ProfileFactoryInterface
{
    private GroupProviderInterface $groupProvider;

    public function __construct(
        GroupProvider $groupProvider
    ) {
        $this->groupProvider = $groupProvider;
    }

    public function createFromAuthSchResponse(array $response): ProfileInterface
    {
        $profile = new Profile();

        $profile->setInternalId($response['internal_id']);
        $profile->setDisplayName($response['displayName']);
        $profile->setSurname($response['sn']);
        $profile->setGivenNames($response['givenName']);
        $profile->setEmailAddress($response['mail']);

        foreach ($response['eduPersonEntitlement'] as $groupData) {
            $group = $this->groupProvider->provide((int)$groupData['id']);

            $group->setName($groupData['name']);

            $profile->addGroup($groupData['status'], $group);
        }

        return $profile;
    }
}
