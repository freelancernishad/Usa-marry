<?php
namespace App\Http\Controllers\UsaMarry\Api\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Block;

class BlockController extends Controller
{
    // ব্লকড ইউজারের তালিকা (admin যে user গুলো block করেছে)
    public function index()
    {
        $blocks = Block::with(['user', 'blockedUser'])
                  ->latest()
                  ->paginate(20);

        return response()->json($blocks);
    }

    // ব্লকড ইউজার আনব্লক (delete block record)
    public function destroy($id)
    {
        $block = Block::findOrFail($id);
        $block->delete();

        return response()->json(['message' => 'User unblocked successfully.']);
    }
}
