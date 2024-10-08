<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Instruções') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('instructions.create') }}" class="btn btn-primary mb-3">{{ __('Adicionar Instrução') }}</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($instructions as $instruction)
                                <tr>
                                    <td>{{ $instruction->id }}</td>
                                    <td>{{ $instruction->title }}</td>
                                    <td>
                                        <a href="{{ route('instructions.edit', $instruction->id) }}" class="btn btn-warning btn-sm">{{ __('Editar') }}</a>
                                        <form action="{{ route('instructions.destroy', $instruction->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">{{ __('Excluir') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
