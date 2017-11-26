@extends('layouts.app')

@section('content')
    <div class='container'>
        <h2>Products Manager</h2>
        <ul>
            <li><a href='{{ url('dashboard/product/create') }}'>Create new product</a></li>
            <li><a href='#'>Import</a></li>
        </ul>
        <hr>
        <p>Lista de produtos</p>
        <product-list :product='product'></product-list>
        <edit-product :product='product'></edit-product>
    </div>
@endsection