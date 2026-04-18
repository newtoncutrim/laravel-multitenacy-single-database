<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('segments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('segment_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('status')->default('active')->after('uuid');
            $table->json('metadata')->nullable()->after('status');

            $table->index(['segment_id', 'status']);
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('scope')->default('tenant');
            $table->string('category')->default('core');
            $table->boolean('is_core')->default(false);
            $table->boolean('active')->default(true);
            $table->string('navigation_label')->nullable();
            $table->string('navigation_path')->nullable();
            $table->string('api_prefix')->nullable();
            $table->string('icon')->nullable();
            $table->json('config_schema')->nullable();
            $table->timestamps();

            $table->index(['scope', 'category']);
            $table->index(['active', 'is_core']);
        });

        Schema::create('module_segment', function (Blueprint $table) {
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->foreignId('segment_id')->constrained()->cascadeOnDelete();
            $table->boolean('enabled_by_default')->default(true);
            $table->json('default_config')->nullable();
            $table->timestamps();

            $table->primary(['module_id', 'segment_id']);
        });

        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->boolean('enabled')->default(true);
            $table->json('config')->nullable();
            $table->timestamp('enabled_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'module_id']);
            $table->index(['tenant_id', 'enabled']);
        });

        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->json('value')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'key']);
        });

        Schema::create('tenant_brandings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('logo_path')->nullable();
            $table->string('primary_color')->default('#2563eb');
            $table->string('secondary_color')->default('#22a389');
            $table->string('accent_color')->default('#16a34a');
            $table->string('custom_domain')->nullable()->unique();
            $table->json('extra')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_brandings');
        Schema::dropIfExists('tenant_settings');
        Schema::dropIfExists('tenant_modules');
        Schema::dropIfExists('module_segment');
        Schema::dropIfExists('modules');

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['segment_id']);
            $table->dropIndex(['segment_id', 'status']);
            $table->dropColumn(['segment_id', 'slug', 'status', 'metadata']);
        });

        Schema::dropIfExists('segments');
    }
};
