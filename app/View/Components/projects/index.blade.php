<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proyectos</h2>
            <a href="{{ route('projects.create') }}">
                <x-primary-button type="button">Nuevo proyecto</x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Propietario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha límite</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($projects as $project)
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('projects.show', $project) }}"
                                       class="text-indigo-600 hover:underline">
                                        {{ $project->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $project->owner->name }}</td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $project->due_date?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $project->status }}</td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="{{ route('projects.show', $project) }}"
                                       class="text-gray-600 hover:underline">Ver</a>

                                    @if ($project->owner_id === auth()->id())
                                        <a href="{{ route('projects.edit', $project) }}"
                                           class="text-indigo-600 hover:underline">Editar</a>

                                        <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                              class="inline"
                                              onsubmit="return confirm('¿Eliminar este proyecto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Todavía no hay proyectos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
</x-app-layout>