<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users-index', [
            'users' => User::orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.users-form');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->safe()->only(['name', 'email', 'role', 'password']));

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users-form', ['editedUser' => $user]);
    }

    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        if ($user->is(auth()->user()) && $request->input('role') !== User::ROLE_ADMIN) {
            return back()->withErrors(['role' => 'Anda tidak dapat mencabut hak admin akun sendiri.']);
        }

        $user->update([
            ...$request->safe()->only(['name', 'email', 'role']),
            ...($request->filled('password') ? ['password' => $request->input('password')] : []),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun sendiri.']);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
