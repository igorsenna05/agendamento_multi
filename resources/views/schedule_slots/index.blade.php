<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Controle de Agendamento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <a href="{{ route('schedule_slots.bulkCreate') }}" class="btn btn-primary mb-4">{{ __('Adicionar Horários') }}</a>
                    <a href="{{ route('schedule_slots.bulkDestroyPrep') }}" class="btn btn-danger mb-4">{{ __('Excluir Horários') }}</a>
                    <form id="filter-form" method="GET" action="{{ route('schedule_slots.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-auto">
                                <select name="location_id" class="form-select" onchange="document.getElementById('filter-form').submit();">
                                    <option value="">{{ __('Todas as Localizações') }}</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}">
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">{{ __('Filtrar') }}</button>
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between mb-4">
                        <a href="{{ route('schedule_slots.index', ['start_date' => $startDate->copy()->subWeek()->toDateString(), 'location_id' => request('location_id')]) }}" class="btn btn-primary">Semana Anterior</a>
                        <h3>Semana de {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}</h3>
                        <a href="{{ route('schedule_slots.index', ['start_date' => $startDate->copy()->addWeek()->toDateString(), 'location_id' => request('location_id')]) }}" class="btn btn-primary">Próxima Semana</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered border-secondary border-opacity-10">
                            <thead class="table-light">
                                <tr>
                                    @for ($i = 0; $i < 7; $i++)
                                        @php
                                            $date = $startDate->copy()->addDays($i);
                                            $dayName = $date->locale('pt_BR')->dayName; // Obtém o nome do dia da semana
                                            $isWeekend = $date->isWeekend(); // Verifica se é sábado ou domingo
                                        @endphp
                                        <th class="text-center {{ $isWeekend ? 'bg-body-secondary' : 'bg-primary-subtle' }}">
                                            {{ $date->format('d/m/Y') }} <br> <small>{{ $dayName }}</small>
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @for ($i = 0; $i < 7; $i++)
                                        @php
                                            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
                                            $slots = $scheduleSlots->get($date) ? $scheduleSlots->get($date) : collect();
                                            $groupedSlots = $slots->groupBy(function ($item) {
                                                return substr($item->time, 0, 5);
                                            });
                                            $agendamentosDoDia = $agendamentos->where ('slot.date', $date);
                                            
                                        @endphp
                                        <td class="text-center align-top {{ \Carbon\Carbon::parse($date)->isWeekend() ? 'bg-body-secondary' : '' }}">
                                            @if ($groupedSlots->isNotEmpty())
                                                @foreach ($groupedSlots as $time => $group)
                                                    @php
                                                        $available = $group->first()->available_date ? \Carbon\Carbon::parse($group->first()->available_date)->isPast() : false;
                                                        $availableDate = $group->first()->available_date ? \Carbon\Carbon::parse($group->first()->available_date)->format('d/m/Y H:i') : '';
                                                        $totalScheduled = $agendamentosDoDia->where('slot.time', $time.":00")->count();
                                                    @endphp
                                                    <div class="{{ $available ? 'bg-success' : 'bg-warning' }} bg-opacity-25" data-bs-toggle="tooltip" title="{{ $available ? 'Disponível desde ' . $availableDate : 'Disponível em ' . $availableDate }}">
                                                        {{ $time }} ({{ $totalScheduled }}/{{ $group->count() }})
                                                    </div>
                                                @endforeach
                                            @else
                                                <div>Sem <br/> horários </div>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                        <i class="bi bi-square-fill text-success text-opacity-25"></i> Horário disponibilizado <span class="text-secondary text-opacity-25">| </span> <i class="bi bi-square-fill text-warning  text-opacity-25"></i> Horário a ser disponibilizado
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Tooltip Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</x-app-layout>
