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
            $table->bigIncrements('id_rental_detail');

            $table->unsignedInteger('id_rental')->index()->nullable();
            $table->foreign('id_rental')
                ->references('id_rental')
                ->on('rentals')
                ->nullOnDelete();

            $table->unsignedSmallInteger('id_item')->index()->nullable();
            $table->foreign('id_item')
                ->references('id_item')
                ->on('items')
                ->nullOnDelete();

            $table->integer('quantity')->default(1);
            $table->boolean('is_returned')->default(false);
            $table->decimal('sub_total', 15, 2)->nullable();
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
