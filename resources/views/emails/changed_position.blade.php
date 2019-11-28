<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Changed Position</title>
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
    <p>{{$data->who }} изменил название должности, через Телеграм бот!</p>
    <table class="tbl">
        <tr>
            <th>Имя: </th>
            <td>{{ $data->nameRus }}</td>
        </tr>
        <tr>
            <th>Login/Email:</th>
            <td>{{ $data->email }}</td>
        </tr>
        <tr>
            <th>Старое значение:</th>
            <td>{{ $data->position_old }}</td>
        </tr>
        <tr>
            <th>Новое значение:</th>
            <td>{{ $data->position_new }}</td>
        </tr>
    </table>

    <span class="note">Письмо было сгенерировано автоматизированной системой телеграм ботом</span>
</body>
</html>