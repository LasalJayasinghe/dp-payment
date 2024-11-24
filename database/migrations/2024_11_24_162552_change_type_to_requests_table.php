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
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->enum('type', [\App\Models\Requests::TYPE_LOCAL, \App\Models\Requests::TYPE_FOREIGN])->default(\App\Models\Requests::TYPE_LOCAL)->after('vender_invoice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->enum('type', [\App\Models\Requests::FULL_PAYMENT, \App\Models\Requests::ADVANCE_PAYMENT])->default(\App\Models\Requests::ADVANCE_PAYMENT)->after('vender_invoice');
        });
    }
};
