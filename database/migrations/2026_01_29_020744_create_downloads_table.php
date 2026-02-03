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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // เช่น Client Full Installer
            $table->string('link');
            $table->string('version')->nullable();
            $table->string('file_size')->nullable();
            $table->enum('type', ['Client', 'Patch', 'Tool'])->default('Client');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
