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
        Schema::create('records', function (Blueprint $table) {
            $table->id()->startingValue(1_000_001);
            $table->date('date');
            $table->string('black');
            $table->string('white');
            $table->string('winner')->nullable();
            $table->string('result')->nullable();
            $table->integer('handicap')->default(0);
            $table->string('organization');
            $table->string('match');
            $table->string('group')->nullable();
            $table->string('round');
            $table->string('short')->nullable();
            $table->string('link')->nullable();
            $table->string('team')->nullable();
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
        Schema::dropIfExists('records');
    }
};
