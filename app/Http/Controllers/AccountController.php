<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\User;
use App\Models\Transaction;

class AccountController extends Controller
{
    public function showAccount()
    {
        $user = Auth::user();
        $account = $user->account;
        
        if (!$account) {
            return redirect()->route('account.create')->with('error', 'You need to create an account first.');
        }
        
        return view('account.show', ['account' => $account]);
    }

    public function showCreateAccountForm()
    {
        $user = Auth::user();
        $account = $user->account;
        
        if (!$account) {
            $account = new Account();
        }
        
        return view('account.create', ['account' => $account]);
    }

    public function createAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => 'required|min:8',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $user = Auth::user();
    
        $account = new Account;
        $account->account_number = $request->account_number;
        $account->user_id = $user->id;
        $account->save();
    
        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    
    
    }

    public function showDepositForm()
    {
        $user = Auth::user();
        $account = $user->account;
    
        return view('account.deposit', ['account' => $account]);
    }

    public function deposit(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'recipient' => 'required|exists:users,email',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $recipient = User::where('email', $request->recipient)->first();
        // Simpan transaksi deposit
        $transaction = new Transaction;
        $transaction->user_id = $user->id;
        $transaction->sender_id = $user->id;
        $transaction->receiver_id = $user->id;
        $transaction->type = 'deposit';
        $transaction->amount = $request->amount;
        $transaction->save();
    
        return redirect()->route('dashboard')->with('success', 'Deposit transaction saved successfully!');
    }

    public function withdraw(Request $request)
    {
        $user = Auth::user();
        $account = $user->account;
        
        // Validate the request
        
        $amount = $request->input('amount');
        
        if ($account->balance < $amount) {
            return redirect()->route('account')->with('error', 'Insufficient balance.');
        }
        
        $account->balance -= $amount;
        $account->save();
        
        // Perform additional actions if necessary
        
        return redirect()->route('account')->with('success', 'Withdrawal successful.');
    }

    public function transfer(Request $request)
    {
        $user = Auth::user();
        $account = $user->account;
        
        // Validate the request
        
        $amount = $request->input('amount');
        $recipientEmail = $request->input('recipient_email');
        
        // Check if recipient email exists
        $recipient = User::where('email', $recipientEmail)->first();
        if (!$recipient) {
            return redirect()->route('account')->with('error', 'Recipient not found.');
        }
        
        // Check if recipient has an account
        if (!$recipient->account) {
            return redirect()->route('account')->with('error', 'Recipient does not have an account.');
        }
        
        // Check if sender has sufficient balance
        if ($account->balance < $amount) {
            return redirect()->route('account')->with('error', 'Insufficient balance.');
        }
        
        $recipientAccount = $recipient->account;
        
        $account->balance -= $amount;
        $account->save();
        
        $recipientAccount->balance += $amount;
        $recipientAccount->save();
        
        // Perform additional actions if necessary
        
        return redirect()->route('account')->with('success', 'Transfer successful.');
    }

    public function showTransactionHistory()
    {
        $user = Auth::user();
        $account = $user->account;

        $transactions = $account->transactions()->latest()->get();

        return view('account.history', ['transactions' => $transactions]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => 'required|min:8',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Membuat akun jika validasi berhasil
        $account = new Account;
        $account->account_number = $request->account_number;
        $account->save();
    
        // Redirect atau tindakan lain setelah berhasil membuat akun
    }
}
