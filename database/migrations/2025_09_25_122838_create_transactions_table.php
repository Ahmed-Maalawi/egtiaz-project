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

                $table->enum('type', ['stage_payment', 'salary_payment', 'eos_payment', 'refund', 'charge'])->default('stage_payment');
                $table->enum('method_type', ['debit', 'credit'])->default('stage_payment');

                $table->nullableMorphs('transactionable');

                $table->foreignIdFor(User::class, 'user_id')
                    ->nullable()->constrained('users')->nullOnDelete();

                $table->foreignId('payment_account_id')
                    ->constrained('payment_accounts')->onDelete('cascade');

                $table->foreignId('created_by')
                    ->constrained('users')->onDelete('cascade');

                $table->decimal('amount', 8, 2);
                $table->decimal('from_balance_before', 8, 2);
                $table->decimal('from_balance_after', 8, 2);

                $table->enum('status', ['pending', 'completed', 'failed', 'refund', 'canceled'])
                    ->default('pending');

                $table->string('description')->nullable();

                $table->softDeletes();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();

                // Useful indexes
                $table->index(['type', 'status']);
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
