<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\PostInterface;
use App\Entities\UserInterface;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            /** @var UserInterface $user */
            $user = Auth::user();

            /** @var PostInterface $post */
            foreach ($user->getPosts() as $post) {
                dump('Downvotes: ' . $post->getDownvoteCount());
                dd ('Upvotes: ' .$post->getUpvoteCount());
            }
        }

        return view('pages.index');
    }
}
