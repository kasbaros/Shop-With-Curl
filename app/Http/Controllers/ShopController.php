<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display the shop index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shop.index');
    }

    /**
     * Display the shop with left sidebar.
     *
     * @return \Illuminate\View\View
     */
    public function leftSidebar()
    {
        return view('shop.left-sidebar');
    }

    /**
     * Display the shop with right sidebar.
     *
     * @return \Illuminate\View\View
     */
    public function rightSidebar()
    {
        return view('shop.right-sidebar');
    }

    /**
     * Display the shop with full width layout.
     *
     * @return \Illuminate\View\View
     */
    public function fullwidth()
    {
        return view('shop.fullwidth');
    }

    /**
     * Display the sub collection page.
     *
     * @return \Illuminate\View\View
     */
    public function subCollection()
    {
        return view('shop.sub-collection');
    }

    /**
     * Display the collections list page.
     *
     * @return \Illuminate\View\View
     */
    public function collectionsList()
    {
        return view('shop.collections-list');
    }

    /**
     * Display the shop with pagination links.
     *
     * @return \Illuminate\View\View
     */
    public function paginationLinks()
    {
        return view('shop.pagination-links');
    }

    /**
     * Display the shop with load more pagination.
     *
     * @return \Illuminate\View\View
     */
    public function paginationLoadmore()
    {
        return view('shop.pagination-loadmore');
    }

    /**
     * Display the shop with infinite scrolling.
     *
     * @return \Illuminate\View\View
     */
    public function infiniteScrolling()
    {
        return view('shop.infinite-scrolling');
    }

    /**
     * Display the shop with filter sidebar.
     *
     * @return \Illuminate\View\View
     */
    public function filterSidebar()
    {
        return view('shop.filter-sidebar');
    }

    /**
     * Display the shop with hidden filter.
     *
     * @return \Illuminate\View\View
     */
    public function filterHidden()
    {
        return view('shop.filter-hidden');
    }
}
