<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App;
use App\Services\Yafi;

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
            $search = new Yafi($stock['symbol']);
            $current_price = $search->fetchPrice();
            $stock['current_price'] = $current_price;
        }
        return view('home', [
            'user' => $user,
            'stocks' => $stocks
        ]);
    }
}
