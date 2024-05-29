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
                    <div class="flex justify-between mb-4">
                        <a href="{{ route('schedule_slots.index', ['start_date' => $startDate->copy()->subWeek()->toDateString()]) }}" class="btn btn-primary">Semana Anterior</a>
                        <h3>Semana de {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}</h3>
                        <a href="{{ route('schedule_slots.index', ['start_date' => $startDate->copy()->addWeek()->toDateString()]) }}" class="btn btn-primary">Próxima Semana</a>
                    </div>
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                @for ($i = 0; $i < 7; $i++)
                                    <th class="px-4 py-2">{{ $startDate->copy()->addDays($i)->format('d/m/Y') }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (range(8, 18) as $hour)
                                <tr>
                                    @for ($i = 0; $i < 7; $i++)
                                        @php
                                            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
                                            $hourFormatted = sprintf('%02d:00:00', $hour);
                                            $slots = $scheduleSlots->get($date) ? $scheduleSlots->get($date)->where('time', $hourFormatted) : collect();
                                            $availableSlots = $slots->where('is_available', true)->count();
                                            $totalSlots = $slots->count();
                                        @endphp
                                        <td class="border px-4 py-2">
                                            <div>{{ $hourFormatted }}</div>
                                            <div>Disponíveis: {{ $availableSlots }}</div>
                                            <div>Total: {{ $totalSlots }}</div>
                                            @foreach ($slots as $slot)
                                                <div>
                                                    {{ $slot->location->name }} 
                                                    <a href="{{ route('schedule_slots.edit', $slot->id) }}" class="btn btn-warning btn-sm">{{ __('Editar') }}</a>
                                                    <form action="{{ route('schedule_slots.destroy', $slot->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Excluir') }}</button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
