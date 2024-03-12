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
        Schema::create('police_station', function (Blueprint $table) {
            $table->id();
            $table->string('morada');
            $table->string('codigo_postal');
            $table->string('localidade');
            $table->string('unidade');
            $table->string('telefone');
            $table->string('fax');
            $table->string('email');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};
