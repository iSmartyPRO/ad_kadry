<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Request for New User</title>
    <style>
        .tbl {
            border:1px solid #004080;
            border-collapse:collapse;
            padding:5px;
        }
        .tbl th {
            border:1px solid #004080;
            padding:5px;
            background:#004080;
            color: #fff;
            width: 200px;
            text-align: left;
        }
        .tbl td {
            width: 300px;
            border:1px solid #004080;
            padding:5px;
        }
        .note {
            color: #808080;
            font-style: italic;
            font-size: 14;
        }
    </style>
</head>
<body>
    <p>Уведомление,</p>
    <p>{{ $data->from }} инициировал запрос на создание пользователя.</p>
    <p>Дата запроса: {{ $data->created_at }}</p>

    <p>Данные для нового пользователя:</p>
    <table class="tbl">
        <tr>
            <th>Фамилия (рус.)</th>
            <td>{{ $data->last_name_rus }}</td>
        </tr>
        <tr>
            <th>Ильяс (рус.)</th>
            <td>{{ $data->first_name_rus }}</td>
        </tr>
        <tr>
            <th>Фамилия (анг.)</th>
            <td>{{ $data->last_name_eng }}</td>
        </tr>
        <tr>
            <th>Имя (анг.)</th>
            <td>{{ $data->first_name_eng }}</td>
        </tr>
        <tr>
            <th>Расположение</th>
            <td>{{ $data->location_name }}</td>
        </tr>
        <tr>
            <th>Отдел</th>
            <td>{{ $data->department }}</td>
        </tr>
        <tr>
            <th>Должность</th>
            <td>{{ $data->position }}</td>
        </tr>
    </table>

    <span class="note">Письмо было сгенерировано автоматизированной системой телеграм ботом</span>
</body>
</html>