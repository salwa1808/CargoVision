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
        Schema::create('economic_indicators', function (Blueprint $table) {
             $table->id();

            $table->foreignId('country_id')->constrained()->cascadeOnDelete();

            $table->decimal('gdp',20,2)->nullable();

            $table->decimal('inflation',8,2)->nullable();

            $table->unsignedBigInteger('population')->nullable();

            $table->year('year')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_indicators');
    }
};
