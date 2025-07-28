<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    /**
     * Display the about us page.
     *
     * @return View
     */
    public function aboutUs()
    {
        return view('pages.about-us');
    }

    /**
     * Display the brands page.
     *
     * @return View
     */
    public function brands()
    {
        return view('pages.brands');
    }

    /**
     * Display the brands v2 page.
     *
     * @return View
     */
    public function brandsV2()
    {
        return view('pages.brands-v2');
    }

    /**
     * Display the contact page.
     *
     * @return View
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Display the contact page version 2.
     *
     * @return View
     */
    public function contactTwo()
    {
        return view('pages.contact-2');
    }

    /**
     * Display the FAQ page.
     *
     * @return View
     */
    public function faq()
    {
        return view('pages.faq');
    }

    /**
     * Display the FAQ page version 2.
     *
     * @return View
     */
    public function faqTwo()
    {
        return view('pages.faq-2');
    }

    /**
     * Display the our store page.
     *
     * @return View
     */
    public function ourStore()
    {
        return view('pages.our-store');
    }

    /**
     * Display the store locator page.
     *
     * @return View
     */
    public function storeLocator()
    {
        return view('pages.store-locator');
    }

    /**
     * Display the timeline page.
     *
     * @return View
     */
    public function timeline()
    {
        return view('pages.timeline');
    }

    /**
     * Display the view cart page.
     *
     * @return View
     */
    public function viewCart()
    {
        return view('pages.view-cart');
    }

    /**
     * Display the checkout page.
     *
     * @return View
     */
    public function checkout()
    {
        return view('pages.checkout');
    }

    /**
     * Display the payment confirmation page.
     *
     * @return View
     */
    public function paymentConfirmation()
    {
        return view('pages.payment-confirmation');
    }

    /**
     * Display the payment failure page.
     *
     * @return View
     */
    public function paymentFailure()
    {
        return view('pages.payment-failure');
    }

    /**
     * Display the invoice page.
     *
     * @return View
     */
    public function invoice()
    {
        return view('pages.invoice');
    }

    /**
     * Display the 404 page.
     *
     * @return View
     */
    public function notFound()
    {
        return view('pages.404');
    }

    /**
     * Display the privacy policy page.
     *
     * @return View
     */
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    /**
     * Display the returns and exchanges page.
     *
     * @return View
     */
    public function returnsExchanges()
    {
        return view('pages.returns-exchanges');
    }

    /**
     * Display the shipping page.
     *
     * @return View
     */
    public function shipping()
    {
        return view('pages.shipping');
    }

    /**
     * Display the terms and conditions page.
     *
     * @return View
     */
    public function termsConditions()
    {
        return view('pages.terms-conditions');
    }
}
