<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('cargo_description');
            $table->decimal('cargo_weight', 12, 2)->nullable();
            $table->foreignId('origin_country_id')->constrained('countries')->restrictOnDelete();
            $table->foreignId('destination_country_id')->constrained('countries')->restrictOnDelete();
            $table->foreignId('origin_port_id')->nullable()->constrained('ports')->nullOnDelete();
            $table->foreignId('destination_port_id')->nullable()->constrained('ports')->nullOnDelete();
            $table->foreignId('vessel_id')->nullable()->constrained('vessels')->nullOnDelete();
            $table->timestamp('departure_at')->nullable();
            $table->timestamp('estimated_arrival')->nullable();
            $table->enum('status', ['draft', 'confirmed', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->foreignId('booking_id')->nullable()->after('id')->unique()->constrained()->nullOnDelete();
        });

        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
        Schema::table('shipments', fn (Blueprint $table) => $table->dropConstrainedForeignId('booking_id'));
        Schema::dropIfExists('bookings');
    }
};
