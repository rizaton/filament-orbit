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
        Schema::create('rental_details', function (Blueprint $table) {
            $table->id('id_rental_detail');
            $table->foreignId('id_rental')->constrained(
                table: 'rentals',
                column: 'id_rental'
            )->cascadeOnDelete();
            $table->foreignId('id_item')->constrained(
                table: 'items',
                column: 'id_item'
            )->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->boolean('is_returned')->default(false);
            $table->decimal('sub_total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_details');
    }
};
