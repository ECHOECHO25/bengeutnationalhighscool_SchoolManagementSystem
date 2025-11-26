<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\AuditRecord;
use App\Livewire\Admin\BackupRestore;
use App\Livewire\Admin\ClassroomRecord;
use App\Livewire\Admin\EditStudentData;
use App\Livewire\Admin\EnrollStudent;
use App\Livewire\Admin\ExamRecord;
use App\Livewire\Admin\Report;
use App\Livewire\Admin\SchoolYearRecord;
use App\Livewire\Admin\StudentRecord;
use App\Livewire\Admin\TeacherRecord;

use App\Livewire\Enrollment;
use App\Livewire\Guidance\GuidanceDashboard;

use App\Livewire\StudentDashboard;
use App\Livewire\Student\GradeRecord;

use App\Livewire\Teacher\ManageGrade;
use App\Livewire\Teacher\SubjectAttendance;
use App\Livewire\Teacher\SubjectGrade;
use App\Livewire\Teacher\TeacherStudent;
use App\Livewire\Teacher\TeacherSubjectRecord;


/*
|--------------------------------------------------------------------------
| Redirect Dashboard by Role
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'teacher'  => redirect()->route('teacher.dashboard'),
        'student'  => redirect()->route('student.dashboard'),
        'encoder'  => redirect()->route('encoder.dashboard'),
        'guidance' => redirect()->route('guidance.dashboard'),
        default    => abort(403, 'Unauthorized'),
    };
})->middleware(['auth'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('administrator')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/school-year', SchoolYearRecord::class)->name('admin.school-year');
        Route::get('/teacher', TeacherRecord::class)->name('admin.teacher');
        Route::get('/classroom', ClassroomRecord::class)->name('admin.classroom');
        Route::get('/enrollment', Enrollment::class)->name('admin.enrollment');
        Route::get('/enroll-student', EnrollStudent::class)->name('admin.enroll-student');
        Route::get('/student', StudentRecord::class)->name('admin.student');
        Route::get('/exam', ExamRecord::class)->name('admin.exam');
        Route::get('/grades', ExamRecord::class)->name('admin.grades');
        Route::get('/student-data/edit/{id}', EditStudentData::class)->name('admin.edit-student-data');
        Route::get('/audit_logs', AuditRecord::class)->name('admin.audit');
        Route::get('/backup-restore', BackupRestore::class)->name('admin.backup-restore');
        Route::get('/reports', Report::class)->name('admin.reports');
    });


/*
|--------------------------------------------------------------------------
| TEACHER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('teacher')
    ->middleware(['auth', 'verified', 'role:teacher'])
    ->group(function () {
        Route::get('/', AdminDashboard::class)->name('teacher.dashboard');
        Route::get('/student', TeacherStudent::class)->name('teacher.student');
        Route::get('/subject', TeacherSubjectRecord::class)->name('teacher.subject');
        Route::get('/attendances/{id}', SubjectAttendance::class)->name('teacher.subject.attendance');
        Route::get('/grades/{id}', SubjectGrade::class)->name('teacher.subject.grade');
        Route::get('/manage-grades/{id}', ManageGrade::class)->name('teacher.manage-grade');
    });


/*
|--------------------------------------------------------------------------
| GUIDANCE ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('guidance')
    ->middleware(['auth', 'verified', 'role:guidance'])
    ->group(function () {
        Route::get('/', GuidanceDashboard::class)->name('guidance.dashboard');
    });


/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('student')
    ->middleware(['auth', 'verified', 'role:student'])
    ->group(function () {
        Route::get('/', StudentDashboard::class)->name('student.dashboard');
        Route::get('/grade', GradeRecord::class)->name('student.grade');
    });


/*
|--------------------------------------------------------------------------
| ENCODER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('encoder')
    ->middleware(['auth', 'verified', 'role:encoder'])
    ->group(function () {
        Route::get('/', Enrollment::class)->name('encoder.dashboard');
        Route::get('/enroll-student', EnrollStudent::class)->name('encoder.enroll-student');
    });


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


require __DIR__ . '/auth.php';
