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
        Schema::create('approved_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->index(); // Foreign key to the original requests table
            $table->string('check_number')->nullable(); // Check number
            $table->string('voucher_number')->nullable(); // Voucher number
            $table->string('deposit_slip')->nullable(); // File path for the deposit slip
            $table->timestamp('approved_at')->nullable(); // Timestamp for approval
            $table->unsignedBigInteger('approved_by')->index(); // User who approved the request
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approved_requests');
    }
};
