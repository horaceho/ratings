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
        Schema::create('players', function (Blueprint $table) {
            $table->id()->startingValue(1_000_001);
            $table->string('code')->nullable();
            $table->string('name')->index();
            $table->string('alias')->nullable();
            $table->string('other')->nullable();
            $table->string('init')->nullable();
            $table->string('rank')->nullable();
            $table->decimal('rating_init', 10, 3)->default(0.0);
            $table->decimal('rating_rank', 10, 3)->default(0.0);
            $table->decimal('gor', 10, 3)->default(0.0);
            $table->decimal('s0', 10, 3)->default(0.0);
            $table->decimal('s1', 10, 3)->default(0.0);
            $table->decimal('s2', 10, 3)->default(0.0);
            $table->decimal('s3', 10, 3)->default(0.0);
            $table->decimal('s4', 10, 3)->default(0.0);
            $table->decimal('s5', 10, 3)->default(0.0);
            $table->decimal('s6', 10, 3)->default(0.0);
            $table->decimal('s7', 10, 3)->default(0.0);
            $table->decimal('s8', 10, 3)->default(0.0);
            $table->decimal('s9', 10, 3)->default(0.0);
            $table->string('remark')->nullable();
            $table->json('meta')->nullable();
            $table->json('info')->nullable();
            $table->foreignId('upload_id')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
