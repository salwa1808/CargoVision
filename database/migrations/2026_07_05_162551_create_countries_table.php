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
        Schema::create('countries', function (Blueprint $table) {
             $table->id();

        $table->string('name');
        $table->string('official_name')->nullable();

        $table->string('cca2',2)->unique()->nullable();
        $table->string('cca3',3)->unique()->nullable();

        $table->string('capital')->nullable();

        $table->string('region')->nullable();
        $table->string('subregion')->nullable();

        $table->string('currency_code')->nullable();
        $table->string('currency_name')->nullable();
        $table->string('currency_symbol')->nullable();

        $table->string('language')->nullable();

        $table->unsignedBigInteger('population')->nullable();

        $table->decimal('latitude',10,6)->nullable();
        $table->decimal('longitude',10,6)->nullable();

        $table->string('flag_png')->nullable();
        $table->string('flag_svg')->nullable();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
