@extends('layouts.app')

@section('title', 'Blog - ShopWithCarl')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Our Blog</h1>

        <div class="space-y-12">
            @forelse($posts as $post)
                <article class="border-b border-gray-200 pb-10">
                    @if($post->featured_image)
                        <div class="mb-6 overflow-hidden rounded-lg">
                            <img
                                src="{{ asset('storage/' . $post->featured_image) }}"
                                alt="{{ $post->title }}"
                                class="w-full h-[400px] object-cover transition-transform duration-300 hover:scale-105"
                            >
                        </div>
                    @endif

                    <div class="flex items-center text-sm text-gray-600 mb-3">
                        <span>{{ $post->published_at->format('F j, Y') }}</span>
                        <span class="mx-2">•</span>
                        @if($post->categories->isNotEmpty())
                            <div class="flex space-x-2">
                                @foreach($post->categories as $category)
                                    <a href="#" class="hover:text-blue-600 transition-colors">
                                        {{ $category->name }}
                                    </a>
                                    @if(!$loop->last)<span>,</span>@endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition-colors">
                        <a href="{{ route('blog.show', $post->slug) }}">
                            {{ $post->title }}
                        </a>
                    </h2>

                    <div class="text-gray-700 mb-4">
                        {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 200) }}
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3">
                                    <img
                                        src="{{ $post->author->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) }}"
                                        alt="{{ $post->author->name }}"
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $post->author->name }}</span>
                            </div>
                        </div>

                        <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center">
                            Read More
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No blog posts found</h3>
                    <p class="text-gray-600">Check back soon for new content!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
