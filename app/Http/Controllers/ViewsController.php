<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewsController extends Controller
{

    public function roles()
    {
        return view('security.role');
    }

    public function permissions()
    {
        return view('security.permission');
    }
}
