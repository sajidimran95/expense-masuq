<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('staff')->after('is_admin');
            $table->json('permissions')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('permissions');
        });

        DB::table('users')
            ->where('is_admin', true)
            ->update([
                'role' => 'super_admin',
                'permissions' => json_encode(['expenses', 'reports', 'settings', 'staff']),
                'is_active' => true,
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['role', 'permissions', 'is_active']);
        });
    }
};
