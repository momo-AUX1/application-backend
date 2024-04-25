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
        Schema::create('insects', function (Blueprint $table) {
            $table->id();
            $table->string('nom_commun')->nullable();
            $table->string('nom_scientifique')->nullable();
          	$table->string('photo')->nullable();
            $table->string('famille')->nullable();
            $table->string('taille')->nullable();
            $table->integer('poids')->nullable();
            $table->string('couleur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insects');
    }
};
