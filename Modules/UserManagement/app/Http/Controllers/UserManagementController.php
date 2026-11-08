<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\UserManagement\Http\Requests\UserRequest;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('usermanagement::index', ['users' => User::latest()->paginate(10)]);
    }

    public function create(): View
    {
        return view('usermanagement::form', ['user' => new User()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('usermanagement::form', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        // admin tidak boleh menurunkan role akunnya sendiri
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->withInput()->with('error', 'Anda tidak dapat menurunkan role akun sendiri.');
        }

        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diubah.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // admin tidak boleh menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
