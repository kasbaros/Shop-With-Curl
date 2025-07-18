<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display the blog index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('blog.index');
    }

    /**
     * Display the blog grid layout.
     *
     * @return \Illuminate\View\View
     */
    public function grid()
    {
        return view('blog.grid');
    }

    /**
     * Display the blog with left sidebar.
     *
     * @return \Illuminate\View\View
     */
    public function sidebarLeft()
    {
        return view('blog.sidebar-left');
    }

    /**
     * Display the blog with right sidebar.
     *
     * @return \Illuminate\View\View
     */
    public function sidebarRight()
    {
        return view('blog.sidebar-right');
    }

    /**
     * Display the blog list layout.
     *
     * @return \Illuminate\View\View
     */
    public function list()
    {
        return view('blog.list');
    }

    /**
     * Display a specific blog post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\View\View
     */
    public function show($post)
    {
        // In a real application, you would fetch the post from the database
        // For now, we'll just return the view
        return view('blog.show', compact('post'));
    }
}
