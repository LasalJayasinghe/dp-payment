<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models\Transaction;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request');
            $table->foreign('request')->references('id')->on('requests')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('type', [Transaction::FULL_PAYMENT, Transaction::ADVANCE_PAYMENT])
                ->default(Transaction::FULL_PAYMENT);
            $table->enum('status', [Transaction::TRANSACTION_SUCCESS, Transaction::TRANSACTION_FAILED])
                ->default(Transaction::TRANSACTION_SUCCESS);
            $table->json('meta')->nullable();
            $table->unsignedBigInteger("created_by")->nullable();
            $table->foreign("created_by")->references("id")->on("users")
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->foreign("updated_by")->references("id")->on("users")
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
