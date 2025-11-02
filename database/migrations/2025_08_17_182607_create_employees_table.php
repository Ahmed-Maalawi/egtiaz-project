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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('salary', 8,2)->nullable();
            $table->text('address')->nullable();
            $table->string('image')->nullable();
            $table->string('passport_image')->nullable();
            $table->string('passport_number')->nullable();
            $table->enum('gender',['m','f'])->default('m');
            $table->enum('status',['active','inactive'])->default('active');
            $table->foreignId('company_id')->constrained('companies','id')->cascadeOnDelete();
            $table->foreignId('iqama_type_id')->constrained('iqama_types','id')->cascadeOnDelete();
            $table->date('expired_date')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
