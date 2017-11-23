@extends('layouts.app')

@section('content')
    <div class='container'>
        Home dashboard
    <ul>
        <li><a href='{{ url('dashboard/product') }}'>Products Manager</a></li>
    </ul>
    </div>
@endsection