<!-- resources/views/transactions/index.blade.php -->

<h1>Transaction History</h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at }}</td>
                <td>{{ ucfirst($transaction->type) }}</td>
                <td>{{ $transaction->amount }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
