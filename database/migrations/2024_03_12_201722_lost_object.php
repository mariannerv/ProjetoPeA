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
        Schema::create('lostObject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locationId')->constrained('location');
            $table->foreignId('owner_id')->constrained('users'); 
            $table->foreignId('category_id')->constrained('categories'); 
            $table->string('description');
            $table->date('date_found');
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
