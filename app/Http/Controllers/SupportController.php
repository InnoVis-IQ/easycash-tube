<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\SupportRequest;
use App\Models\FAQ;

class SupportController extends Controller
{
    /**
     * Display the support page with FAQs and contact form
     */
    public function index()
    {
        $faqs = FAQ::where('status', 'active')->orderBy('order', 'asc')->get();
        
        return view('backend.user.support.index', compact('faqs'));
    }

    /**
     * Store a new support request
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|in:general,technical,payment,withdrawal,other',
        ]);

        $supportRequest = SupportRequest::create([
            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => 'open',
        ]);

        // Optionally send email notification to admin
        // Mail::to('admin@example.com')->send(new SupportRequestNotification($supportRequest));

        return back()->with('success', 'Your support request has been submitted successfully. We will get back to you soon.');
    }

    /**
     * Display user's support request history
     */
    public function history()
    {
        $supportRequests = SupportRequest::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.user.support.history', compact('supportRequests'));
    }

    /**
     * Display a specific support request
     */
    public function show($id)
    {
        $supportRequest = SupportRequest::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('backend.user.support.show', compact('supportRequest'));
    }
} 