@extends("layout.account-layout")
@section("page-title","Buy Airtime")
@section("page-content")
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="panel panel-default text-center">
                <div class="panel-heading">Buy Airtime</div>
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
                    <form action="{{  route("account.airtime.purchase") }}" method="post">
                        <input name="_token" value="{{ csrf_token() }}" hidden>
                        <div class="form-group {!! $errors->has('account_type') ? 'has-error' : '' !!}">
                            <label>Beneficiary</label>
                            <select name="beneficiary" id="beneficiary" class="form-control" required>
                                <option value="self">Self ({{ Auth::user()->account->phone_number }})</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div class="form-group" style="display: none" id="phone_option">
                            <label>Phone Number</label>
                            <input name="phone_number" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Airtime Amount (&#8358;)</label>
                            <input name="amount" type="number" class="form-control" value="0" required>
                        </div>

                        <input type="submit" class="btn btn-primary btn-block btn-lg" value="Confirm Purchase">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("page-script")
    <script>
        $(document).on('change','#beneficiary', function (){
            let beneficiary = $('select[name = "beneficiary"]').val();

            if(beneficiary == "others"){

                $('#phone_option').show();
            }
            else {

                $('#phone_option').hide();
            }
        });

    </script>
@endsection
