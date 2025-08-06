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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->decimal('monto', 12, 2);
            $table->timestamp('fecha');
            $table->unsignedSmallInteger('metodo_pago_id');
            $table->foreign('metodo_pago_id')->references('id')->on('metodos_pago');
            $table->unsignedSmallInteger('estado_pago_id');
            $table->foreign('estado_pago_id')->references('id')->on('estados_pago');
            $table->string('referencia_externa', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
}; 