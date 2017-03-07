<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Stock;

class StockController extends Controller
{
    public function purchase($symbol)
    {
        $share_info = $this->getQuote(strtoupper($symbol));
        return view('stock.purchase',[
            'symbol' => $share_info[0],
            'name' => $share_info[1],
            'price' => $share_info[2]
        ]);
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'symbol' => 'required|alpha_num|max:10'
        ]);
        $share_info = $this->getQuote(strtoupper($request->symbol));
        return back()->with('share_info', [
            'Symbol' => $share_info[0],
            'Name' => trim($share_info[1], '"'),
            'Price' => $share_info[2],
            'Last trade date' => $share_info[3],
            'Last trade time' => $share_info[4],
            'Day\'s high' => $share_info[5],
            'Day\'s low' => $share_info[6],
            'Change' => $share_info[7],
            'Change percent' => $share_info[8]
        ])
        ->with('symbol', $share_info[0]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required|integer'
        ]);
        $quantity = $request->quantity;
        $share_info = $this->getQuote(strtoupper($request->symbol));
        $purchase_price = $share_info[2];
        $symbol = trim($share_info[0], '"');
        $name = trim($share_info[1], '"');

        $stock = new Stock;
        $stock->user_id = Auth::id();
        $stock->symbol = $symbol;
        $stock->stock_name = $name;
        $stock->quantity = $quantity;
        $stock->purchase_price = $purchase_price;
        $stock->save();

        $user = Auth::user();
        $cost = $quantity * $purchase_price;
        if ($cost > $user->cash) {
            return back()->with('message', 'Not enough balance');
        }
        $user->cash = $user->cash - $cost;
        $user->save();

        $message = $quantity . ' shares of ' . $name .  ' purchased successfully';
        return redirect('/home')->with('message', $message);
    }

    public function getQuote($symbol)
    {
        $share_info = file_get_contents('http://download.finance.yahoo.com/d/quotes.csv?s=' . $symbol . '&f=snl1d1t1hgc1p2&e=.csv');
        $data = explode(',', $share_info);
        return $data;
    }

    public function sell(Stock $stock)
    {
        $user = Auth::user();
        $current_price = $this->getQuote($stock->symbol)[2];
        $user->cash += $current_price * $stock->quantity;
        $user->save();
        $stock->delete();
        $delete_message = $stock->quantity . ' stocks for ' . $stock->stock_name . ' sold succesfully';
        return back()->with('delete_message', $delete_message);
    }
}
