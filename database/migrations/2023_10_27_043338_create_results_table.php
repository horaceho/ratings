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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('player');
            $table->string('opponent');
            $table->string('winner');
            $table->decimal('rating', 10, 3)->nullable();
            $table->decimal('update', 10, 3)->nullable();
            $table->string('slot')->default('s0');
            $table->json('meta')->nullable();
            $table->json('info')->nullable();
            $table->foreignId('entrant_id')->nullable();
            $table->foreignId('opposer_id')->nullable();
            $table->foreignId('record_id')->nullable();
            $table->foreignId('trial_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
