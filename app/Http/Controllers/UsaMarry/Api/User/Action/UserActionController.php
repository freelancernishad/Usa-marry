<?php
namespace App\Http\Controllers\UsaMarry\Api\User\Action;

use App\Models\User;
use App\Models\Block;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserActionController extends Controller
{
    public function block(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'You cannot block yourself.'], 422);
        }

        Block::firstOrCreate([
            'user_id' => $request->user()->id,
            'blocked_user_id' => $user->id
        ]);

        return response()->json(['message' => 'User has been blocked.']);
    }

    public function report(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|in:fake,inappropriate,married,photo'
        ]);

        Report::create([
            'reporter_id' => $request->user()->id,
            'reported_user_id' => $user->id,
            'reason' => $request->reason
        ]);

        return response()->json(['message' => 'User has been reported.']);
    }
}
