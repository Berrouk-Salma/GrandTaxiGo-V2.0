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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users');
            $table->foreignId('reviewed_id')->constrained('users');
            $table->foreignId('trip_id')->constrained('trips');
            $table->integer('rating')->unsigned()->comment('1-5 stars');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Prevent duplicate reviews for same trip
            $table->unique(['reviewer_id', 'trip_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
