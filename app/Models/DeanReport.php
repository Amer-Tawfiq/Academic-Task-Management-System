<?php

// app/Models/DeanReport.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeanReport extends Model
{
    protected $fillable = [
        'report_type',
        'teacher_id',
        'report_date',
        'status',
        'notes',
        'file_path',
        'created_by',
        'reviewed_by',
        'reviewed_at'
    ];

    // أنواع التقارير
    const TYPE_TEACHER_EVALUATION = 'teacher_evaluation';
    const TYPE_COURSE_EVALUATION = 'course_evaluation';
    const TYPE_ATTENDANCE_REPORT = 'attendance_report';
    const TYPE_PERFORMANCE_REVIEW = 'performance_review';
    const TYPE_ACADEMIC_ADVICE = 'academic_advice';
    const TYPE_OTHER = 'other';

    // الحالات
    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ARCHIVED = 'archived';

    // العلاقات
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // دالة للحصول على أسماء الأنواع
    public static function getReportTypes()
    {
        return [
            self::TYPE_TEACHER_EVALUATION => 'تقييم أداء المعلم',
            self::TYPE_COURSE_EVALUATION => 'تقييم المقرر الدراسي',
            self::TYPE_ATTENDANCE_REPORT => 'تقرير حضور المعلم',
            self::TYPE_PERFORMANCE_REVIEW => 'مراجعة الأداء',
            self::TYPE_ACADEMIC_ADVICE => 'توصية أكاديمية',
            self::TYPE_OTHER => 'أخرى'
        ];
    }

    // دالة للحصول على أسماء الحالات
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'قيد الانتظار',
            self::STATUS_UNDER_REVIEW => 'قيد المراجعة',
            self::STATUS_APPROVED => 'معتمد',
            self::STATUS_REJECTED => 'مرفوض',
            self::STATUS_ARCHIVED => 'مؤرشف'
        ];
    }
}
