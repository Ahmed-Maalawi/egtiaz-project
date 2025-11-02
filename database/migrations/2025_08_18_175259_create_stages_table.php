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
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iqama_type_id')->constrained('iqama_types','id')->cascadeOnDelete();
            $table->json('name');
            $table->json('description')->nullable();
            $table->smallInteger('order');
            $table->float('price')->default(0.0);
            $table->integer('estimated_time_in_days')->nullable();
            $table->string('image')->nullable();
            $table->json('options')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
