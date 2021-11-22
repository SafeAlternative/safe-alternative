@extends('layouts.app')


@section('title')
    Laravel Shopping Cart
@endsection

@section('content')
    @foreach($products->chunk(3) as $productChunk)
        <div class="card-deck">
            @foreach($productChunk as $product)
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                       
                            <img class="card-img-top mx-auto d-block embed-responsive-item" src="{{ asset($product->imagePath ) }}" alt="Card image cap" style="width: 11rem; height: 4rem;">
                        
                            <div class="card-body">
                            <h3>{{ $product->title }}</h3>
                            <p class="description">{{ $product->description }}</p>
                        
                            <div class="d-flex justify-content-between align-items-center">
                            
                                <small class="text-muted">${{ $product->price }} RON </small>

                                <a href="{{ route('account') }}" class="btn btn-primary btn-sm" role="button">
                                    <i class="fa fa-download" aria-hidden="true"></i> Download
                                </a>
                            
                           
                            </div>
                        </div>
                    </div>
                </div>

                
            @endforeach
        </div>
    @endforeach


@endsection