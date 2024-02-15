@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="row">
    @foreach($categories as $category)
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $category->name }}</h5>
                <p class="card-text">{{ $category->description }}</p>
                <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="btn btn-primary">View
                    Products</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection