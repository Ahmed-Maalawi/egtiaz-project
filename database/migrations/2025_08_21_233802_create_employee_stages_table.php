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
        Schema::create('employee_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees','id')->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('stages','id')->cascadeOnDelete();
            $table->enum('status',['pending','in_progress','completed'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('done_by')->nullable()->constrained('users','id')->nullOnDelete();
            $table->timestamp('expired_at')->nullable();
            $table->json('options')->nullable();
            $table->boolean('currently_type')->default(true);
            $table->enum('payment_status', ['pending', 'paid'])->default('pending')->after('status');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->decimal('amount_paid', 10, 2)->nullable()->after('paid_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_stages');
    }
};
