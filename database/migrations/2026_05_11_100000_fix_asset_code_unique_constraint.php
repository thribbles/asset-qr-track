<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing unique constraint
        Schema::table('assets', function (Blueprint $table) {
            $table->dropUnique(['asset_code']);
        });

        // Add new unique constraint that includes deleted_at for soft deletes
        Schema::table('assets', function (Blueprint $table) {
            $table->unique(['asset_code', 'deleted_at'], 'assets_asset_code_deleted_at_unique');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropUnique('assets_asset_code_deleted_at_unique');
            $table->unique('asset_code');
        });
    }
};
