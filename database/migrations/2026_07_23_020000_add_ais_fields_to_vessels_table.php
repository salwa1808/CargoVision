<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->unsignedBigInteger('mmsi')->nullable()->unique()->after('id');
            $table->string('call_sign')->nullable()->after('imo');
            $table->unsignedSmallInteger('ais_ship_type')->nullable()->after('type');
            $table->decimal('course', 5, 1)->nullable()->after('heading');
            $table->timestamp('position_reported_at')->nullable()->after('destination');
            $table->string('data_source')->nullable()->index()->after('position_reported_at');
        });
    }

    public function down(): void
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->dropUnique(['mmsi']);
            $table->dropIndex(['data_source']);
            $table->dropColumn(['mmsi','call_sign','ais_ship_type','course','position_reported_at','data_source']);
        });
    }
};
