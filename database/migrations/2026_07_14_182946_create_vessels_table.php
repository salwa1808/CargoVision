<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('imo')->nullable()->unique();
            $table->string('type'); // 'Container', 'Tanker', 'Bulk Carrier', 'Cargo', 'Passenger'
            $table->string('status'); // 'Underway', 'Anchored', 'Moored'
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('speed', 4, 1)->default(0.0); // in knots
            $table->integer('heading')->default(0); // 0-360 degrees
            $table->string('destination')->nullable();
            $table->unsignedBigInteger('port_id')->nullable();
            $table->timestamps();

            $table->foreign('port_id')->references('id')->on('ports')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
