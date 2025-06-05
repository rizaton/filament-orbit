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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id('id_rental');
            $table->foreignId('id_user')->nullable()->constrained(
                table: 'users',
                column: 'id_user'
            )->nullOnDelete();
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'returned',
                'late'
            ])->default('pending');
            $table->decimal('down_payment', 15, 2);
            $table->date('rent_date');
            $table->date('return_date');
            $table->date('late_date')->nullable();
            $table->decimal('late_fees', 15, 2)->nullable();
            $table->decimal('total_fees', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
