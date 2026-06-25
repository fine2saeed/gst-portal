<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('ntn')->nullable();
            $table->string('strn')->nullable();
            $table->string('province'); // FBR, SRB, PRA, KPRA, BRA
            $table->decimal('default_gst_rate', 5, 2)->default(18.00);
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('invoice_prefix')->default('INV');
            $table->integer('invoice_counter')->default(1);
            $table->boolean('profile_complete')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
