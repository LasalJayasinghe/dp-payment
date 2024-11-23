<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SubRequest;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sub_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account')->nullable();
            $table->foreign('account')->references('id')->on('supplier_accounts')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedBigInteger('request');
            $table->foreign('request')->references('id')->on('requests')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('status', [SubRequest::STATUS_PENDING, SubRequest::STATUS_CHECKED, SubRequest::STATUS_APPROVED, SubRequest::STATUS_REJECTED, SubRequest::STATUS_WAITING_FOR_SIGNATURE])
                ->default(SubRequest::STATUS_PENDING);
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->foreign('checked_by')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedBigInteger('signed_by')->nullable();
            $table->foreign('signed_by')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->char('subcategory', 191);
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('due_date')->default(\Carbon\Carbon::now()->addDays(14));
            $table->char('note', 255)->nullable();
            $table->enum('priority', [SubRequest::PRIORITY_LOW, SubRequest::PRIORITY_NORMAL, SubRequest::PRIORITY_HIGH]);
            $table->char('vender_invoice', 191)->nullable();
            $table->enum('type', [\App\Models\Transaction::FULL_PAYMENT, \App\Models\Transaction::ADVANCE_PAYMENT])
                ->default(\App\Models\Transaction::ADVANCE_PAYMENT);
            $table->char('indicator', 191)->nullable();
            $table->char('payment_link', 255)->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->char('payment_type', 100)->nullable();
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
        Schema::dropIfExists('sub_requests');
    }
};
