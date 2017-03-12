<?php
namespace App\Services;


class Yafi
{

    // Stock informations
    private $symbol;
    private $name;
    private $price;
    private $last_trade_date;
    private $last_trade_time;
    private $days_high;
    private $days_low;
    private $change;
    private $change_percent;

    public function __construct($symbol)
    {
        $this->symbol = $symbol;

        $stock_info = $this->fetch();

        // set all the stock attributes
        $this->name = trim($stock_info[1], '"');
        $this->price = $stock_info[2];
        $this->last_trade_date = $stock_info[3];
        $this->last_trade_time = $stock_info[4];
        $this->days_high = $stock_info[5];
        $this->days_low = $stock_info[6];
        $this->change = $stock_info[7];
        $this->change_percent = $stock_info[8];
    }

    public function fetchAll()
    {
        return [
            'Symbol' => $this->symbol,
            'Name' => $this->name,
            'Price' => $this->price,
            'Last Trade Date' => $this->last_trade_date,
            'Last Trade Time' => $this->last_trade_time,
            'Day\'s High' => $this->days_high,
            'Day\'s Low' => $this->days_low,
            'Change' => $this->change,
            'Change Percent' =>$this->change_percent
        ];
    }

    public function fetchPrice()
    {
        return $this->price;
    }

    private function fetch()
    {
        $stock_info = file_get_contents('http://download.finance.yahoo.com/d/quotes.csv?s=' . $this->symbol . '&f=snl1d1t1hgc1p2&e=.csv');
        return explode(',', $stock_info);
    }
}