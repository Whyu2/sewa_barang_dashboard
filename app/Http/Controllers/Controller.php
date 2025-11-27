<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

abstract class Controller
{
    public function index(): Response
    {
        $pageTitle = "Parameters List";
        return Inertia::render('Pages/IndexPage', compact('pageTitle'));
    }
}
