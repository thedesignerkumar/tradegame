@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h3 class="page-header text-center">Purchase share</h3>
        <form method="POST" action="/stock/create">
        {{ csrf_field() }}
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <p><strong>Name</strong></p>
                    <p>{{ trim($name, '"') }}</p>
                    <p><strong>Price</strong></p>
                    <p id="price">{{ $price }}</p>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="text" name="quantity" id="quantity" class="form-control" value={{ old('quantity') }}>
                        @if ($errors->has('quantity'))
                            <span class="help-block">
                                <strong class="text-danger">{{ $errors->first('quantity') }}</strong>
                            </span>
                        @endif
                    </div>
                    <input type="hidden" name="symbol" value="{{ $symbol }}">
                    {{-- <p><strong>Total cost</strong></p> --}}
                    {{-- <p></p> --}}
                    <button type="submit" class="btn btn-primary">Purchase</button>
                </div>
            </div>
        </form>
    </div>
@stop