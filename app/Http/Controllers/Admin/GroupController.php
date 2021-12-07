<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

class GroupController extends AdminController
{
    public function index()
    {
        $this->load();

        return view('pages.admin.groups.index');
    }
}
