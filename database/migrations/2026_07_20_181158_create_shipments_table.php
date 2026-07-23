<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();

            $table->string('tracking_number')->unique();

            $table->foreignId('origin_country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->foreignId('destination_country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->foreignId('origin_port_id')
                ->nullable()
                ->constrained('ports')
                ->nullOnDelete();

            $table->foreignId('destination_port_id')
                ->nullable()
                ->constrained('ports')
                ->nullOnDelete();

            $table->foreignId('vessel_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('transport_mode', [
                'ship',
                'air',
                'truck'
            ]);

            $table->enum('status', [
                'pending',
                'loading',
                'departed',
                'in_transit',
                'arrived',
                'delivered',
                'cancelled'
            ])->default('pending');

            $table->double('distance')->nullable();
            $table->integer('progress')->default(0);

            $table->timestamp('departure_at')->nullable();
            $table->timestamp('estimated_arrival')->nullable();
            $table->timestamp('arrived_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};