<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function settings()
    {
        return view('pages.profile.settings');
    }
}
