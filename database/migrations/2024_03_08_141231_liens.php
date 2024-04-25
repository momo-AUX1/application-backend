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
        Schema::create('liens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user')->nullable();
            $table->foreignId('insect')->nullable();
            $table->integer('view history')->nullable();
            $table->string('lat long')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
