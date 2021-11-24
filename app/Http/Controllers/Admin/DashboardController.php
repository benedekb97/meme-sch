<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\View\Factory;

class DashboardController extends AdminController
{
    public function index()
    {
        $this->load();

        return view('pages.admin.index');
    }

    public function posts()
    {
        $this->load();

        return view('pages.admin.posts');
    }

    public function approvals()
    {
        $this->load();

        if (isset($this->adminGroups)) {
            $posts = $this->postRepository->findAllUnapprovedForGroups($this->adminGroups);
        } else {
            $posts = $this->postRepository->findAllUnapproved();
        }

        return view(
            'pages.admin.approvals',
            [
                'posts' => $posts,
            ]
        );
    }

    public function refusedPosts()
    {
        $this->load();

        if (isset($this->adminGroups)) {
            $posts = $this->postRepository->findAllRefusedForGroups($this->adminGroups);
        } else {
            $posts = $this->postRepository->findAllRefused();
        }

        return view(
            'pages.admin.refused-posts',
            [
                'posts' => $posts,
            ]
        );
    }
}
