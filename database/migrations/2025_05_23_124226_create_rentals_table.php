<?php

use App\Models\User;
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
            $table->integerIncrements('id_rental');
            $table->unsignedInteger('id_user')->index()->nullable();
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->nullOnDelete();
            $table->enum('status', [
                'pending',
                'approved',
                'rented',
                'rejected',
                'returning',
                'returned',
                'late'
            ])->default('pending');
            $table->decimal('down_payment', 15, 2)->nullable();
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
