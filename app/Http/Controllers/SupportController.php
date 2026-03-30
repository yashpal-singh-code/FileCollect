<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Support Page
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $tickets = Support::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('support.index', compact('tickets'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Support Ticket
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string', 'min:10'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'], // 10MB
        ]);

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {

            $attachmentPath = $request
                ->file('attachment')
                ->store('support-attachments', 's3');
        }

        Support::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'message' => $validated['message'],
            'attachment' => $attachmentPath,
            'status' => 'open',
        ]);

        return redirect()
            ->route('support.index')
            ->with('success', 'Support ticket submitted successfully.');
    }
}
