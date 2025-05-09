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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->text('type')->nullable();
            $table->text('vendor')->nullable();
            $table->text('model')->nullable();
            $table->text('year')->nullable();
            $table->text('version')->nullable();
            $table->text('notes')->nullable();
            $table->string('token')->unique();
            $table->string('url');
            $table->text('user_agent_header')->nullable();
            $table->text('user_agent_js')->nullable();
            $table->json('html_fingerprint')->nullable();
            $table->json('api_fingerprint')->nullable();
            $table->string('update_status')->nullable();
            $table->unsignedBigInteger('updated_from')->nullable();
            $table->foreign('updated_from')->references('id')->on('enrollments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
