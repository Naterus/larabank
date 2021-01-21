@extends("layout.account-layout")
@section("page-title","Account")
@section("page-content")
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="panel panel-default text-center">
                <div class="panel-heading">Account Details</div>
                <div class="panel-body">
                    @if(Session::has('error'))
                        <div class="alert alert-danger text-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>{!! Session::get('error') !!}</strong>
                        </div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success text-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>{!! Session::get('success') !!}</strong>
                        </div>
                    @endif
                    @if(Session::has('errors'))
                        <div class="alert alert-danger text-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>{!! $errors->first()!!}</strong>
                        </div>
                    @endif
                    <form action="{{  route("account.update") }}" method="post">
                        <input name="_token" value="{{ csrf_token() }}" hidden>
                        <div class="form-group">
                            <label> Account Email</label>
                            <input type="text" class="form-control" disabled value="{{ Auth::user()->email }}" required="required">
                        </div>
                        <div class="form-group">
                            <label> Account Number</label>
                            <input  type="text" class="form-control" disabled value="{{ Auth::user()->account->account_number }}" required="required">
                        </div>
                        <div class="form-group">
                            <label> Account Name</label>
                            <input name="account_name" type="text" class="form-control" value="{{ Auth::user()->account->account_name }}" required="required">
                        </div>
                        <div class="form-group {!! $errors->has('account_type') ? 'has-error' : '' !!}">
                            <label>Account Type</label>
                            <select name="account_type" class="form-control" required>
                                <option value="{{ Auth::user()->account->account_type }}">{{ Auth::user()->account->account_type }}</option>
                                <option value="@if(Auth::user()->account->account_type == "Savings") Current @else Savings @endif">@if(Auth::user()->account->account_type == "Savings") Current @else Savings @endif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input name="phone_number" type="text" class="form-control" value="{{ Auth::user()->account->phone_number }}" required>
                        </div>
                        <div class="form-group">
                            <label>Alternate Email</label>
                            <input name="alternate_email" type="text" class="form-control" value="{{ Auth::user()->account->alternate_email }}">
                        </div>

                        <input type="submit" class="btn btn-primary btn-block btn-lg" value="Update">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
