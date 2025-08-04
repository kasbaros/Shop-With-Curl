<?php

namespace App\Livewire\Guest;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Blog with Sidebar - ShopWithCarl')]
#[Layout('components.app-layout')]
class BlogSidebarLeft extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;
    public $selectedTag = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => null],
        'selectedTag' => ['except' => null],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->selectedTag = null;
        $this->resetPage();
    }

    public function selectTag($tagId)
    {
        $this->selectedTag = $tagId;
        $this->selectedCategory = null;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategory = null;
        $this->selectedTag = null;
        $this->resetPage();
    }

    public function render()
    {
        $posts = Post::published()
            ->with(['author', 'categories', 'tags'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->where('categories.id', $this->selectedCategory);
                });
            })
            ->when($this->selectedTag, function ($query) {
                $query->whereHas('tags', function ($q) {
                    $q->where('tags.id', $this->selectedTag);
                });
            })
            ->latest('published_at')
            ->paginate(8);

        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        $categories = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        return view('livewire.guest.blog.sidebar-left', [
            'posts' => $posts,
            'popularTags' => $popularTags,
            'categories' => $categories,
        ]);
    }
}
