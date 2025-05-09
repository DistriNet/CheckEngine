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
        Schema::create('test', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->smallInteger('timeout')->nullable();
            $table->string('behaviour');
            $table->string('failure_severity');
            $table->unsignedBigInteger('parent');
            $table->foreign('parent')->references('id')->on('category')->cascadeOnDelete();
            $table->integer('execute_order');
            $table->text('test_function');
            $table->boolean('live')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
