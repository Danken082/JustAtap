<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('card_id')->nullable()->change();
            $table->boolean('is_corporate')->default(false)->after('password');
            $table->string('company_name')->nullable()->after('is_corporate');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['is_corporate', 'company_name']);
            $table->string('card_id')->nullable(false)->change();
        });
    }
};