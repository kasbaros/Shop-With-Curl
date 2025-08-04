<?php

namespace App\Livewire\Guest;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Blog List - ShopWithCarl')]
#[Layout('components.app-layout')]
class BlogList extends Component
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
        $posts = Post::published()
            ->with(['author', 'categories'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            })
            ->latest('published_at')
            ->paginate(10);

        return view('livewire.guest.blog.list', [
            'posts' => $posts
        ]);
    }
}
