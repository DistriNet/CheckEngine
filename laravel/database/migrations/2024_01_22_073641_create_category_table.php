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
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent')->nullable();
            $table->foreign('parent')->references('id')->on('category')->cascadeOnDelete();
            $table->text('title');
            $table->text('description')->nullable();
            $table->integer('execute_order');
            $table->boolean('live')->default(true);
            $table->text('short_description')->nullable();
            $table->boolean('in_default')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
