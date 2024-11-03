<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'posts' => Inertia::defer(function () {
                sleep(2);

                return ['post 1', 'post 2'];
            }),
        ]);
    }
}
