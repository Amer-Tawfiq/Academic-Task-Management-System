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
        // database/migrations/xxxx_create_dean_reports_table.php
        Schema::create('dean_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // نوع التقرير
            $table->foreignId('teacher_id')->constrained('users'); // المعلم
            $table->date('report_date'); // التاريخ
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'archived'])->default('pending');
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->string('file_path')->nullable(); // ملف التقرير
            $table->foreignId('created_by')->constrained('users'); // العميد/رئيس القسم
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dean_reports');
    }
};
