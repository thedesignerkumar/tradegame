<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Auth;
use App;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->load('stocks');
        $stocks = $user->stocks;
        foreach ($stocks as $stock) {
            $search = new StockController;
            $current_price = $search->getQuote($stock->symbol);
            $stock['current_price'] = $current_price[2];
        }
        return view('home', [
            'user' => $user,
            'stocks' => $stocks
        ]);
    }
}
