<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->string('front_background')->nullable()->after('favicon');
            $table->string('front_badge_text')->nullable()->after('meta_description');
            $table->string('front_title')->nullable()->after('front_badge_text');
            $table->text('front_description')->nullable()->after('front_title');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'front_background',
                'front_badge_text',
                'front_title',
                'front_description',
            ]);
        });
    }
};
