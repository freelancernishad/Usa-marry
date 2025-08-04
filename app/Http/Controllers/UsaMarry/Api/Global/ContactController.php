<?php

namespace App\Http\Controllers\UsaMarry\Api\Global;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'email'     => 'required|email',
            'subject'   => 'required|string',
            'message'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $message = ContactMessage::create($validated);

        try {
            Mail::to('freelancernishad123@gmail.com')->send(new ContactMessageMail($message));
            $message->update(['email_sent' => true]);
        } catch (\Exception $e) {
            // email_sent remains false
            Log::error("Email failed: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Your message has been received.',
        ]);
    }
}
