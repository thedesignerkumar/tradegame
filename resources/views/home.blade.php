@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>{{ session()->get('message') }}</strong>
        </div>
    @endif
    <div class="row">

{{-- left column start --}}

        <div class="col-md-3">
            <h3 class="page-header">
                Search for share price
            </h3>
            <form method="POST" action="/stock/search">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="symbol">Company Symbol</label>
                    <input type="text" class="form-control" name="symbol" id="symbol" placeholder="symbol" autofocus>
                    @if ($errors->has('symbol'))
                    <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('symbol') }}</strong>
                    </span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <hr>

            @if (session()->has('share_info'))
            @foreach (session('share_info') as $key => $element)
            <div class="row">
                <div class="col-md-6"><p><strong>{{ $key }}</strong></p></div>
                <div class="col-md-6"><p>{{ trim($element, '"') }}</p></div>
            </div>
            @endforeach
            <hr>
            <a class="btn btn-success" href="/stock/purchase/{{ strtolower(trim(session('symbol'), '"')) }}">Purchase</a>
            @endif
        </div>

{{-- left column end --}}

{{-- right column start --}}
        <div class="col-md-9">
            @if (session()->has('delete_message'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>{{ session()->get('delete_message') }}</strong>
                    </div>
            @endif

            <h4 class="page-header pull-right"><strong>Cash Balance:</strong> Rs {{ $user->cash }}</h4>
            <h2>Your Stocks</h2>
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <td>Action</td>
                        <td>Share name</td>
                        <td>Symbol</td>
                        <td>Purchased Price (Rs)</td>
                        <td>Quantity</td>
                        <td>Purchased Date</td>
                        <td>Current Price (Rs)</td>
                        <td>Current Value (Rs)</td>
                        <td>Profit / Loss (Rs)</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stocks as $stock)
                        <tr class="text-center">
                            <td><a href="/stock/sell/{{ $stock->id }}">Sell</a></td>
                            <td>{{ $stock->stock_name }}</td>
                            <td>{{ $stock->symbol }}</td>
                            <td>{{ $stock->purchase_price }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ $stock->updated_at }}</td>
                            <td>{{ $stock->current_price }}</td>
                            <td>{{ $stock->current_price * $stock->quantity }}</td>
                            <td>{{ ($stock->current_price * $stock->quantity) -($stock->purchase_price * $stock->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
{{-- right column end --}}


    </div>
</div>
@endsection
