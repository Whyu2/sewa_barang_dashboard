<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Petakan role lama yang tidak lagi valid ke enum yang berlaku.
     * Enum UserRole hanya mengenal: admin, staff.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereNotIn('role', ['admin', 'staff'])
            ->update(['role' => 'staff']);
    }

    public function down(): void
    {
        // Tidak ada pemetaan balik yang aman; biarkan apa adanya.
    }
};
