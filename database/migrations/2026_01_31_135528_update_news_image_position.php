<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('news', function (Blueprint $table) {
        // เปลี่ยนชื่ออันเดิมเป็น Y (แนวตั้ง)
        $table->renameColumn('image_position', 'image_position_y');
        // เพิ่มอันใหม่เป็น X (แนวนอน) ค่าเดิม 50 (ตรงกลาง)
        $table->integer('image_position_x')->default(50)->after('image_url');
    });
}

public function down(): void
{
    Schema::table('news', function (Blueprint $table) {
        $table->renameColumn('image_position_y', 'image_position');
        $table->dropColumn('image_position_x');
    });
}
};
