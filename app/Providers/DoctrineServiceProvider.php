<?php

declare(strict_types=1);

namespace App\Providers;

use App\Entities\Post;
use App\Entities\User;
use App\Entities\Vote;
use App\Services\Factory\PostFactory;
use App\Services\Factory\UserFactory;
use App\Services\Factory\VoteFactory;
use App\Services\Repository\PostRepository;
use App\Services\Repository\UserRepository;
use App\Services\Repository\VoteRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class DoctrineServiceProvider extends ServiceProvider
{
    private const ENTITY_REPOSITORY_MAP = [
        User::class => UserRepository::class,
        Post::class => PostRepository::class,
        Vote::class => VoteRepository::class,
    ];

    private const ENTITY_FACTORY_MAP = [
        User::class => UserFactory::class,
        Post::class => PostFactory::class,
        Vote::class => VoteFactory::class,
    ];

    public function register(): void
    {
        $this->registerRepositories();
        $this->registerFactories();
    }

    private function registerRepositories(): void
    {
        foreach (self::ENTITY_REPOSITORY_MAP as $entityClass => $repositoryClass) {
            $this->app->bind(
                $repositoryClass,
                function (Application $application) use ($entityClass, $repositoryClass) {
                    return new $repositoryClass(
                        $application['em'],
                        $application['em']->getClassMetaData($entityClass)
                    );
                }
            );
        }

        foreach (self::ENTITY_REPOSITORY_MAP as $entityClass => $repositoryClass) {
            $this->app->bind(
                $repositoryClass . 'Interface',
                function (Application $application) use ($entityClass, $repositoryClass) {
                    return new $repositoryClass(
                        $application['em'],
                        $application['em']->getClassMetaData($entityClass)
                    );
                }
            );
        }
    }

    private function registerFactories(): void
    {
        foreach (self::ENTITY_FACTORY_MAP as $entityClass => $repositoryClass) {
            $this->app->bind(
                $repositoryClass,
                function (Application $application) use ($entityClass, $repositoryClass) {
                    return new $repositoryClass(
                        $application['em'],
                        $application['em']->getClassMetaData($entityClass)
                    );
                }
            );
        }

        foreach (self::ENTITY_FACTORY_MAP as $entityClass => $repositoryClass) {
            $this->app->bind(
                $repositoryClass . 'Interface',
                function (Application $application) use ($entityClass, $repositoryClass) {
                    return new $repositoryClass(
                        $application['em'],
                        $application['em']->getClassMetaData($entityClass)
                    );
                }
            );
        }
    }
}
