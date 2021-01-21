@extends("layout.account-layout")
@section("page-title","Transfer Funds")
@section("page-content")
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="panel panel-default text-center">
                <div class="panel-heading">Transfer Funds</div>
                <div class="panel-body">
                    <h3 class="text-info">Balance &#8358;{{ number_format(Auth::user()->account->account_balance) }}</h3>
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
                    <form action="{{  route("account.transfer.submit") }}" method="post">
                        <input name="_token" value="{{ csrf_token() }}" hidden>
                        <div class="form-group">
                            <label>Beneficiary Account Number</label>
                            <input name="account_number" type="text" class="form-control" placeholder="71**********" required="required">
                        </div>
                        <div class="form-group">
                            <label>Amount (&#8358;)</label>
                            <input name="amount" type="number" class="form-control"  value="0.00" required="required">
                        </div>

                        <input type="submit" class="btn btn-primary btn-block btn-lg" value="Transfer">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
