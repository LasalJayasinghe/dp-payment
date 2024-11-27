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
        Schema::create('cash_accounts', function (Blueprint $table) {
            $table->id();
            $table->char('name', 255)->nullable();
            $table->text('account_number')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->enum('status', [\App\Models\CashAccount::ACTIVE, \App\Models\CashAccount::INACTIVE])
                ->default(\App\Models\CashAccount::ACTIVE);
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
        Schema::dropIfExists('cash_accounts');
    }
};
