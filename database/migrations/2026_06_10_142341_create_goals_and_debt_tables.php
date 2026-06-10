<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Goals Domain
        Schema::create('financial_goals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->decimal('target_amount', 15, 4);
            $table->decimal('current_amount', 15, 4)->default(0);
            $table->char('currency', 3)->default('INR');
            $table->date('deadline')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::create('goal_contributions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->ulid('goal_id');
            $table->decimal('amount', 15, 4);
            $table->char('currency', 3)->default('INR');
            $table->date('contribution_date');
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('goal_id')->references('id')->on('financial_goals')->onDelete('cascade');
        });

        // Debt Domain
        Schema::create('debt_accounts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['credit_card', 'personal_loan', 'mortgage', 'student_loan', 'other']);
            $table->decimal('principal_amount', 15, 4);
            $table->decimal('current_balance', 15, 4);
            $table->char('currency', 3)->default('INR');
            $table->decimal('interest_rate', 5, 2); // Annual interest rate percentage
            $table->decimal('minimum_payment', 15, 4)->nullable();
            $table->tinyInteger('due_date_day')->nullable(); // e.g. 15th of every month
            $table->boolean('is_paid_off')->default(false);
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::create('debt_amortization_schedules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id');
            $table->ulid('debt_account_id');
            $table->integer('payment_number');
            $table->date('payment_date');
            $table->decimal('principal_payment', 15, 4);
            $table->decimal('interest_payment', 15, 4);
            $table->decimal('remaining_balance', 15, 4);
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('debt_account_id')->references('id')->on('debt_accounts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debt_amortization_schedules');
        Schema::dropIfExists('debt_accounts');
        Schema::dropIfExists('goal_contributions');
        Schema::dropIfExists('financial_goals');
    }
};
