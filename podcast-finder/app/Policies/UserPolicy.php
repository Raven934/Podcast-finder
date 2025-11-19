<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Seul un admin peut voir la liste complète.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Un admin peut voir n’importe quel utilisateur,
     * un utilisateur ne peut voir que son propre profil.
     */
    public function view(User $user, User $model): bool
    {
        return $user->role === 'admin' || $user->id === $model->id;
    }

    /**
     * Seul un admin peut créer un utilisateur.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Un admin peut modifier n’importe qui,
     * un utilisateur ne peut modifier que lui-même.
     */
    public function update(User $user, User $model): bool
    {
        return $user->role === 'admin' || $user->id === $model->id;
    }

    /**
     * Seul un admin peut supprimer un utilisateur.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}