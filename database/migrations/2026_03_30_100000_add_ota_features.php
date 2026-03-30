<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('releases', function (Blueprint $table) {
            $table->boolean('is_enabled')->default(true)->after('environment');
            $table->unsignedTinyInteger('rollout_percentage')->default(100)->after('is_enabled');
            $table->foreignId('promoted_from_id')->nullable()->after('rollout_percentage')
                  ->constrained('releases')->nullOnDelete();
            $table->boolean('is_current')->default(false)->after('promoted_from_id');
        });

        Schema::create('release_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('release_id')->constrained('releases')->cascadeOnDelete();
            $table->string('event_type', 20);
            $table->string('device_id', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('release_events');

        Schema::table('releases', function (Blueprint $table) {
            $table->dropForeign(['promoted_from_id']);
            $table->dropColumn(['is_enabled', 'rollout_percentage', 'promoted_from_id', 'is_current']);
        });
    }
};
