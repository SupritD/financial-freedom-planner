<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('plan_id')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ledger_accounts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('account_type', ['income', 'expense', 'savings', 'investment', 'debt', 'equity']);
            $table->string('name');
            $table->decimal('current_balance', 15, 4)->default(0);
            $table->boolean('is_system')->default(false);
            $table->timestamps();
            
            $table->index(['tenant_id', 'user_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->ulid('account_id');
            $table->string('transaction_ref_type');
            $table->string('transaction_ref_id');
            $table->enum('account_type', ['income', 'expense', 'savings', 'investment', 'debt', 'equity']);
            $table->enum('entry_type', ['debit', 'credit']);
            $table->decimal('amount', 15, 4);
            $table->char('currency', 3)->default('INR');
            $table->decimal('balance_after', 15, 4);
            $table->timestamp('posted_at');
            
            // Append-only, no updated_at
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['tenant_id', 'user_id', 'posted_at', 'account_type'], 'idx_ledger_tenant_user_post_type');
            $table->index(['transaction_ref_type', 'transaction_ref_id'], 'idx_ledger_ref');
            $table->foreign('account_id')->references('id')->on('ledger_accounts')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action');
            $table->string('auditable_type');
            $table->string('auditable_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->char('row_hash', 64);
            $table->char('previous_hash', 64);
            $table->timestamp('chain_verified_at')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['tenant_id', 'user_id', 'action', 'created_at'], 'idx_audit_tenant_user_act');
            $table->index(['auditable_type', 'auditable_id'], 'idx_audit_model');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('ledger_accounts');
        Schema::dropIfExists('tenants');
    }
};
