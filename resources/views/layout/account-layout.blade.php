<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Larabank - @yield("page-title")</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{!! asset("assets/css/app.css") !!}"></style>
</head>
<body>
<div class="login-form">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('account.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('account.transfer') }}">Send Money</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('account.airtime') }}">Buy Airtime</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('account') }}">Account</a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled" href="{{ route('account.logout') }}">Logout</a>
        </li>
    </ul>
    @yield("page-content")
</div>
@yield("page-script")
</body>
</html>
