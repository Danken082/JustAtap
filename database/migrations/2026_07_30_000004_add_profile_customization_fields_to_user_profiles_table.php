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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('display_name_font_size')->nullable()->after('display_name');
            $table->string('logo_url')->nullable()->after('avatar_url');
            $table->json('badge_images')->nullable()->after('logo_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['display_name_font_size', 'logo_url', 'badge_images']);
        });
    }
};
