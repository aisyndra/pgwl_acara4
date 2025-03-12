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
        Schema::create('polygon', function (Blueprint $table) {
            $table->id();
            $table->geometry('geom'); //ditambah ini
            $table->string('name'); //ditambah ini
            $table->text('description'); //ditambah ini
            $table->string('image')-> nullable(); //ditambah ini dengan nullable agar boleh kosong
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polygon');
    }
};
