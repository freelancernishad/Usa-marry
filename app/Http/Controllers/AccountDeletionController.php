<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AccountDeletionMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AccountDeletionController extends Controller
{
    public function deleteRequest(Request $request)
    {
        Log::info('Received method: ' . $request->method());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'reason' => 'nullable|string',
        ]);

        // Send mail to admin or support
        Mail::to('freelancernishad123@gmail.com')->send(new AccountDeletionMail($validated));

        return response()->json(['message' => 'Your request has been submitted successfully.']);
    }
}
