<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectMemberController extends Controller
{
    // Asignar un usuario al proyecto
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->ensureOwner($project);

        $data = $request->validate([
            'user_id'            => ['required', 'integer', 'exists:users,id', Rule::notIn([$project->owner_id])],
            'role'               => ['required', Rule::in(Project::ROLES)],
            'contribution_hours' => ['nullable', 'integer', 'min:0'],
        ]);

        $project->members()->syncWithoutDetaching([
            $data['user_id'] => [
                'role'               => $data['role'],
                'contribution_hours' => $data['contribution_hours'] ?? 0,
            ],
        ]);

        return back()->with('status', 'Miembro asignado correctamente.');
    }

    // Cambiar rol u horas de un miembro
    public function update(Request $request, Project $project, User $member): RedirectResponse
    {
        $this->ensureOwner($project);

        $data = $request->validate([
            'role'               => ['required', Rule::in(Project::ROLES)],
            'contribution_hours' => ['required', 'integer', 'min:0'],
        ]);

        $project->members()->updateExistingPivot($member->id, $data);

        return back()->with('status', 'Miembro actualizado correctamente.');
    }

    // Quitar a un miembro
    public function destroy(Project $project, User $member): RedirectResponse
    {
        $this->ensureOwner($project);

        $project->members()->detach($member->id);

        return back()->with('status', 'Miembro eliminado del proyecto.');
    }

    private function ensureOwner(Project $project): void
    {
        abort_unless(auth()->id() === $project->owner_id, 403);
    }
}   