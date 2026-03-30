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
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('platform', 10);        // 'ios' or 'android'
            $table->string('app_version', 50);
            $table->string('bundle_version', 50);
            $table->text('bundle_url');
            $table->string('bundle_hash', 64);      // SHA-256 hex
            $table->string('bundle_file_name');
            $table->string('environment', 20)->default('prod');
            $table->timestamps();

            $table->unique([
                'app_name',
                'platform',
                'app_version',
                'bundle_version',
                'environment',
            ], 'releases_unique_release');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
