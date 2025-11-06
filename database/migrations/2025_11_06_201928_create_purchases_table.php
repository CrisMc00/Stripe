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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('payment_intent_id')->unique();
            $table->string('session_id')->unique();
            $table->integer('amount'); // En centavos
            $table->string('currency', 3)->default('mxn');
            $table->string('customer_email');
            $table->string('customer_name')->nullable();
            $table->string('status')->default('completed'); // completed, refunded, partial_refund
            $table->json('products'); // Array de productos comprados
            $table->text('refund_reason')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
