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
            'category' => 'required|in:general,technical,payment,withdrawal,other',
        ]);

        $supportRequest = SupportRequest::create([
            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
            'message' => $request->message,
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

    // Admin Functions

    /**
     * Admin: View all support requests
     */
    public function adminIndex()
    {
        $supportRequests = SupportRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.admin.support.index', compact('supportRequests'));
    }

    /**
     * Admin: View a specific support request
     */
    public function adminShow($id)
    {
        $supportRequest = SupportRequest::with('user')->findOrFail($id);

        return view('backend.admin.support.show', compact('supportRequest'));
    }

    /**
     * Admin: Respond to a support request
     */
    public function adminRespond(Request $request, $id)
    {
        $request->validate([
            'admin_response' => 'required|string|max:2000',
        ]);

        $supportRequest = SupportRequest::findOrFail($id);
        
        $supportRequest->update([
            'admin_response' => $request->admin_response,
            'admin_response_at' => now(),
            'status' => 'resolved',
        ]);

        // Optionally send email notification to user
        // Mail::to($supportRequest->user->email)->send(new SupportResponseNotification($supportRequest));

        return redirect()->route('admin.support.show', $id)
            ->with('success', 'Response sent successfully.');
    }

    /**
     * Admin: Update support request status
     */
    public function adminUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $supportRequest = SupportRequest::findOrFail($id);
        $supportRequest->update(['status' => $request->status]);

        return redirect()->route('admin.support.show', $id)
            ->with('success', 'Status updated successfully.');
    }

    /**
     * Admin: Mark support request as closed
     */
    public function adminClose($id)
    {
        $supportRequest = SupportRequest::findOrFail($id);
        $supportRequest->update(['status' => 'closed']);

        return redirect()->route('admin.support.index')
            ->with('success', 'Support request marked as closed.');
    }

    /**
     * User: View admin response
     */
    public function viewResponse($id)
    {
        $supportRequest = SupportRequest::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('backend.user.support.response', compact('supportRequest'));
    }
} 