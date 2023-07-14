@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Account Information
            </div>
            <div class="card-body">
                <h5 class="card-title">Account Number: {{ $account->account_number }}</h5>
                <p class="card-text">Balance: {{ $account->balance }}</p>
                <!-- Add other account information fields as needed -->
            </div>
        </div>
    </div>
@endsection
