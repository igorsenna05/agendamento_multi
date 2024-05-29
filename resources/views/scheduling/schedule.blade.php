<!-- resources/views/scheduling/schedule.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 80mm; /* Largura do cupom fiscal */
            margin: 0 auto;
        }
        .content {
            padding: 10px;
        }
        .header, .footer {
            text-align: center;
        }
        .header {
            font-size: 14px;
            font-weight: bold;
        }
        .details {
            margin: 10px 0;
        }
        .details div {
            margin-bottom: 5px;
        }
        .details div span {
            display: inline-block;
            width: 50%;
        }
        .print-button {
            display: none;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            Agendamento Confirmado
        </div>
        <div class="details">
            <div><span>Nome:</span> <span>{{ $schedule->user_name }}</span></div>
            <div><span>CPF:</span> <span>{{ $schedule->user_cpf }}</span></div>
            <div><span>Inscrição:</span> <span>{{ $schedule->user_insc }}</span></div>
            <div><span>Serviço:</span> <span>{{ $schedule->service_type }}</span></div>
            <div><span>Local:</span> <span>{{ $location->name }}</span></div>
            <div><span>Data:</span> <span>{{ $slot->date }}</span></div>
            <div><span>Hora:</span> <span>{{ $slot->time }}</span></div>
            <div><span>Token:</span> <span>{{ $schedule->confirmation_token }}</span></div>
        </div>
        <div class="footer">
            Obrigado por utilizar nossos serviços!
        </div>
    </div>
    <div class="print-button">
        <button onclick="window.print()">Imprimir</button>
    </div>
</body>
</html>
