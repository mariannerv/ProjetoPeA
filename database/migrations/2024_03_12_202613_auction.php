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
        Schema::create('auction', function (Blueprint $table) {
            $table->id();
            $table->string('highestBid');
            $table->string('highestBidderId');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('objectId');
            $table->string('sellerId');
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
