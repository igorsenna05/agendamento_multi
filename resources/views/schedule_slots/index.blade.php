<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Controle de Agendamento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('schedule_slots.create') }}" class="btn btn-secondary mb-4">{{ __('Adicionar Horário de Agendamento') }}</a>
                    <a href="{{ route('schedule_slots.bulkCreate') }}" class="btn btn-primary mb-4">{{ __('Adicionar Horários em Lote') }}</a>
                    <a href="{{ route('schedule_slots.bulkDestroyPrep') }}" class="btn btn-danger mb-4">{{ __('Excluir Horários em Lote') }}</a>
                    <form method="GET" action="{{ route('schedule_slots.index') }}" class="mb-4">
                        <div class="flex space-x-2">
                            <select name="location_id" class="form-select">
                                <option value="">{{ __('Todas as Localizações') }}</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                            <input type="date" name="date" class="form-input" value="{{ request('date') }}">
                            <button type="submit" class="btn btn-primary">{{ __('Filtrar') }}</button>
                        </div>
                    </form>
                    <table class="table-auto w-full mt-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('ID') }}</th>
                                <th class="px-4 py-2">{{ __('Data') }}</th>
                                <th class="px-4 py-2">{{ __('Hora') }}</th>
                                <th class="px-4 py-2">{{ __('Disponível') }}</th>
                                <th class="px-4 py-2">{{ __('Localização') }}</th>
                                <th class="px-4 py-2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scheduleSlots as $slot)
                                <tr>
                                    <td class="border px-4 py-2">{{ $slot->id }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($slot->date)->format('d/m/Y') }}</td>
                                    <td class="border px-4 py-2">{{ $slot->time }}</td>
                                    <td class="border px-4 py-2">{{ $slot->is_available ? 'Sim' : 'Não' }}</td>
                                    <td class="border px-4 py-2">{{ $slot->location->name }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('schedule_slots.edit', $slot->id) }}" class="btn btn-warning">{{ __('Editar') }}</a>
                                        <form action="{{ route('schedule_slots.destroy', $slot->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">{{ __('Excluir') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $scheduleSlots->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
