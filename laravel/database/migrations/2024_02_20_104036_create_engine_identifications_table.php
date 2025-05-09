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
        Schema::create('engine_identifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Enrollment::class)->constrained()->cascadeOnDelete();
            $table->integer('rank');
            $table->integer('script_version');
            $table->boolean('is_engine');
            $table->string('version');
            $table->integer('votes_for');
            $table->integer('votes_against');
            $table->integer('votes_net');
            $table->integer('votes_total');
            $table->string('release_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engine_identifications');
    }
};
