<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        // 1. প্রথমে nullable column হিসেবে যোগ করা
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_id')->nullable()->after('id');
        });

        // 2. Existing users এ unique ID assign করা
        $users = User::all();
        foreach ($users as $user) {
            $user->profile_id = $this->generateUniqueProfileId();
            $user->save();
        }

        // 3. Column কে not nullable এবং unique করা
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_id')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_id');
        });
    }

    private function generateUniqueProfileId()
    {
        do {
            $id = mt_rand(100000, 999999);
        } while (User::where('profile_id', $id)->exists());

        return $id;
    }
};
