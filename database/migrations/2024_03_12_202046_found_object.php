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
        Schema::create('foundObject', function (Blueprint $table) {
            $table->id();
            $table->string('categoryId');
            $table->foreignId('locationId')->constrained('location');
            $table->string('description');
            $table->string('value');
            $table->date('dateFound');
            $table->date('dateRegistered');
            $table->date('deadlineForAuction');
            $table->string('objectId');
            $table->string('finderId');
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
