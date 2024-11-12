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
        Schema::table('users', function (Blueprint $table) {
            // Add new columns
            $table->string('role')->default('user')->after('email'); // Add 'role' column
            $table->string('fname')->nullable()->after('role'); // Add 'fname' column
            $table->text('signature')->nullable()->after('fname'); // Add 'signature' column
            $table->timestamp('last_login')->nullable()->after('signature'); // Add 'last_login' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the columns if rolling back
            $table->dropColumn('role');
            $table->dropColumn('fname');
            $table->dropColumn('signature');
            $table->dropColumn('last_login');
        });
    }
};
