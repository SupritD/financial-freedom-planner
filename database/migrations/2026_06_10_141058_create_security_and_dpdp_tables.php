<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('privacy_policy_versions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('version', 20);
            $table->date('effective_date');
            $table->text('summary_of_changes');
            $table->string('full_text_url', 500);
            $table->timestamps();
        });

        Schema::create('consent_records', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('consent_type', ['data_processing', 'marketing', 'analytics', 'third_party']);
            $table->string('version', 20);
            $table->timestamp('consented_at')->useCurrent();
            $table->string('ip_address', 45);
            $table->timestamp('withdrawn_at')->nullable();
            $table->timestamps();
        });

        Schema::create('data_access_requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('request_type', ['access', 'correction', 'erasure', 'portability']);
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('fulfilled_at')->nullable();
            $table->json('fulfillment_data')->nullable();
            $table->timestamps();
        });

        Schema::create('trusted_devices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_fingerprint', 255);
            $table->string('device_name');
            $table->timestamp('first_seen')->useCurrent();
            $table->timestamp('last_seen')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('trusted_at')->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->timestamps();
        });

        Schema::create('login_events', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->char('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->boolean('success');
            $table->string('failure_reason', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('idempotency_keys', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('key', 255);
            $table->string('endpoint', 200);
            $table->smallInteger('response_status')->nullable();
            $table->json('response_body')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            
            $table->unique(['user_id', 'key']);
            $table->index('expires_at');
        });

        Schema::table('users', function (Blueprint $table) {
            // Need nullable initially because we may have existing rows (even though we're doing fresh, good practice)
            $table->uuid('tenant_id')->nullable();
            $table->ulid('privacy_policy_version_id')->nullable();
            $table->char('data_residency_region', 10)->default('IN');
            $table->timestamp('account_locked_until')->nullable();
            $table->tinyInteger('failed_login_count')->default(0);
            
            // Note: tenant_id constraint is not strictly enforced via FK yet because tenants is UUID and users is ID
            // but we can enforce it.
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('privacy_policy_version_id')->references('id')->on('privacy_policy_versions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['privacy_policy_version_id']);
            $table->dropColumn(['tenant_id', 'privacy_policy_version_id', 'data_residency_region', 'account_locked_until', 'failed_login_count']);
        });

        Schema::dropIfExists('idempotency_keys');
        Schema::dropIfExists('login_events');
        Schema::dropIfExists('trusted_devices');
        Schema::dropIfExists('data_access_requests');
        Schema::dropIfExists('consent_records');
        Schema::dropIfExists('privacy_policy_versions');
    }
};
