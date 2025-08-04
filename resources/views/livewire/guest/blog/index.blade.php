<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Our Blog</h1>

        <!-- Search Bar -->
        <div class="mb-8">
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search posts..."
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                >
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    @if($search)
                        <button wire:click="$set('search', '')" class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    @endif
                </div>
            </div>
        </div>

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
