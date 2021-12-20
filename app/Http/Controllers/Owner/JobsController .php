<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class JobsController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('CreateJob', [
            'mechanics' => User::where('type', 'mechanic')->when(request('term'), function ($query, $term) {
                $query->where('name', 'like', "%$term%");
            })->limit(15)->get(),
            'consultants' => User::where('type', 'consultant')->when(request('term'), function ($query, $term) {
                $query->where('name', 'like', "%$term%");
            })->limit(15)->get()
        ]);
    }
}