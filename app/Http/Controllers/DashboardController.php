<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // $user = Auth::user();
        // // $transactions = $user->transactions()->orderBy('created_at', 'desc')->get();

        // $transactions = Transaction::where('user_id', auth()->user()->id)
        // ->select('id', 'type', 'amount', 'status', 'created_at')
        // ->selectRaw('CASE WHEN amount >= 0 THEN "penambahan saldo" ELSE "pengurangan saldo" END AS status')
        // ->orderBy('created_at', 'desc')
        // ->take(5) // Ambil 5 transaksi terbaru
        // ->get();

    
        // return view('dashboard', compact('transactions'));

        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    
        $transactions->transform(function ($transaction) use ($user) {
            if ($transaction->user_id === $user->id && $transaction->amount < 0) {
                $transaction->status = 'Pengurangan saldo';
            } elseif ($transaction->user_id !== $user->id && $transaction->amount > 0) {
                $transaction->status = 'Penambahan saldo';
            }
    
            return $transaction;
        });
    
        return view('dashboard', compact('transactions'));
    }
    
}
