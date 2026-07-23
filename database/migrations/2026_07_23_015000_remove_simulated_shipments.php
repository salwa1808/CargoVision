<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('shipments')->where('is_simulated', true)->delete();
    }

    public function down(): void
    {
        // Data simulasi sengaja tidak dipulihkan.
    }
};
