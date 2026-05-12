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
        Schema::create('sell_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchaser_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->enum('status', ['draft','submitted','received','cancelled'])->default('draft');
            $table->integer('total_amount')->default(0);
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('received_at')->nullable();

            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
