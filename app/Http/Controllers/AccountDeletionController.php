<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountDeletionMail;

class AccountDeletionController extends Controller
{
    public function deleteRequest(Request $request)
    {
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
