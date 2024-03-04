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
         // Tabla de Grupos de Reembolso
         Schema::create('grupos_reembolsos', function (Blueprint $table) {
            $table->id();
            $table->string('consecutivo')->unique();
            $table->timestamps();
        });

        // Tabla de Facturas Agrupadas
        Schema::create('reembolsos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas');
            $table->foreignId('grupo_reembolso_id')->constrained('grupos_reembolsos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // Tabla de Facturas Agrupadas
         Schema::dropIfExists('reembolsos');

         // Tabla de Grupos de Reembolso
         Schema::dropIfExists('grupos_reembolsos');
    }
};
