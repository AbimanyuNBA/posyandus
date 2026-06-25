<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Posyandu};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('posyandu')->where('role', 'kader')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $posyandu = Posyandu::orderBy('nama')->get();
        return view('admin.users.create', compact('posyandu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8|confirmed',
            'posyandu_id' => 'required|exists:posyandu,id',
        ]);

        User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'role'     => 'kader',
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun kader berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $posyandu = Posyandu::orderBy('nama')->get();
        return view('admin.users.edit', compact('user', 'posyandu'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,'.$user->id,
            'posyandu_id' => 'required|exists:posyandu,id',
            'password'    => 'nullable|min:8|confirmed',
        ]);

        $user->update([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'posyandu_id' => $validated['posyandu_id'],
            ...($validated['password']
                ? ['password' => Hash::make($validated['password'])]
                : []),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Data kader berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun kader dihapus.');
    }
}