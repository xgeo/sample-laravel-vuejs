@extends('layouts.app')

@section('content')
    <div class='container'>
        <div class='btn-control'>
            <a href="{{ route('product.index') }}" class='btn btn-default'>Voltar</a>
        </div>
        <hr>
        <create-product-component></create-product-component>
    </div>
@endsection