<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_score_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('country_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->double('weather_score')->default(0);

            $table->double('inflation_score')->default(0);

            $table->double('currency_score')->default(0);

            $table->double('news_score')->default(0);

            $table->double('total_score');

            $table->string('risk_level');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_score_histories');
    }
};