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

        if (strlen($nickName) > 64) {
            return new RedirectResponse(route('profile.settings'));
        }

        $user->setNickName($nickName);

        /** @var UploadedFile|null $profilePicture */
        $profilePicture = $request->file('image');

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

            $this->entityManager->persist($image);

            $user->setProfilePicture($image);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new RedirectResponse(route('profile.settings'));
    }
}
