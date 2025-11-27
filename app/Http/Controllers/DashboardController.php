<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Inertia\Inertia;

class DashboardController
{
    public function index(): Response
    {
        $pageTitle = "Parameters List";
        return Inertia::render('Dashboard/Pages/IndexPage', compact('pageTitle'));
    }
}
