<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('tutores', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->timestamps();
    });

    // Insertamos los maestros precargados directamente
    DB::table('tutores')->insert([
        ['nombre' => 'Mtro. Carlos Francisco Dominguez Dominguez'],
        ['nombre' => 'Mtra. Eloisa Ruiz Gonzalez'],
        ['nombre' => 'Mtra. Jazmin Morales Toxqui'],
        ['nombre' => 'Mtro. Hector Guzman Coutiño']
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutores');
    }
};
