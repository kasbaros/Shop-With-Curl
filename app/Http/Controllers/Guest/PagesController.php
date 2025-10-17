<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

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
     * Display the contact page.
     *
     * @return View
     */
    public function contact()
    {
        return view('pages.contact');
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

    /**
     * Handle contact form submission.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contactSubmit(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Send email notification
            Mail::to(setting('site_email', config('app.admin_email', config('mail.from.address', 'admin@example.com'))))
                ->send(new ContactFormMail($validated));

            // Log the contact form submission
            \Log::info('Contact form submitted:', $validated);

            return redirect()->route('pages.contact')
                ->with('success', 'Thank you for your message! We will get back to you soon.');
        } catch (\Exception $e) {
            \Log::error('Contact form submission failed:', ['error' => $e->getMessage()]);

            return redirect()->route('pages.contact')
                ->with('error', 'Sorry, there was an error sending your message. Please try again.')
                ->withInput();
        }
    }
}
