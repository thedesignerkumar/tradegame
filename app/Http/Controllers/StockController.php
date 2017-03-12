<?php

namespace App\Http\Controllers;

use App\Services\Yafi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Stock;

class StockController extends Controller
{
    public function purchase($symbol)
    {
        $yafi = new Yafi(strtoupper($symbol));
        $share_info = $yafi->fetchAll();
        return view('stock.purchase',[
            'symbol' => $share_info['Symbol'],
            'name' => $share_info['Name'],
            'price' => $share_info['Price']
        ]);
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'symbol' => 'required|alpha_num|max:10'
        ]);
        $yafi = new Yafi(strtoupper($request->symbol));
        $share_info = $yafi->fetchAll();
        return back()->with(compact('share_info'))
            ->with('symbol', $request->symbol);
    }

    public function create(Request $request, Stock $stock)
    {
        $this->validate($request, [
            'quantity' => 'required|integer'
        ]);
        $quantity = $request->quantity;
        $yafi = new Yafi(strtoupper($request->symbol));
        $share_info = $yafi->fetchAll();

        // if user doesn't have enough money, return with the error
        $cost = $quantity * $share_info['Price'];
        $user = Auth::user();
        if ($cost > $user->cash) {
            return back()->with('message', 'Not enough balance');
        }

        $stock->new_stock($share_info, $quantity);

        $user->cash = $user->cash - $cost;
        $user->save();

        $message = $quantity . ' shares of ' . $share_info['Name'] .  ' purchased successfully';
        return redirect('/home')->with('message', $message);
    }

    public function sell(Stock $stock)
    {
        $user = Auth::user();
        $yafi = new Yafi($stock->symbol);
        $current_price = $yafi->fetchPrice();
        $user->cash += $current_price * $stock->quantity;
        $user->save();
        $stock->delete();
        $delete_message = $stock->quantity . ' stocks for ' . $stock->stock_name . ' sold succesfully';
        return back()->with('delete_message', $delete_message);
    }
}
