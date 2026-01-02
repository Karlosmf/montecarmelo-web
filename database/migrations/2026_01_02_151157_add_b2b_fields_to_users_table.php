<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('cuit')->nullable()->after('company_name');
            $table->string('phone')->nullable()->after('cuit');
            $table->string('address')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('password'); // Default true for normal users, false for B2B via code
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'cuit', 'phone', 'address', 'is_active']);
        });
    }
};
