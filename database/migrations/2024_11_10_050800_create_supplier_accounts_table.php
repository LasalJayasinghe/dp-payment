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
        Schema::create('supplier_accounts', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('supplier_id'); 
            $table->string('account_name'); 
            $table->string('account_number')->unique();
            $table->string('bank_name'); 
            $table->string('branch');
            $table->timestamps(); 

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('supplier_accounts');
    }
};
