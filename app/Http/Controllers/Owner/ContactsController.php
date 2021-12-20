<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Inertia\Inertia;

class ContactsController extends Controller
{
    public function index()
    {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:name,city']
        ]);

        $query = Contact::query();

        if (request('search')) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        }

        return Inertia::render('Contacts', [
            'contacts' => $query->paginate()->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction'])
        ]);
    }
}