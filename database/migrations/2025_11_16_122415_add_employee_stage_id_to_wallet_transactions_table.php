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
        // Add employee_stage_id to wallet_transactions table
        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('wallet_transactions', 'employee_stage_id')) {
                $table->foreignId('employee_stage_id')
                    ->nullable()
                    ->after('wallet_id')
                    ->constrained('employee_stages')
                    ->onDelete('set null');
            }

            if (!Schema::hasColumn('wallet_transactions', 'type')) {
                $table->string('type')->nullable()->after('status');
            }

            if (!Schema::hasColumn('wallet_transactions', 'description')) {
                $table->text('description')->nullable()->after('type');
            }
        });

        // Add employee_stage_id to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'employee_stage_id')) {
                $table->foreignId('employee_stage_id')
                    ->nullable()
                    ->after('payment_account_id')
                    ->constrained('employee_stages')
                    ->onDelete('set null');
            }

            // Check if metadata exists and convert to JSON if needed
            if (Schema::hasColumn('transactions', 'metadata')) {
                // Change existing column to JSON type
                DB::statement('ALTER TABLE `transactions` MODIFY `metadata` JSON NULL');
            } else {
                // Add new JSON column
                $table->json('metadata')->nullable()->after('description');
            }
        });

        // Add transaction links to employee_stages table
        Schema::table('employee_stages', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_stages', 'transaction_id')) {
                $table->foreignId('transaction_id')
                    ->nullable()
                    ->after('done_by')
                    ->constrained('transactions')
                    ->onDelete('set null');
            }

            if (!Schema::hasColumn('employee_stages', 'wallet_transaction_id')) {
                $table->foreignId('wallet_transaction_id')
                    ->nullable()
                    ->after('transaction_id')
                    ->constrained('wallet_transactions')
                    ->onDelete('set null');
            }
        });

        // Add completion tracking to employees table
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'all_papers_completed')) {
                $table->boolean('all_papers_completed')->default(false)->after('status');
            }

            if (!Schema::hasColumn('employees', 'papers_completed_at')) {
                $table->timestamp('papers_completed_at')->nullable()->after('all_papers_completed');
            }
        });

        // Add indexes for better performance
//        Schema::table('wallet_transactions', function (Blueprint $table) {
//            $table->index('employee_stage_id');
//            $table->index(['status', 'type']);
//        });
//
//        Schema::table('transactions', function (Blueprint $table) {
//            $table->index('employee_stage_id');
//            $table->index(['type', 'status']);
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['employee_stage_id']);
            $table->dropColumn(['employee_stage_id', 'type', 'description']);
            $table->dropIndex(['employee_stage_id']);
            $table->dropIndex(['status', 'type']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['employee_stage_id']);
            $table->dropColumn(['employee_stage_id', 'metadata']);
            $table->dropIndex(['employee_stage_id']);
            $table->dropIndex(['type', 'status']);
        });

        Schema::table('employee_stages', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['wallet_transaction_id']);
            $table->dropColumn(['transaction_id', 'wallet_transaction_id']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['all_papers_completed', 'papers_completed_at']);
        });
    }
};
