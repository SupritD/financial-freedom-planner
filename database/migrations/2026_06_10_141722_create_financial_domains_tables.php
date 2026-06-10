<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Income Domain
        Schema::create('income_source_types', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::create('income_entries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->ulid('source_type_id');
            $table->decimal('amount', 15, 4);
            $table->char('currency', 3)->default('INR');
            $table->decimal('base_currency_amount', 15, 4);
            $table->decimal('exchange_rate', 12, 6)->default(1.0);
            $table->date('income_date');
            $table->char('financial_year', 7); // e.g. 2025-26
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('source_type_id')->references('id')->on('income_source_types')->onDelete('cascade');
            
            // Partitioning by year was recommended, but for SQLite/MySQL prototyping we use standard tables with indexes
            $table->index(['user_id', 'income_date']);
        });

        // Expense Domain
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::create('expense_budgets', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->ulid('category_id');
            $table->decimal('amount', 15, 4);
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->decimal('alert_threshold', 5, 2)->default(80.00); // Alert when 80% used
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('expense_categories')->onDelete('cascade');
            $table->unique(['tenant_id', 'category_id', 'month', 'year']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->ulid('category_id');
            $table->string('title'); // Encrypted cast in model
            $table->decimal('amount', 15, 4); // Encrypted cast in model
            $table->char('currency', 3)->default('INR');
            $table->decimal('base_currency_amount', 15, 4);
            $table->decimal('exchange_rate', 12, 6)->default(1.0);
            $table->date('expense_date');
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('expense_categories')->onDelete('cascade');
            
            $table->index(['user_id', 'expense_date']);
            $table->index(['user_id', 'category_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_budgets');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('income_entries');
        Schema::dropIfExists('income_source_types');
    }
};
