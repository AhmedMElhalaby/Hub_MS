<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        $tenant = resolve_tenant_from_request();
        if (!$tenant) {
            abort(404);
        }
        return view('auth.login');
    }
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        Session::regenerate();
        return redirect()->intended(route('tenant.home'));
    }
}
