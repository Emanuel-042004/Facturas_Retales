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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->enum('type', ['Factura electrónica', 'Nota de crédito electrónica', 'Reembolso', 'Legalizacion'])->nullable();
            $table->string('folio', 255)->nullable();
            $table->string('prefix')->nullable();
            $table->string('issuer_nit', 20)->nullable();
            $table->string('issuer_name')->nullable();
            $table->string('cude')->nullable();
            $table->date('arrival_date')->nullable();
            $table->enum('location',['Centro','Octava','Lopez','Alameda','Acopi','Jamundi','Pondaje'])->nullable();
            $table->enum('area',['Compras','Financiera','Logistica','Mantenimiento','Tecnologia'])->nullable();
            $table->text('note',)->nullable();
            $table->enum('status',['Pendiente', 'Entregada', 'Recibida', 'Rechazada','Pendiente a Causar', 'Finalizada']);
            $table->date('delivery_date')->nullable();
            $table->date('received_date')->nullable();
            $table->string('delivered_by')->nullable();
            $table->string('received_by')->nullable();
            $table->string('anexo1')->nullable();
            $table->string('anexo2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
