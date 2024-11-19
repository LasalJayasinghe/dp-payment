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
        Schema::create('requests', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->decimal('amount', 10, 2); // Amount field
            $table->enum('status', ['pending', 'checked', 'waiting_for_signature', 'approved', 'rejected'])->default('pending'); // Status field
            $table->unsignedBigInteger('checked_by')->nullable(); // Foreign key for user who checked the request
            $table->unsignedBigInteger('approved_by')->nullable(); // Foreign key for user who approved the request
            $table->timestamps(); // created_at and updated_at
            $table->string('subcategory'); // Subcategory field
            $table->unsignedBigInteger('supplier_id'); // Foreign key to supplier
            $table->timestamp('due_date')->nullable(); // Due date field
            $table->text('note')->nullable(); // Note field, nullable
            $table->enum('priority', ['normal', 'high'])->default('normal'); // Priority field
            $table->string('vender_invoice')->nullable(); // Vendor invoice, nullable
            $table->string('type')->nullable(); // Indicator field, nullable
            $table->string('indicator')->nullable(); // Indicator field, nullable
            $table->string('payment_link')->nullable(); // Payment link, nullable
            $table->timestamp('checked_date')->nullable(); // Date when request was checked, nullable
            $table->timestamp('approved_date')->nullable(); // Date when request was approved, nullable
            $table->string('payment_type')->nullable(); // Indicator field, nullable

            // Adding foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('checked_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
