<?php

use App\Models\Employee;
use App\Models\EmployeeStage;
use App\Models\PaymentAccount;
use App\Models\User;
use App\Models\Wallet;
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
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->uuid('transaction_id')->unique();

                // Payment accounts
                $table->foreignId('from_payment_account_id')->constrained('payment_accounts')->onDelete('cascade');
                $table->foreignId('to_wallet_id')->nullable()->constrained('wallets')->onDelete('cascade');

                // Transaction type determination
                $table->enum('type', ['stage_payment', 'salary_payment', 'refund', 'charge'])->default('stage_payment');

                // Polymorphic relationships (only one will be set)
                $table->morphs('transactionable');


                // Employee and user references
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('payment_account_id')->constrained('payment_accounts')->onDelete('cascade');
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

                // Transaction amounts and balances
                $table->decimal('amount', 8, 2);
                $table->decimal('from_balance_before', 8, 2);
                $table->decimal('from_balance_after', 8, 2);

                // Transaction metadata
                $table->enum('status', ['pending', 'completed', 'failed', 'refund', 'canceled'])->default('pending');
                $table->string('description')->nullable();

                // Soft delete & timestamps
                $table->softDeletes();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();

                // Indexing for performance
                $table->index(['transactionable_id', 'transactionable_type']);
                $table->index(['type', 'status']);
                $table->index(['employee_id', 'status']);
                $table->index(['user_id', 'created_at']);
                $table->index('transaction_id');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
