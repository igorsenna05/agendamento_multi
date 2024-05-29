<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adicionar Espaço de Agendamento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('schedule_slots.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Data') }}:</label>
                            <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="time" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Hora') }}:</label>
                            <input type="time" id="time" name="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="is_available" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Disponível') }}:</label>
                            <select id="is_available" name="is_available" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="1">{{ __('Sim') }}</option>
                                <option value="0">{{ __('Não') }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="location_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Localização') }}:</label>
                            <select id="location_id" name="location_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Salvar') }}
                            </button>
                            <a href="{{ route('schedule_slots.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
