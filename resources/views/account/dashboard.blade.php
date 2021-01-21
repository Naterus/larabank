@extends("layout.account-layout")
@section("page-title","Dashboard")
@section("page-content")


    @if(Session::has('success'))
        <div class="row">
            <div class="alert alert-success text-center">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>{!! Session::get('success') !!}</strong>
            </div>
        </div>
    @endif
    <h3>Account Number : <span class="text-info">{{ Auth::user()->account->account_number }}</span> </h3>

    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="panel panel-default text-center">
                <div class="panel-body">
                    <h3>Balance</h3>
                    <h1>&#8358;{{ number_format(Auth::user()->account->account_balance) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="panel panel-default text-center">
                <div class="panel-body">
                    <h3>Transactions</h3>
                    <h1>{{ Auth::user()->account->transactions->count() }}</h1>
                </div>
            </div>
        </div>
    </div>



    <h3>Transactions</h3>

    <div class="row">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php $counter = 1; ?>
            @foreach(Auth::user()->account->transactions as $transaction)
                <tr class="@if($transaction->transaction_type == "Credit") success @else danger @endif">
                    <td>{{ $counter }}</td>
                    <td>{{ $transaction->transaction_type }}</td>
                    <td>&#8358;{{ number_format($transaction->transaction_amount) }}</td>
                    <td>{{ $transaction->transaction_description }}</td>
                    <td>{{ $transaction->created_at->format("d M Y h:i:s A") }}</td>
                </tr>
                <?php $counter++; ?>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
