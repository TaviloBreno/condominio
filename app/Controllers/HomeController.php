<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Home'
        ];

        return view('Home/index', $data);
    }
}
