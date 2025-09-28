<?php

namespace App\Http\Controllers\Money;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WalletController extends Controller
{
public function index(): View
{
    $wallet = Auth::user()->wallet ?? Auth::user()->wallet()->create();
    $recentTransactions = Auth::user()->recentTransactions(5);
    
    return view('home.money.index', compact('wallet', 'recentTransactions'));
}
    
    public function transactions()
    {
        $wallet = Auth::user()->wallet;
        // Future: Add transaction history
        return back()->with('info', 'Transaction history feature coming soon!');
        //return view('money.wallet.transactions', compact('wallet'));
    }
}