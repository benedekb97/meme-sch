<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\UserInterface;
use App\Services\Factory\UserFactoryInterface;
use App\Http\Api\AuthSchApi;
use App\Http\Api\AuthSchApiInterface;
use App\Http\Api\Entity\ProfileInterface;
use App\Services\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\Session\Store;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthenticationController extends Controller
{
    private const AUTH_SCH_REDIRECT_URL = 'https://auth.sch.bme.hu/site/login';

    private const SESSION_STATE_KEY = 'auth.sch.state';

    private const AUTH_SCH_SCOPE_BASIC = 'basic';
    private const AUTH_SCH_SCOPE_DISPLAY_NAME = 'displayName';
    private const AUTH_SCH_SCOPE_SURNAME = 'sn';
    private const AUTH_SCH_SCOPE_GIVEN_NAME = 'givenName';
    private const AUTH_SCH_SCOPE_EMAIL_ADDRESS = 'mail';
    private const AUTH_SCH_SCOPE_EDU_PERSON_ENTITLEMENT = 'eduPersonEntitlement';

    private const AUTH_SCH_SCOPES = [
        self::AUTH_SCH_SCOPE_BASIC                  => true,
        self::AUTH_SCH_SCOPE_DISPLAY_NAME           => true,
        self::AUTH_SCH_SCOPE_SURNAME                => true,
        self::AUTH_SCH_SCOPE_GIVEN_NAME             => true,
        self::AUTH_SCH_SCOPE_EMAIL_ADDRESS          => true,
        self::AUTH_SCH_SCOPE_EDU_PERSON_ENTITLEMENT => true,
    ];

    private Store $sessionStore;

    private AuthSchApiInterface $authSchApi;

    private UserRepositoryInterface $userRepository;

    private UserFactoryInterface $userFactory;

    private AuthManager $authManager;

    public function __construct(
        EntityManager $entityManager,
        Store           $sessionStore,
        AuthSchApi      $authSchApi,
        UserRepositoryInterface $userRepository,
        UserFactoryInterface $userFactory,
        AuthManager $authManager
    )
    {
        $this->sessionStore = $sessionStore;
        $this->authSchApi = $authSchApi;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->authManager = $authManager;

        parent::__construct($entityManager);
    }

    public function redirect(): RedirectResponse
    {
        return new RedirectResponse($this->buildRedirectUrl());
    }

    public function callback(Request $request): Response
    {
        if (!$request->request->has('code')) {
            return new JsonResponse(
                [
                    'error' => 'Response does not contain an access code.',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $authorizationCode = $request->get('code');
        $state = $request->get('state');

        if ($state !== $this->sessionStore->get(self::SESSION_STATE_KEY)) {
            return new JsonResponse(
                [
                    'error' => 'Invalid state provided by auth.sch.bme.hu'
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $this->sessionStore->forget(self::SESSION_STATE_KEY);

        try {
            $accessToken = $this->authSchApi->getAccessToken($authorizationCode);
        } catch (Throwable $exception) {
            return new JsonResponse(
                [
                    'error' => $exception->getMessage(),
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $profile = $this->authSchApi->getProfile($accessToken);

        $user = $this->getUser($profile);

        $this->entityManager->flush();

        $this->authManager->guard(config('auth.defaults.guard'))->login($user);

        return redirect()->intended(route('index'));
    }

    public function logout(): RedirectResponse
    {
        $this->authManager->guard(config('auth.defaults.guard'))->logout();

        return new RedirectResponse(route('index'));
    }

    private function buildRedirectUrl(): string
    {
        return sprintf(
            '%s?response_type=code&client_id=%s&state=%s&scope=%s',
            self::AUTH_SCH_REDIRECT_URL,
            config('auth.sch.client_id'),
            $this->generateState(),
            $this->generateScope()
        );
    }

    private function generateState(): string
    {
        $state = Str::random();

        $this->sessionStore->put(self::SESSION_STATE_KEY, $state);

        return $state;
    }

    private function generateScope(): string
    {
        $scopes = [];

        foreach (self::AUTH_SCH_SCOPES as $scope => $enabled) {
            if ($enabled) {
                $scopes[] = $scope;
            }
        }

        return implode('+', $scopes);
    }

    private function getUser(ProfileInterface $profile): UserInterface
    {
        $user = $this->userRepository->findOneByEmail($profile->getEmailAddress());

        if ($user === null) {
            $user = $this->userFactory->createFromAuthSchProfile($profile);
        }

        if ($user->getAuthSchInternalId() === null) {
            $user->setAuthSchInternalId($profile->getInternalId());
        }

        $this->entityManager->persist($user);

        return $user;
    }
}
