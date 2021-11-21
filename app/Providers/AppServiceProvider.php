<?php

namespace App\Providers;

use App\Entities\Post;
use App\Services\Repository\PostRepositoryInterface;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private PostRepositoryInterface $postRepository;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->postRepository = $app['em']->getRepository(Post::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $deletedPosts = $this->postRepository->countDeleted();
        $unapprovedPosts = $this->postRepository->countUnapproved();

        View::share('deletedPostCount', $deletedPosts);
        View::share('unapprovedPostCount', $unapprovedPosts);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
