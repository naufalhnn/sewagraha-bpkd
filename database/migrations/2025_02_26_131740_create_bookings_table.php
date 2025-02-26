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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('venue_id')->constrained();
            $table->string('booking_code');
            $table->date('event_start_date');
            $table->date('event_end_date');
            $table->string('purpose');
            $table->integer('total_price');
            $table->enum('status', ['PENDING', 'CONFIRMED', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
