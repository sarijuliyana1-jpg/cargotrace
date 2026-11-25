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
            $table->string('shipment_number')->unique();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('carrier_id')->constrained('carriers');
            $table->foreignId('origin_location_id')->constrained('locations');
            $table->foreignId('destination_location_id')->constrained('locations');
            $table->string('cargo_type');
            $table->decimal('weight_kg', 10, 2);
            $table->decimal('volume_cbm', 10, 2)->nullable();
            $table->dateTime('expected_departure_at');
            $table->dateTime('expected_arrival_at');
            $table->dateTime('estimated_arrival_at')->nullable();
            $table->string('current_status')->default('CREATED')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};