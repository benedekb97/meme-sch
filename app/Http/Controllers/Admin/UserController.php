<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

class UserController extends AdminController
{
    public function index()
    {
        $this->load();

        return view('pages.admin.users.index');
    }
}
