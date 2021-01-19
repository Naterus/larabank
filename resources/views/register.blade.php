<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaraBank App - Create Account</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body {
            color: #999;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
        }
        .form-control {
            box-shadow: none;
            border-color: #ddd;
        }
        .form-control:focus {
            border-color: #483d8b;
        }
        .login-form {
            width: 500px;
            margin: 0 auto;
            padding: 30px 0;
        }
        .login-form form {
            color: #434343;
            border-radius: 1px;
            margin-bottom: 15px;
            background: #fff;
            border: 1px solid #f3f3f3;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }
        .login-form h1 {
            text-align: center;
            font-size: 40px;
            margin-bottom: 20px;
        }
        .login-form .avatar {
            color: #fff;
            margin: 0 auto 30px;
            text-align: center;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            z-index: 9;
            background: #483d8b;
            padding: 15px;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
        }
        .login-form .avatar i {
            font-size: 62px;
        }
        .login-form .form-group {
            margin-bottom: 20px;
        }
        .login-form .form-control, .login-form .btn {
            min-height: 40px;
            border-radius: 2px;
            transition: all 0.5s;
        }
        .login-form .close {
            position: absolute;
            top: 15px;
            right: 15px;
        }
        .login-form .btn {
            background: #483d8b;
            border: none;
            line-height: normal;
        }
        .login-form .btn:hover, .login-form .btn:focus {
            background: #483d8b;
        }
        .login-form .checkbox-inline {
            float: left;
        }
        .login-form input[type="checkbox"] {
            margin-top: 2px;
        }
        .login-form .forgot-link {
            float: right;
        }
        .login-form .small {
            font-size: 13px;
        }
        .login-form a {
            color: #483d8b;
        }
    </style>
</head>
<body>
<div class="login-form">
    <form action="{{ route("register.submit") }}" method="post">
        <input name="_token" value="{{ csrf_token() }}" hidden>
        <div class="avatar"><i class="material-icons">&#xe84f;</i></div>
        <h1 class="modal-title">Lara<span class="text-danger">Bank</span></h1>
        <h3>Create Account</h3>
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
        <div class="form-group {!! $errors->has('account_type') ? 'has-error' : '' !!}">
            <label>Account Type</label>
            <select name="account_type" class="form-control">
                <option value="Savings">Savings</option>
                <option value="Current">Current</option>
            </select>
        </div>
        <div class="form-group {!! $errors->has('account_name') ? 'has-error' : '' !!}">
            <label>Account Name</label>
            <input name="account_name" type="text" class="form-control" value="{{ Request::old('account_name') }}" required="required">
        </div>
        <div class="form-group {!! $errors->has('email') ? 'has-error' : '' !!}">
            <label>Email Address</label>
            <input name="email" type="text" class="form-control" value="{{ Request::old('email') }}" required="required">
        </div>
        <div class="form-group {!! $errors->has('phone_number') ? 'has-error' : '' !!}">
            <label>Phone Number</label>
            <input name="phone_number" type="text" class="form-control" value="{{ Request::old('phone_number') }}" required="required">
        </div>
        <div class="form-group {!! $errors->has('password') ? 'has-error' : '' !!}">
            <label>Account Password</label>
            <input name="password" type="password" class="form-control"  required="required">
        </div>
        <div class="form-group {!! $errors->has('password_confirmation') ? 'has-error' : '' !!}">
            <label>Confirm Account Password</label>
            <input name="password_confirmation" type="password" class="form-control" required="required">
        </div>
        <input type="submit" class="btn btn-primary btn-block btn-lg" value="Login">
    </form>
    <div class="text-center small">Don't have an account? <a href="{{ route('home') }}">Sign up</a></div>
</div>
</body>
</html>
