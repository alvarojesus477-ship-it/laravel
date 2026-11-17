<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar proyecto</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('projects._form')

                    <div class="flex items-center gap-4">
                        <x-primary-button>Actualizar</x-primary-button>
                        <a href="{{ route('projects.show', $project) }}" class="text-gray-600 hover:underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>