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
        .step-container {
            display: none;
        }
        .step-container.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="shrink-0 flex items-center">
            <a href="https://coren-rj.org.br" target="_blank">
                <center><x-application-logo class="block w-auto fill-current text-gray-800" /></center>
            </a>
        </div>
        <h1 class="mb-4 text-center">Agendamento {{ $node->label }}</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form id="scheduling-form" action="{{ route('scheduling.store') }}" method="POST">
            @csrf
            <input type="hidden" name="service_type" value="{{ $node->action_value }}">

            <!-- Step 1: User Information -->
            <div id="step-1" class="step-container active">
                <div class="form-group">
                    <label for="user_name">Nome:</label>
                    <input type="text" class="form-control" name="user_name" id="user_name" required>
                </div>

                <div class="form-group">
                    <label for="user_cpf">CPF:</label>
                    <input type="text" class="form-control" name="user_cpf" id="user_cpf" required>
                </div>

                <div class="form-group">
                    <label for="user_insc">Nº de Inscrição:</label>
                    <input type="text" class="form-control" name="user_insc" id="user_insc" required>
                </div>
                <button type="button" class="btn btn-secondary"  id="back-to-menu">Voltar ao menu</button>
                <button type="button" class="btn btn-primary" id="next-to-step-2">Próximo</button>
                
            </div>

            <!-- Step 2: Select Location and Date -->
            <div id="step-2" class="step-container">
                <div class="form-group">
                    <label for="location_id">Local de Atendimento:</label>
                    <select class="form-control" name="location_id" id="location_id" required>
                        <option value="">Selecione o Local</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="slot_date">Dias Disponíveis:</label>
                    <select class="form-control" name="slot_date" id="slot_date" required>
                        <option value="">Selecione o Dia</option>
                    </select>
                </div>

                <button type="button" class="btn btn-secondary" id="back-to-step-1">Voltar</button>
                <button type="button" class="btn btn-primary" id="next-to-step-3">Próximo</button>
            </div>

            <!-- Step 3: Select Time Slot -->
            <div id="step-3" class="step-container">
                <div class="form-group">
                    <label for="slot_id">Horários Disponíveis:</label>
                    <select class="form-control" name="slot_id" id="slot_id" required>
                        <option value="">Selecione o Horário</option>
                    </select>
                </div>

                <button type="button" class="btn btn-secondary" id="back-to-step-2">Voltar</button>
                <button type="submit" class="btn btn-success">Agendar</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Navigation between steps
            $('#next-to-step-2').click(function() {
                $('#step-1').removeClass('active');
                $('#step-2').addClass('active');
            });

            $('#back-to-step-1').click(function() {
                $('#step-2').removeClass('active');
                $('#step-1').addClass('active');
            });

            $('#next-to-step-3').click(function() {
                $('#step-2').removeClass('active');
                $('#step-3').addClass('active');
            });

            $('#back-to-step-2').click(function() {
                $('#step-3').removeClass('active');
                $('#step-2').addClass('active');
            });
            $('#back-to-menu').click(function() {
                window.location.href = '/';
            });

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
                            $('#slot_date').empty().append('<option value="">Select Date</option>');
                            dates.forEach(function(date) {
                                $('#slot_date').append('<option value="' + date + '">' + date + '</option>');
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
                            var slots = data.slots;
                            $('#slot_id').empty().append('<option value="">Select Time Slot</option>');
                            slots.forEach(function(slot) {
                                $('#slot_id').append('<option value="' + slot.id + '">' + slot.time + '</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
