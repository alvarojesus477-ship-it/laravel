<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->title }}</h2>

            @if ($project->owner_id === auth()->id())
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

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Miembros</h3>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Horas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($project->members as $member)
                            <tr>
                                <td class="px-4 py-2">{{ $member->name }}</td>
                                <td class="px-4 py-2">{{ $member->pivot->role }}</td>
                                <td class="px-4 py-2">{{ $member->pivot->contribution_hours }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                    Este proyecto aún no tiene miembros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('projects.index') }}" class="text-gray-600 hover:underline">
                ← Volver al listado
            </a>
        </div>
    </div>
</x-app-layout>