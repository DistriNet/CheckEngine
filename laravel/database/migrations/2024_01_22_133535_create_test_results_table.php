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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Enrollment::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Test::class);
            $table->foreign('test_id')->references('id')->on('test')->cascadeOnDelete();
            $table->string('outcome');
            $table->text('reason')->nullable();
            $table->unsignedInteger('duration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
