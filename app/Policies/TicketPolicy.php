<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    private function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    // Un user peut voir/toucher un ticket s'il est membre du projet lié
    private function isMemberOfTicketProject(User $user, Ticket $ticket): bool
    {
        // Ticket sans projet → admin seulement
        if (!$ticket->project_id) {
            return false;
        }

        return $ticket->project
            ->users
            ->contains($user->id);
    }

    public function viewAny(User $user): bool
    {
        return true; // Filtrée dans le controller
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $this->isAdmin($user) || $this->isMemberOfTicketProject($user, $ticket);
    }

    public function create(User $user): bool
    {
        return true; // Vérification du projet faite dans le controller
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $this->isAdmin($user) || $this->isMemberOfTicketProject($user, $ticket);
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $this->isAdmin($user) || $this->isMemberOfTicketProject($user, $ticket);
    }

    public function addTime(User $user, Ticket $ticket): bool
    {
        return $this->isAdmin($user) || $this->isMemberOfTicketProject($user, $ticket);
    }

    public function addValidation(User $user, Ticket $ticket): bool
    {
        return $this->isAdmin($user) || $this->isMemberOfTicketProject($user, $ticket);
    }
}
