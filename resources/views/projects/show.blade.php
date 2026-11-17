@php($isOwner = auth()->id() === $project->owner_id)

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->title }}</h2>

            @if ($isOwner)
                <div class="flex items-center gap-3">
                    <a href="{{ route('projects.edit', $project) }}">
                        <x-secondary-button type="button">Editar</x-secondary-button>
                    </a>

                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar este proyecto?')">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>Eliminar</x-danger-button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Datos del proyecto --}}
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Propietario</dt>
                        <dd class="text-gray-900">{{ $project->owner->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Estado</dt>
                        <dd class="text-gray-900">{{ $project->status }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Fecha límite</dt>
                        <dd class="text-gray-900">{{ $project->due_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm text-gray-500">Descripción</dt>
                        <dd class="text-gray-900 whitespace-pre-line">{{ $project->description ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Miembros --}}
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Miembros</h3>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Horas</th>
                            @if ($isOwner)
                                <th class="px-4 py-2"></th>
                                <th class="px-4 py-2"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($project->members as $member)
                            <tr>
                                <td class="px-4 py-2">{{ $member->name }}</td>

                                @if ($isOwner)
                                    <td class="px-4 py-2">
                                        <select name="role" form="update-{{ $member->id }}"
                                            class="border-gray-300 rounded-md shadow-sm text-sm">
                                            @foreach (\App\Models\Project::ROLES as $role)
                                                <option value="{{ $role }}"
                                                    @selected($member->pivot->role === $role)>
                                                    {{ $role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" name="contribution_hours" min="0"
                                            form="update-{{ $member->id }}"
                                            value="{{ $member->pivot->contribution_hours }}"
                                            class="w-24 border-gray-300 rounded-md shadow-sm text-sm">
                                    </td>
                                    <td class="px-4 py-2">
                                        <button type="submit" form="update-{{ $member->id }}"
                                            class="text-indigo-600 hover:underline text-sm">Guardar</button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <button type="submit" form="delete-{{ $member->id }}"
                                            class="text-red-600 hover:underline text-sm">Quitar</button>
                                    </td>
                                @else
                                    <td class="px-4 py-2">{{ $member->pivot->role }}</td>
                                    <td class="px-4 py-2">{{ $member->pivot->contribution_hours }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    Este proyecto aún no tiene miembros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Formularios de edición y borrado, fuera de la tabla (se enlazan con form="...") --}}
                @if ($isOwner)
                    @foreach ($project->members as $member)
                        <form id="update-{{ $member->id }}" method="POST"
                              action="{{ route('projects.members.update', [$project, $member]) }}">
                            @csrf
                            @method('PUT')
                        </form>

                        <form id="delete-{{ $member->id }}" method="POST"
                              action="{{ route('projects.members.destroy', [$project, $member]) }}"
                              onsubmit="return confirm('¿Quitar a este miembro?')">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                @endif

                {{-- Formulario para asignar --}}
                @if ($isOwner && $availableUsers->isNotEmpty())
                    <form method="POST" action="{{ route('projects.members.store', $project) }}"
                          class="mt-6 flex flex-wrap items-end gap-4 border-t pt-6">
                        @csrf

                        <div>
                            <x-input-label for="user_id" value="Usuario" />
                            <select id="user_id" name="user_id" required
                                class="mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Selecciona...</option>
                                @foreach ($availableUsers as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="role" value="Rol" />
                            <select id="role" name="role" required
                                class="mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                                @foreach (\App\Models\Project::ROLES as $role)
                                    <option value="{{ $role }}" @selected(old('role') === $role)>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="contribution_hours" value="Horas" />
                            <x-text-input id="contribution_hours" name="contribution_hours" type="number"
                                min="0" class="mt-1 w-24" :value="old('contribution_hours', 0)" />
                        </div>

                        <x-primary-button>Asignar</x-primary-button>
                    </form>
                @endif
            </div>

            <a href="{{ route('projects.index') }}" class="text-gray-600 hover:underline">
                ← Volver al listado
            </a>
        </div>
    </div>
</x-app-layout>