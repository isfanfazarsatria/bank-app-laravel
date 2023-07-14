@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}

                        <div class="mt-4">
                            <a href="{{ route('transactions.deposit') }}" class="btn btn-primary mr-2">Deposit</a>
                            <a href="{{ route('transactions.withdraw') }}" class="btn btn-primary mr-2">Withdraw</a>
                            <a href="{{ route('transactions.transfer') }}" class="btn btn-primary">Transfer</a>
                        </div>
                    </div>

                </div>

                <div class="card">
                    <div class="card-header">{{ __('Balance') }}</div>
                    <div class="card-body">
                        <h3>{{ number_format(Auth::user()->balance, 2) }}</h3>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">{{ __('Transaction History') }}</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ ucfirst($transaction->type) }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->status }}</td>
                                        <td>{{ $transaction->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
