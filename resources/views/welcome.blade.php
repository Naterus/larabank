<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Larabank - Login Account</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{!! asset("assets/css/app.css") !!}"></style>

</head>
<body>
<div class="login-form">
    <form action="{{ route('login.attempt') }}" method="post">
        <div class="avatar"><i class="material-icons">&#xe84f;</i></div>
        <h1 class="modal-title"><span class="text-danger">LARABANK</span></h1>
        <h3>Login Account</h3>
        @if(Session::has('error'))
            <div class="alert alert-danger text-center">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>{!! Session::get('error') !!}</strong>
            </div>
        @endif
        @if(Session::has('errors'))
            <div class="alert alert-danger text-center">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>{!! $errors->first()!!}</strong>
            </div>
        @endif
        <input name="_token" value="{{ csrf_token() }}" hidden>
        <div class="form-group">
            <label>Email Address</label>
            <input name="email" type="text" class="form-control"  required="required">
        </div>
        <div class="form-group">
            <label>Account Password</label>
            <input name="password" type="password" class="form-control"  required="required">
        </div>

        <input type="submit" class="btn btn-primary btn-block btn-lg" value="Login">
    </form>
    <div class="text-center small">Don't have an account? <a href="{{ route('register') }}">Sign up</a></div>
</div>
</body>
</html>
