<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\Image;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileController extends Controller
{
    public const MIME_TYPE_IMAGE_PNG = 'image/png';
    public const MIME_TYPE_IMAGE_JPEG = 'image/jpeg';

    public const ALLOWED_MIME_TYPES = [
        self::MIME_TYPE_IMAGE_JPEG,
        self::MIME_TYPE_IMAGE_PNG,
    ];

    public function settings()
    {
        return view(
            'pages.profile.settings',
            [
                'user' => $this->getUser(),
            ]
        );
    }

    public function edit(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        $nickName = $request->get('nickname');

        if ($nickName !== null && strlen($nickName) > 64) {
            return new RedirectResponse(route('profile.settings'));
        }

        $user->setNickName($nickName);

        /** @var UploadedFile|null $profilePicture */
        $profilePicture = $request->file('file');

        if (
            $profilePicture !== null &&
            in_array($profilePicture->getMimeType(), PostController::ALLOWED_MIME_TYPES, true)
        ) {
            $fileName = Str::random() . '.' . $profilePicture->getClientOriginalExtension();

            $profilePicture->storeAs('images', $fileName);

            $filePath = sprintf('images/%s', $fileName);

            $image = new Image();

            $image->setFilePath($filePath);
            $image->setName($user->getName());
            $image->setConvertable(true);

            $this->entityManager->persist($image);

            $user->setProfilePicture($image);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new RedirectResponse(route('profile.settings'));
    }
}
