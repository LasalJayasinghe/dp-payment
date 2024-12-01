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
        Schema::table('request_rejects', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_request')->nullable()->after('request_id');
            $table->foreign('sub_request')->references('id')->on('sub_requests')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_rejects', function (Blueprint $table) {
            $table->dropForeign('request_rejects_sub_request_foreign');
            $table->dropColumn('sub_request');
        });
    }
};
