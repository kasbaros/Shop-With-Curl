@extends('layouts.app')

@section('title', $category->name . ' - ShopWithCarl')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Categories
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="md:flex">
            @if($category->image)
                <div class="md:w-1/3">
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-64 object-cover">
                </div>
            @endif

            <div class="p-6 md:w-2/3">
                <h1 class="text-3xl font-bold mb-4">{{ $category->name }}</h1>

                @if($category->description)
                    <div class="text-gray-700 mb-6">
                        {{ $category->description }}
                    </div>
                @endif

                <div class="flex items-center text-sm text-gray-500">
                    <span>{{ $category->products->count() }} products in this category</span>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-6">Products in {{ $category->name }}</h2>

    @if($category->products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($category->products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($product->featured_image)
                        <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No image</span>
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">{{ $product->name }}</h3>

                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('products.show', $product->slug) }}" class="text-blue-600 hover:text-blue-800">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <p class="text-gray-600">No products found in this category.</p>
        </div>
    @endif
</div>
@endsection
