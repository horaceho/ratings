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
        Schema::create('trials', function (Blueprint $table) {
            $table->id()->startingValue(1_000_001);
            $table->string('algorithm');
            $table->string('organization')->nullable();
            $table->date('from')->nullable();
            $table->date('till')->nullable();
            $table->integer('handicap')->default(2);
            $table->string('rank_lo')->default('30k');;
            $table->string('rank_hi')->default('9d');;
            $table->json('meta')->nullable();
            $table->json('info')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('trials');
    }
};
