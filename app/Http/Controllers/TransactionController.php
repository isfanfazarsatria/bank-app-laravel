<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller
{
    public function showDepositForm()
    {
        return view('transactions.deposit');
    }

    public function deposit(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);
    
        $user = auth()->user();
    
        $amount = $validatedData['amount'];
    
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $amount,
            'status' => ($amount >= 0) ? 'penambahan saldo' : 'pengurangan saldo',
        ]);
    
        $user->balance += $amount;
        $user->save();
    
        return redirect()->route('dashboard')->with('success', 'Deposit berhasil dilakukan.');
    
    }

    public function showWithdrawForm()
    {
        return view('transactions.withdraw');
    }

    public function withdraw(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);
    
        $user = auth()->user();
    
        if ($user->balance < $validatedData['amount']) {
            return redirect()->route('dashboard')->with('error', 'Saldo tidak mencukupi.');
        }
    
        $amount = -$validatedData['amount'];
    
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdraw',
            'amount' => $amount,
            'status' => ($user->balance - $validatedData['amount'] >= 0) ? 'pengurangan saldo' : 'saldo tidak mencukupi',
        ]);
    
        $user->balance += $amount;
        $user->save();
    
        return redirect()->route('dashboard')->with('success', 'Penarikan berhasil dilakukan.');
    
    }

    public function showTransferForm()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('transactions.transfer', compact('users'));
    }

    public function transfer(Request $request)
{
    $validatedData = $request->validate([
        'recipient' => 'required|exists:users,id',
        'amount' => 'required|numeric|min:0',
    ]);

    $user = Auth::user();

    if ($user->balance < $validatedData['amount']) {
        return redirect()->back()->withErrors(['amount' => 'Insufficient balance.']);
    }

    $recipient = User::findOrFail($validatedData['recipient']);

    $user->balance -= $validatedData['amount'];
    $user->save();

    $recipient->balance += $validatedData['amount'];
    $recipient->save();

    $senderStatus = -($validatedData['amount'] >= 0) ? 'Pengurangan saldo' : 'Penambahan saldo';
    $recipientStatus = +($validatedData['amount'] >= 0) ? 'Penambahan saldo' : 'Pengurangan saldo';

    Transaction::create([
        'user_id' => $user->id,
        'type' => 'transfer',
        'amount' => $validatedData['amount'],
        'status' => $senderStatus,
    ]);

    Transaction::create([
        'user_id' => $recipient->id,
        'type' => 'transfer',
        'amount' => $validatedData['amount'],
        'status' => $recipientStatus,
    ]);

    return redirect('/dashboard')->with('success', 'Transfer successfully.');
}

    
}