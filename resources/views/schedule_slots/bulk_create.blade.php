<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adicionar Espaços de Agendamento em Lote') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Adicionar Espaços de Agendamento em Lote') }}</div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('schedule_slots.bulkStore') }}" method="POST">
                                @csrf

                                <!-- Date Selection -->
                                <div class="mb-3">
                                    <label for="date_selection" class="form-label">{{ __('Selecionar Datas') }}</label>
                                    <div class="d-flex gap-3">
                                        <div>
                                            <label for="specific_days" class="form-label">{{ __('Dias Específicos') }}</label>
                                            <input type="text" id="specific_days" class="form-control datepicker hidden" placeholder="Selecione os dias específicos" value="{{ old('specific_days') }}">
                                            <input type="hidden" name="specific_days" id="specific_days_hidden" value="{{ old('specific_days') }}">
                                        </div>
                                        <div>
                                            <label for="date_range" class="form-label">{{ __('Período') }}</label>
                                            <input type="text" id="date_range" class="form-control daterangepicker hidden" placeholder="Selecione o período" value="{{ old('date_range') }}">
                                            <input type="hidden" name="date_range" id="date_range_hidden" value="{{ old('date_range') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Available Date for Scheduling -->
                                <div class="mb-3">
                                    <label for="available_date" class="form-label">{{ __('Data Disponível para Marcação') }}</label>
                                    <input type="text" id="available_date" name="available_date" class="form-control flatpickr-available-date" placeholder="Selecione a data" value="{{ old('available_date') }}" required>
                                </div>

                                <!-- Location Selection -->
                                <div class="mb-3">
                                    <label for="locations" class="form-label">{{ __('Localizações') }}</label>
                                    <select multiple name="locations[]" id="locations" class="form-control" required>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ in_array($location->id, old('locations', [])) ? 'selected' : '' }}>{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Appointment Duration -->
                                <div class="mb-3">
                                    <label for="appointment_duration" class="form-label">{{ __('Duração de Cada Atendimento (minutos)') }}</label>
                                    <input type="number" id="appointment_duration" name="appointment_duration" class="form-control"  value="{{ old('appointment_duration') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="appointments_per_slot" class="form-label">{{ __('Número de Atendimentos por Período') }}</label>
                                    <input type="number" id="appointments_per_slot" name="appointments_per_slot" class="form-control"  value="{{ old('appointments_per_slot') }}" required>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Salvar') }}
                                    </button>
                                    <a href="{{ route('schedule_slots.index') }}" class="btn btn-dark">
                                        {{ __('Cancelar') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include necessary scripts -->
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr for specific days
            flatpickr('.datepicker', {
                mode: 'multiple',
                dateFormat: 'Y-m-d',
                locale: 'pt',
                inline: true,
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('specific_days_hidden').value = dateStr;
                }
            });

            // Initialize Flatpickr for date range
            flatpickr('.daterangepicker', {
                mode: 'range',
                dateFormat: 'Y-m-d',
                locale: 'pt',
                inline: true,
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('date_range_hidden').value = dateStr;
                }
            });

            // Initialize Flatpickr for available date
            flatpickr('.flatpickr-available-date', {
                dateFormat: 'd-m-Y',
                locale: 'pt',
                inline: false,
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('available_date').value = dateStr;
                }
            });
        });
    </script>
</x-app-layout>
