<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        if (in_array(mb_strtolower((string) $user->role), ['admin', 'administrador'], true)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('products.catalog');
    }
}
