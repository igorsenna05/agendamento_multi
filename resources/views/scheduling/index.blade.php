<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Scheduling</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .container {
            max-width: 800px;
        }
        .dropdown-menu a {
            cursor: pointer;
        }
        .input-group .input-group-text {
            cursor: default;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="text-center mb-4">
            <a href="https://coren-rj.org.br" target="_blank">
                <x-application-logo class="block w-auto fill-current text-gray-800" />
            </a>
        </div>
        <h1 class="mb-4 text-center">Agendamento {{ $node->label }}</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="scheduling-form" action="{{ route('scheduling.store') }}" method="POST" onsubmit="return validateForm()">
            @csrf
            <input type="hidden" name="service_type" value="{{ $node->action_value }}">
            <input type="hidden" name="formatted_insc" id="formatted_insc" value="{{ old('formatted_insc') }}">

            <!-- User Information -->
            <div class="mb-4">
                <h4>Dados Pessoais</h4>
                <div class="mb-3">
                    <label for="user_name" class="form-label">Nome:</label>
                    <input type="text" class="form-control" name="user_name" id="user_name" value="{{ old('user_name') }}" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="user_cpf" class="form-label">CPF:</label>
                        <input type="text" class="form-control" name="user_cpf" id="user_cpf" value="{{ old('user_cpf') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="user_insc" class="form-label">Nº de Inscrição:</label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" required>Categoria</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-category="AE">Auxiliar de Enfermagem</a>
                                <a class="dropdown-item" data-category="TE">Técnico de Enfermagem</a>
                                <a class="dropdown-item" data-category="ENF">Enfermeiro</a>
                                <a class="dropdown-item" data-category="OB">Obstetriz</a>
                            </div>
                            <input type="number" class="form-control" name="user_insc" id="user_insc" value="{{ old('user_insc') }}" required style="text-align: right;">
                            <div class="input-group-append">
                                <span class="input-group-text" id="insc_suffix">{{ old('formatted_insc') ? explode('-', old('formatted_insc'))[1] : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="user_phone" class="form-label">Telefone:</label>
                        <input type="text" class="form-control" name="user_phone" id="user_phone" value="{{ old('user_phone') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="user_email" class="form-label">E-mail de contato:</label>
                        <input type="email" class="form-control" name="user_email" id="user_email" value="{{ old('user_email') }}" required>
                    </div>
                </div>
            </div>

            <!-- Scheduling Information -->
            <div class="mb-4">
                <h4>Dados do Agendamento</h4>
                <div class="mb-3">
                    <label for="location_id" class="form-label">Local de atendimento:</label>
                    <select class="form-control" name="location_id" id="location_id" required>
                        <option value="">Selecione o local</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="slot_date" class="form-label">Dias disponíveis:</label>
                        <select class="form-control" name="slot_date" id="slot_date" required>
                            <option value="">Selecione o dia</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="slot_id" class="form-label">Horários disponíveis:</label>
                        <select class="form-control" name="slot_id" id="slot_id" required>
                            <option value="">Selecione o horário</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Agendar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load available dates when a location is selected
            $('#location_id').change(function() {
                var location_id = $(this).val();
                if (location_id) {
                    $.ajax({
                        url: '{{ route("scheduling.index") }}',
                        type: 'GET',
                        data: { location_id: location_id },
                        success: function(data) {
                            var dates = data.dates;
                            $('#slot_date').empty().append('<option value="">Selecione o dia</option>');
                            dates.forEach(function(date) {
                                $('#slot_date').append('<option value="' + date.original + '">' + date.formatted + '</option>');
                            });
                        }
                    });
                }
            });

            // Load available time slots when a date is selected
            $('#slot_date').change(function() {
                var location_id = $('#location_id').val();
                var date = $(this).val();
                if (location_id && date) {
                    $.ajax({
                        url: '{{ route("scheduling.index") }}',
                        type: 'GET',
                        data: { location_id: location_id, date: date },
                        success: function(data) {
                            var uniqueSlots = {};
                            $('#slot_id').empty().append('<option value="">Selecione o horário</option>');
                            data.slots.forEach(function(slot) {
                                var time = slot.time;
                                if (!uniqueSlots[time]) {
                                    uniqueSlots[time] = true;
                                    $('#slot_id').append('<option value="' + slot.id + '">' + time + '</option>');
                                }
                            });
                        }
                    });
                }
            });

            // Handle category selection and append to user_insc input
            $('.dropdown-item').click(function() {
                var category = $(this).data('category');
                var currentInsc = $('#user_insc').val().split('-')[0];
                $('#insc_suffix').text(category);
                $('#user_insc').val(currentInsc).focus();
                updateFormattedInsc();
            });

            // Update formatted_insc input on user_insc change
            $('#user_insc').on('input', function() {
                updateFormattedInsc();
            });

            function updateFormattedInsc() {
                var inscNumber = $('#user_insc').val();
                var category = $('#insc_suffix').text();
                $('#formatted_insc').val(inscNumber + '-' + category);
            }

            // Validate form to ensure category is selected
            window.validateForm = function() {
                var category = $('#insc_suffix').text();
                if (!category) {
                    alert('Por favor, selecione uma categoria para o Nº de Inscrição.');
                    return false;
                }
                return true;
            };

            // Restore old values for dynamically loaded options
            if ('{{ old("location_id") }}') {
                $('#location_id').trigger('change');
                setTimeout(function() {
                    $('#slot_date').val('{{ old("slot_date") }}').trigger('change');
                    setTimeout(function() {
                        $('#slot_id').val('{{ old("slot_id") }}');
                    }, 500);
                }, 500);
            }
        });
    </script>
</body>
</html>
