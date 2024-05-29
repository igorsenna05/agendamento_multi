<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Excluir Espaços de Agendamento em Lote') }}
        </h2>
    </x-slot>
    <form id="bulkDeleteForm" action="{{ route('schedule_slots.bulkDestroy') }}" method="POST">
        @csrf
        @method('DELETE')
    <div class="py-12">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Excluir Espaços de Agendamento em Lote') }}</div>

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


                                <!-- Seleção de Datas -->
                                <div class="mb-3">
                                    <label for="date_selection" class="form-label">{{ __('Selecionar Datas') }}</label>
                                    <div class="d-flex gap-3">
                                        <div>
                                            <label for="specific_days" class="form-label">{{ __('Dias Específicos') }}</label>
                                            <input type="text" id="specific_days" class="form-control datepicker hidden" placeholder="Selecione os dias específicos" value="{{ old('specific_days') }}">
                                            <input type="hidden" name="specific_days" id="specific_days_hidden" value="{{ old('specific_days') }}">
                                        </div>
                                        <div>
                                            <label for="date_range" class="form-label">{{ __('Intervalo de Datas') }}</label>
                                            <input type="text" id="date_range" class="form-control daterangepicker hidden" placeholder="Selecione o intervalo de datas" value="{{ old('date_range') }}">
                                            <input type="hidden" name="date_range" id="date_range_hidden" value="{{ old('date_range') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Intervalo de Tempo -->
                                <div class="mb-3">
                                    <label for="time_slots" class="form-label">{{ __('Intervalo de Tempo') }}</label>
                                    <div class="d-flex gap-3">
                                        <div>
                                            <label for="start_time" class="form-label">{{ __('Hora de Início') }}</label>
                                            <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
                                        </div>
                                        <div>
                                            <label for="end_time" class="form-label">{{ __('Hora de Término') }}</label>
                                            <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Local -->
                                <div class="mb-3">
                                    <label for="locations" class="form-label">{{ __('Local') }}</label>
                                    <select name="locations[]" id="locations" class="form-control" multiple required>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ (collect(old('locations'))->contains($location->id)) ? 'selected' : '' }}>{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                        {{ __('Excluir') }}
                                    </button>
                                    <a href="{{ route('schedule_slots.index') }}" class="btn btn-link">
                                        {{ __('Cancelar') }}
                                    </a>
                                </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">{{ __('Confirmar Exclusão') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('Você tem certeza que deseja excluir os horários selecionados?') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteButton">{{ __('Excluir') }}</button>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!-- Inclusão de scripts necessários -->
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Flatpickr para dias específicos
            flatpickr('.datepicker', {
                mode: 'multiple',
                dateFormat: 'Y-m-d',
                locale: 'pt',
                inline: true,
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('specific_days_hidden').value = dateStr;
                }
            });

            // Inicializar Flatpickr para intervalo de datas
            flatpickr('.daterangepicker', {
                mode: 'range',
                dateFormat: 'Y-m-d',
                locale: 'pt',
                inline: true,
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('date_range_hidden').value = dateStr;
                }
            });

        });
    </script>
</x-app-layout>
