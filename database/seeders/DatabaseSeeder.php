<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Ticket;
use App\Models\Tp;
use App\Models\Validation;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Users ── status 'approved' pour tous les comptes de test ──
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name'  => 'Nebula',
            'name'       => 'Admin Nebula',
            'email'      => 'admin@nebula.test',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'status'     => 'approved',
            'department' => 'Direction',
        ]);

        $dev = User::create([
            'first_name' => 'Jean',
            'last_name'  => 'Dupont',
            'name'       => 'Jean Dupont',
            'email'      => 'jean@nebula.test',
            'password'   => Hash::make('password'),
            'role'       => 'developer',
            'status'     => 'approved',
            'department' => 'Développement',
        ]);

        $manager = User::create([
            'first_name' => 'Sophie',
            'last_name'  => 'Martin',
            'name'       => 'Sophie Martin',
            'email'      => 'sophie@nebula.test',
            'password'   => Hash::make('password'),
            'role'       => 'manager',
            'status'     => 'approved',
            'department' => 'Gestion de projet',
        ]);

        $client = User::create([
            'first_name' => 'Marc',
            'last_name'  => 'Client',
            'name'       => 'Marc Client',
            'email'      => 'client@nebula.test',
            'password'   => Hash::make('password'),
            'role'       => 'client',
            'status'     => 'approved',
            'department' => 'Acme Corp',
        ]);

        // Compte en attente pour tester la validation
        User::create([
            'first_name' => 'Alice',
            'last_name'  => 'Pending',
            'name'       => 'Alice Pending',
            'email'      => 'alice@nebula.test',
            'password'   => Hash::make('password'),
            'role'       => 'pending',
            'status'     => 'pending',
        ]);

        // ─── Projects ──────────────────────────────────────────────
        $project1 = Project::create([
            'name'     => 'Refonte Site Web',
            'client'   => 'Acme Corp',
            'status'   => 'active',
            'progress' => 65,
            'deadline' => '2025-06-30',
            'team'     => 'Équipe A',
        ]);

        $project2 = Project::create([
            'name'     => 'Application Mobile',
            'client'   => 'StartUp XYZ',
            'status'   => 'active',
            'progress' => 30,
            'deadline' => '2025-09-15',
            'team'     => 'Équipe B',
        ]);

        // Membres
        $project1->users()->attach([$admin->id, $dev->id, $client->id]);
        $project2->users()->attach([$dev->id, $manager->id]);

        // Contrats
        Contract::create([
            'project_id'     => $project1->id,
            'hours_included' => 200,
            'hours_used'     => 80,
            'hourly_rate'    => 95.00,
            'start_date'     => '2025-01-01',
            'end_date'       => '2025-06-30',
        ]);

        // Tickets
        $ticket1 = Ticket::create([
            'title'       => 'Correction bug formulaire de contact',
            'project_id'  => $project1->id,
            'priority'    => 'high',
            'status'      => 'open',
            'assigned_to' => $dev->id,
            'creator'     => $admin->name,
            'type'        => 'bug',
            'category'    => 'frontend',
            'description' => 'Le formulaire de contact ne s\'envoie pas correctement sur mobile.',
            'in_contract' => 1,
            'time_spent'  => 120,
        ]);

        Tp::create([
            'ticket_id' => $ticket1->id,
            'user_id'   => $dev->id,
            'time'      => 120,
            'comment'   => 'Analyse + première correction.',
        ]);

        Validation::create([
            'ticket_id' => $ticket1->id,
            'user_id'   => $manager->id,
            'decision'  => 'pending',
            'comment'   => 'En attente de test sur staging.',
        ]);
    }
}
