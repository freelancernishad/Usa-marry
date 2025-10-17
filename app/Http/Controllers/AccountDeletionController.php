<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AccountDeletionMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AccountDeletionController extends Controller
{
    public function deleteRequest(Request $request)
    {
        // Log::info('Received method: ' . $request->method());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // Send mail to admin or support
        Mail::to('freelancernishad123@gmail.com')->send(new AccountDeletionMail($validated));

        return response()->json(['message' => 'Your request has been submitted successfully.']);
    }
}
