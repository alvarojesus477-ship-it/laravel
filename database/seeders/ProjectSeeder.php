<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $roles = ['Desarrollador', 'Diseñador', 'Tester'];

        Project::factory(5)
            ->recycle($users) // el owner se elige entre los usuarios existentes
            ->create()
            ->each(function (Project $project) use ($users, $roles) {
                $members = $users->random(rand(2, 5));

                foreach ($members as $member) {
                    $project->members()->attach($member->id, [
                        'role'               => $roles[array_rand($roles)],
                        'contribution_hours' => rand(0, 120),
                    ]);
                }
            });
    }
}