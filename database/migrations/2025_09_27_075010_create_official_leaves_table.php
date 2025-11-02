<?php

use App\Models\Employee;
use App\Models\User;
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
        Schema::create('official_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class,'employee_id')->constrained('employees')->onDelete('cascade');
            $table->enum('type', ['annual', 'sick', 'maternity', 'paternity', 'unpaid', 'other']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_taken');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignIdFor(User::class,'approved_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index(['employee_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('official_leaves');
    }
};
