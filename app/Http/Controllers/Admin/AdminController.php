<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Notifications\AdminCredentialsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::all();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Admin::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', 'in:super_admin,manager,job_coach,coordinator'],
        ], [
            'name.required' => 'Name is required.',
            'name.regex' => 'Name can only contain letters, spaces, and hyphens.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'role.required' => 'Role is required.',
        ]);

        $tempPassword = $request->password;

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        try {
            $admin->notify(new AdminCredentialsNotification($tempPassword, $admin->role));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info('Admin email failed. Credentials for ' . $admin->email . ' - Password: ' . $tempPassword);
        }

        return redirect()->route('admin.admins.index')->with('success', 'Administrator created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        // Not used, using edit instead.
        return redirect()->route('admin.admins.edit', $admin);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(Admin::class)->ignore($admin->id)],
            'role' => ['required', 'in:super_admin,manager,job_coach,coordinator'],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ], [
            'name.required' => 'Name is required.',
            'name.regex' => 'Name can only contain letters, spaces, and hyphens.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.confirmed' => 'Passwords do not match.',
            'role.required' => 'Role is required.',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->role = $request->role;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Administrator updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        if (auth('admin')->id() === $admin->id) {
            return redirect()->route('admin.admins.index')->withErrors(['error' => 'You cannot delete yourself.']);
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Administrator deleted successfully.');
    }
}
