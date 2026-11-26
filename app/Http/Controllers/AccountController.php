<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function loginPost(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $role = mb_strtolower((string) $request->user()->role);
        $destination = in_array($role, ['admin', 'administrador'], true)
            ? route('admin.dashboard', absolute: false)
            : route('products.catalog', absolute: false);

        return redirect()->intended($destination);
    }
}
