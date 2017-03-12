<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Stock extends Model
{
    public function user()
    {
        return $this->belongsTo(User::Class);
    }
    
    public function new_stock(array $stock_info, $quantity)
    {
        extract($stock_info);
        $this->user_id = Auth::id();
        $this->symbol = $Symbol;
        $this->stock_name = $Name;
        $this->quantity = $quantity;
        $this->purchase_price = $Price;
        $this->save();
    }
    
}
