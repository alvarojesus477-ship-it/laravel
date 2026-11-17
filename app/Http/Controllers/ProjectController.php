<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Proyectos que he creado o en los que participo
        $projects = Project::with('owner')
            ->where('owner_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->whereKey($user->id))
            ->latest()
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $request->user()->ownedProjects()->create($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Proyecto creado correctamente.');
    }

    public function show(Project $project): View
    {
        $project->load('owner', 'members');

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        abort_unless(auth()->id() === $project->owner_id, 403);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        abort_unless(auth()->id() === $project->owner_id, 403);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('status', 'Proyecto eliminado correctamente.');
    }
}