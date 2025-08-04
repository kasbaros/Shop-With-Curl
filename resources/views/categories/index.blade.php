@extends('layouts.app')

@section('title', 'Categories - ShopWithCarl')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Categories</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <h1 class="text-2xl font-bold mb-6">All Categories</h1>
        @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">No image</span>
                    </div>
                @endif

                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $category->name }}</h2>

                    @if($category->description)
                        <p class="text-gray-600 mb-4">{{ Str::limit($category->description, 100) }}</p>
                    @endif

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">{{ $category->products_count }} products</span>
                        <a href="{{ route('categories.show', $category->slug) }}" class="text-blue-600 hover:text-blue-800">
                            View Category →
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
