@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Deposit
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('account.deposit') }}">
                    @csrf

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" class="form-control" name="amount" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Deposit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
