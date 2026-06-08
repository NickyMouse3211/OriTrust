<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $title = 'Profile';
    private $route = 'profile.';
    private $header = 'Profile';
    private $sub_header = 'Settings';

    public function index()
    {
        $data = [
            'title'      => $this->title,
            'route'      => $this->route,
            'header'     => $this->header,
            'sub_header' => $this->sub_header,
        ];

        return view('profile.index', $data);
    }
}
