<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Inertia\Inertia;

class DashboardController
{
    public function index(): Response
    {

        return Inertia::render('Home/Pages/IndexPage');
    }

    public function masterProduct(): Response
    {
        return Inertia::render('MastersProducts/Pages/IndexPage');
    }
}
