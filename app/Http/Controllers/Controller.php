<?php

namespace App\Http\Controllers;

use App\Entities\UserInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected EntityManagerInterface $entityManager;

    protected Guard $auth;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager
    ) {
        $this->entityManager = $entityManager;
        $this->auth = $authManager->guard(config('auth.defaults.guard'));
    }

    protected function getUser(): ?UserInterface
    {
        /** @var UserInterface $user */
        $user = $this->auth->user();

        $this->entityManager->initializeObject($user);

        return $user;
    }
}
