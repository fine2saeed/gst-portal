<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('hs_code')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('gst_rate', 5, 2)->default(18.00);
            $table->enum('tax_type', ['standard', 'zero_rated', 'exempt'])->default('standard');
            $table->string('unit')->default('Unit'); // Kg, Liter, Unit, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
