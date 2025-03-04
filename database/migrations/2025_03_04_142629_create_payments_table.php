<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('transaction_id')->unique();
            $table->string('status');
            $table->json('payment_details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
