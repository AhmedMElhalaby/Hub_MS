<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class TenantRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.tenant.register');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'unique:tenants,domain', 'regex:/^[a-z0-9-]+$/'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', Password::defaults()],
        ], [
            'domain.regex' => 'The domain may only contain lowercase letters, numbers, and hyphens.'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the tenant
            $tenant = Tenant::create([
                'name' => $request->name,
                'domain' => $request->domain,
                'active' => true,
            ]);

            // Create initial settings for the tenant
            $tenant->settings()->createMany([
                [
                    'key' => 'app_name',
                    'value' => $request->name,
                    'group' => 'general'
                ],
                [
                    'key' => 'tenant_domain',
                    'value' => $request->domain . '.wtrhub.com',
                    'group' => 'general'
                ]
            ]);

            // Create the admin user
            $user = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'tenant_id' => $tenant->id,
            ]);

            DB::commit();

            auth()->login($user);

            // Modify this line to use the tenant's domain
            return redirect()->away('http://' . $request->domain . '.'.config('app.url').'/dashboard')
                ->with('success', 'Workspace created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
}
