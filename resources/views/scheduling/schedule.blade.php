<!-- resources/views/scheduling/schedule.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento - Coren-RJ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0 auto;
            padding: 20px;
            max-width: 800px;
            background-color: #fff;
            color: #000;
        }
        .header, .footer {
            text-align: center;
        }
        .header img {
            width: 100px;
        }
        .header h1 {
            font-size: 24px;
            color: #4d4dff;
            margin: 10px 0;
        }
        .header h2 {
            font-size: 18px;
            color: #000;
            margin: 5px 0;
        }
        .header h3 {
            font-size: 48px;
            color: #000;
            margin: 5px 0;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .details {
            margin: 20px 0;
            font-size: 14px;
        }
        .details div {
            margin-bottom: 10px;
        }
        .details div span {
            display: inline-block;
            width: 50%;
        }
        .appointment-code {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #555;
        }
        .footer a {
            color: #000;
            text-decoration: none;
        }
        .footer p {
            margin: 5px 0;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
    <!-- 
    <logo>
    -->
        <h1>Bem-vindo ao Agendamento do Coren-RJ</h1>
        <h2>SENHA DE ATENDIMENTO</h2>
        <h3> 64</h3>

    </div>
    <div class="details">
        <div><span><strong>Nome:</strong></span> <span>{{ $schedule->user_name }}</span></div>
        <div><span><strong>CPF:</strong></span> <span>{{ $schedule->user_cpf }}</span></div>
        <div><span><strong>Inscrição:</strong></span> <span>{{ $schedule->user_insc }}</span></div>
        <div><span><strong>Serviço:</strong></span> <span>{{ $schedule->service_type }}</span></div>
        <div><span><strong>Local:</strong></span> <span>{{ $location->name }}</span></div>
        <div><span><strong>Data:</strong></span> <span>{{ $slot->date }}</span></div>
        <div><span><strong>Hora:</strong></span> <span>{{ $slot->time }}</span></div>
        <div><span><strong>Senha de Cancelamento: </strong></span><div><span>{{ strtoupper(substr(md5($schedule->confirmation_token), 0, 4)) }}</span></div>

    </div>
    <div class="footer">
        <p>Confira os documentos necessários para o atendimento dos serviços selecionados acessando o site <a href="https://www.coren-rj.org.br">Coren-RJ</a>.</p>
        <p>Senha emitida às {{ date('H:i', strtotime($schedule->created_at)) }} de {{ date('d/m/Y', strtotime($schedule->created_at)) }}</p>
    </div>
    <div class="print-button">
        <button onclick="window.print()">Imprimir</button>
    </div>
</body>
</html>
