<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Subscribe a user to the newsletter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        // In a real application, you would store the email in the database
        // For now, we'll just return a success response

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!'
        ]);
    }
}
