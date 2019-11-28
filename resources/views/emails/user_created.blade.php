<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New User Created</title>
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
    <p>Создан новый пользователь!</p>
    <p>Данные для авторизации пользователя:</p>
    <table class="tbl">
        <tr>
            <th>Логин: </th>
            <td>{{ $data->first_name_eng.".".$data->last_name_eng }}</td>
        </tr>
        <tr>
            <th>Пароль:</th>
            <td>{{ $data->password }}</td>
        </tr>
        <tr>
            <th>Электронная почта:</th>
            <td>{{ $data->first_name_eng.".".$data->last_name_eng.'@'.env("AD_DOMAIN") }}</td>
        </tr>
    </table>

    <span class="note">Письмо было сгенерировано автоматизированной системой телеграм ботом</span>
</body>
</html>