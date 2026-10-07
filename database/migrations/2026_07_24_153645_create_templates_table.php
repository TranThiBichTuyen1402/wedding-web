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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();

            // Thông tin mẫu thiệp
            $table->string('name');
            $table->boolean('is_vip')->default(false);
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Hình ảnh đại diện mẫu
            $table->string('thumbnail')->nullable();

            // File Blade hiển thị mẫu
            // Ví dụ: client.templates.template_1
            $table->string('view');

            // Trạng thái mẫu
            $table->boolean('is_active')->default(true);

            // Thứ tự hiển thị
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};