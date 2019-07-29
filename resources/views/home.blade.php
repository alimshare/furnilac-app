@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>Dashboard</h1>
        <ol class="breadcrumb">
            <!-- <li><a href="/#"><i class="fa fa-home"></i> Home</a></li> -->
            <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        @if (session('status'))
            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
        @endif

        You are logged in!
    </section>

@endsection
