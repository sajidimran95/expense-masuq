<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        return view('admin.staff.index', [
            'staffUsers' => User::query()->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.staff.create', [
            'permissions' => User::permissionLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['is_admin'] = true;
        $validated['permissions'] = $validated['role'] === 'super_admin' ? array_keys(User::permissionLabels()) : ($validated['permissions'] ?? []);

        User::query()->create($validated);

        return redirect()->route('admin.staff.index')->with('status', 'Staff user সফলভাবে তৈরি হয়েছে।');
    }

    public function edit(User $staff): View
    {
        return view('admin.staff.edit', [
            'staff' => $staff,
            'permissions' => User::permissionLabels(),
        ]);
    }

    public function update(Request $request, User $staff): RedirectResponse
    {
        $validated = $this->validatedData($request, $staff);
        $validated['permissions'] = $validated['role'] === 'super_admin' ? array_keys(User::permissionLabels()) : ($validated['permissions'] ?? []);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.index')->with('status', 'Staff user সফলভাবে আপডেট হয়েছে।');
    }

    public function destroy(User $staff): RedirectResponse
    {
        if ($staff->is(auth()->user())) {
            return back()->withErrors(['staff' => 'নিজের account delete করা যাবে না।']);
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('status', 'Staff user delete হয়েছে।');
    }

    private function validatedData(Request $request, ?User $staff = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($staff)],
            'password' => [$staff ? 'nullable' : 'required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(['super_admin', 'staff'])],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [Rule::in(array_keys(User::permissionLabels()))],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'নাম দিন।',
            'email.required' => 'ইমেইল দিন।',
            'email.unique' => 'এই ইমেইল আগে ব্যবহার হয়েছে।',
            'password.required' => 'পাসওয়ার্ড দিন।',
            'password.confirmed' => 'Confirm password মিলেনি।',
        ]) + [
            'is_active' => false,
            'permissions' => [],
        ];
    }
}
