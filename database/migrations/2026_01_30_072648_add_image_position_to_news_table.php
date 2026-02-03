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
    Schema::table('news', function (Blueprint $table) {
        // เก็บค่า 0-100 (Default 50 คือตรงกลาง)
        $table->integer('image_position')->default(50)->after('image_url');
    });
}

public function down(): void
{
    Schema::table('news', function (Blueprint $table) {
        $table->dropColumn('image_position');
    });
}
};
