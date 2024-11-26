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
        Schema::create('cash_account_feed_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_account');
            $table->foreign('cash_account')->references('id')->on('cash_accounts')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->enum('status', [\App\Models\CashAccountFeedLog::CREDITED, \App\Models\CashAccountFeedLog::DEBITED])
                ->default(\App\Models\CashAccountFeedLog::CREDITED);
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
        Schema::dropIfExists('cash_account_feed_logs');
    }
};
