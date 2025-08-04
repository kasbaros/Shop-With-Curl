<?php

namespace App\Livewire\Guest;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Blog Grid - ShopWithCarl')]
#[Layout('components.app-layout')]

class BlogGrid extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $featured = Post::published()
            ->featured()
            ->with(['author', 'categories'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $posts = Post::published()
            ->with(['author', 'categories'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            })
            ->latest('published_at')
            ->paginate(12);

        return view('livewire.guest.blog.index', [
            'posts' => $posts,
            'featured' => $featured
        ]);
    }
}
