<?php

    namespace App\Livewire\Guest;

    use App\Models\Post;
    use Livewire\Attributes\Layout;
    use Livewire\Attributes\Title;
    use Livewire\Component;

    class BlogShow extends Component
    {
        public Post $post;
        public $relatedPosts = [];

        public function mount(Post $post)
        {
            $this->post = $post;

            // Increment view count
            $this->post->incrementViewCount();

            // Get related posts
            $this->relatedPosts = Post::published()
                ->whereHas('categories', function ($query) {
                    $query->whereIn('categories.id', $this->post->categories->pluck('id'));
                })
                ->where('id', '!=', $this->post->id)
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        public function render()
        {
            return view('livewire.guest.blog.show', [
                'post' => $this->post,
                'relatedPosts' => $this->relatedPosts
            ])->layout('components.app-layout')
                ->title($this->post->title . ' - ShopWithCarl Blog');
        }
    }
