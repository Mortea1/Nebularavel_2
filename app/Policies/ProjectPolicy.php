<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    // Admin voit tout, toujours
    private function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    // Est membre du projet
    private function isMember(User $user, Project $project): bool
    {
        return $project->users->contains($user->id);
    }

    public function viewAny(User $user): bool
    {
        return true; // La liste est filtrée dans le controller
    }

    public function view(User $user, Project $project): bool
    {
        return $this->isAdmin($user) || $this->isMember($user, $project);
    }

    public function create(User $user): bool
    {
        return true; // Tout le monde peut créer, l'admin assigne ensuite
    }

    public function update(User $user, Project $project): bool
    {
        return $this->isAdmin($user) || $this->isMember($user, $project);
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->isAdmin($user);
    }
}
