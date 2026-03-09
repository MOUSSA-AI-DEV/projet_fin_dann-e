<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom'               => ['required', 'string', 'max:100'],
            'prenom'            => ['required', 'string', 'max:100'],
            'email'             => ['required', 'string', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'telephone'         => ['nullable', 'string', 'max:20'],
            'role'              => ['required', 'in:user,admin,garage'],
            'adresse_livraison' => ['nullable', 'string', 'max:255'],
            'code_postal'       => ['nullable', 'string', 'max:10'],
            'ville'             => ['nullable', 'string', 'max:100'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Toggle the active status of the user (Ban/Unban).
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous bannir vous-même.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusMessage = $user->is_active ? 'Utilisateur réactivé.' : 'Utilisateur banni.';
        return back()->with('success', $statusMessage);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
