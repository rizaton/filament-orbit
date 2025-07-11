<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->smallIncrements('id_item');
            $table->unsignedTinyInteger('id_category')->index()->nullable();
            $table->foreign('id_category')
                ->references('id_category')
                ->on('categories')
                ->nullOnDelete();
            $table->string('name', 100);
            $table->char('slug', 32)->unique();
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->binary('image')->nullable();
            $table->boolean('is_available')->default(false);
            $table->decimal('rent_price', 15, 2);
            $table->timestamps();
        });
        // DB::statement("ALTER TABLE items ADD image MEDIUMBLOB NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
