<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var array<int, string> */
    private array $tables = [
        'users',
        'personal_access_tokens',
        'user_profiles',
        'profile_links',
        'cards',
        'products',
        'product_sizes',
        'product_colors',
        'product_images',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            DB::table($tableName)->whereNull('created_at')->update(['created_at' => now()]);
            DB::table($tableName)->whereNull('updated_at')->update(['updated_at' => now()]);

            Schema::table($tableName, function (Blueprint $table): void {
                $table->timestamp('created_at')->useCurrent()->change();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->change();
            });
        }

        DB::table('password_reset_tokens')->whereNull('created_at')->update(['created_at' => now()]);

        Schema::table('password_reset_tokens', function (Blueprint $table): void {
            $table->timestamp('created_at')->useCurrent()->change();
        });
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->timestamp('created_at')->nullable()->change();
                $table->timestamp('updated_at')->nullable()->change();
            });
        }

        Schema::table('password_reset_tokens', function (Blueprint $table): void {
            $table->timestamp('created_at')->nullable()->change();
        });
    }
};