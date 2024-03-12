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
        Schema::create('categoria', function (Blueprint $table) {
            $table->id();
            $table->string('categoria');
            $table->string('marca');
            $table->string('modelo');
            $table->string('numeroDeSerie');
            $table->string('cor');
            $table->timestamps();

        });
    }
    public function down(): void
    {
        //
    }
};
