@extends('layouts.app')

@section('content')
<div class="container">



    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Contul meu</li>
        </ol>
    </nav>  

    <div class="row mb-3">
        <div class="col-12 col-lg-12 col-xl-12 plugin-download-card">
            <div class="card">
                <div class="card-header" style="border-bottom: 0;">
                    <div style="position: relative; top: 3px;">
                        <i class="fas fa-file-archive" style="font-size: 30px; float: left; margin: 0 15px 0 0; color: green; float: left;"></i> <span style="float: left; line-height: 2.1;"> Descarca plugin-ul Safe-Alternative</span>
                    </div>
                    <a class="btn btn-outline-success" style="float: right;" href="https://api.curie.ro/download_unsigned/curiero-plugin" download=""> <i class="fa fa-download" aria-hidden="true"></i>
                        Descarca plugin <sub>v2.3.1</sub> </a>
                </div>
            </div>
        </div>
    </div>


    @if ($accounts)
    @foreach($accounts->chunk(2) as $accountChunk)
        <div class="card-deck">
            @foreach($accountChunk as $account)
                <div class="col-md-6">
                    <div class="card mb-6 box-shadow">
                       
                            
                            <div class="card-body">
                            <h3>{{ $account->courier_account}}</h3>
                            <p class="description">{{ $account->courier_type }}</p>
                            <p class="description">{{ $account->account_type }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                            
                                <small class="text-muted">${{ $account->calls_number }} generari </small>

                                <a href="{{ route('account') }}" class="btn btn-primary btn-sm" role="button">
                                    <i class="fa fa-star" aria-hidden="true"></i> Cumpara premium
                                </a>
                            
                           
                            </div>
                        </div>
                    </div>
                </div>

                
            @endforeach
        </div>
    @endforeach
    @endif


</div>
@endsection
