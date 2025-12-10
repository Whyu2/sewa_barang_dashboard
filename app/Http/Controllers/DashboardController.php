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

    public function masterCategory(): Response
    {
        return Inertia::render('MastersCategories/Pages/IndexPage');
    }

    public function masterRegion(): Response
    {
        return Inertia::render('MastersRegions/Pages/IndexPage');
    }
}
