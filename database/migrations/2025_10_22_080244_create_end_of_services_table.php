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
        Schema::create('end_of_services', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Employee::class,'employee_id')->constrained()->cascadeOnDelete();
            $table->date('joining_date');
            $table->date('leaving_date');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('gross_salary', 10, 2)->nullable();
            $table->integer('notice_period_days')->default(0);

            // Additions
            $table->decimal('incentive', 10, 2)->default(0);
            $table->decimal('rewards', 10, 2)->default(0);
            $table->decimal('other_additions', 10, 2)->default(0);

            // Deductions
            $table->decimal('cash_advance', 10, 2)->default(0);
            $table->decimal('petty_cash', 10, 2)->default(0);
            $table->decimal('fines', 10, 2)->default(0);
            $table->decimal('compensation_notice', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);

            $table->decimal('annual_leave_balance', 8, 2)->default(0);
            $table->decimal('net_pay', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('end_of_services');
    }
};
