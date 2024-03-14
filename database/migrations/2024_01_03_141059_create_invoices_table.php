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
            $table->enum('type', ['Factura electrónica', 'Nota de crédito electrónica', 'Reembolso', 'Legalizacion'])->nullable();
            $table->string('folio', 255)->nullable();
            $table->string('prefix')->nullable();
            $table->string('issuer_nit', 20)->nullable();
            $table->string('issuer_name')->nullable();
            $table->string('cude')->nullable();
            $table->date('issue_date')->nullable();
            $table->enum('subtype', ['Rechazada','Adjuntada','Aprobada','FIN/Rechazada'])->nullable();
            $table->date('arrival_date')->nullable();
            $table->enum('location',['Centro','Octava','Lopez','Alameda','Acopi','Jamundi','Pondaje'])->nullable();
            $table->enum('area',['Compras','Financiera','Logistica','Mantenimiento','Tecnologia'])->nullable();
            $table->text('note',)->nullable();
            $table->enum('status',['Pendiente', 'Cargada', 'Entregada', 'Recibida', 'Rechazada','Causada', 'Pagada','Finalizada']);
            $table->date('delivery_date')->nullable();
            $table->date('received_date')->nullable();
            $table->string('delivered_by')->nullable();
            $table->string('received_by')->nullable();
            $table->string('anexo1')->nullable();
            $table->string('anexo2')->nullable();
            $table->string('anexo3')->nullable();
            $table->string('anexo4')->nullable();
            $table->string('anexo5')->nullable();
            $table->string('anexo6')->nullable();
            $table->string('costo1')->nullable();
            $table->string('costo2')->nullable();
            $table->string('costo3')->nullable();
            $table->string('costo4')->nullable();
            $table->string('costo5')->nullable();
            $table->string('causacion1')->nullable();
            $table->string('causacion2')->nullable();
            $table->string('causacion3')->nullable();
            $table->string('causacion4')->nullable();
            $table->string('causacion5')->nullable();
            $table->string('causacion6')->nullable();
            $table->string('comprobante1')->nullable();
            $table->string('comprobante2')->nullable();
            $table->string('comprobante3')->nullable();

            $table->foreignId('reembolso_id')->nullable()->constrained('reembolsos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['reembolso_id']);
            $table->dropColumn('reembolso_id');
        });
    }
};
