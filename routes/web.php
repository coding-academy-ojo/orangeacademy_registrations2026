<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Student\RegistrationController;
use App\Http\Controllers\Admin;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    $academies = \App\Models\Academy::with([
        'cohorts' => function ($q) {
            $q->where('status', 'active');
        }
    ])->get();
    return view('welcome', compact('academies'));
});

Route::view('/terms', 'terms')->name('terms');

// ── Student Auth Routes ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/student/verify-email', [AuthController::class, 'showVerifyEmail'])->name('student.verify-email');
    Route::post('/student/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/student/verify-email/resend', [AuthController::class, 'resendVerificationCode'])->name('student.verify-email.resend');

    Route::get('/student/verify-phone', [AuthController::class, 'showVerifyPhone'])->name('student.verify-phone');
    Route::post('/student/verify-phone', [AuthController::class, 'verifyPhone']);
    Route::post('/student/verify-phone/resend', [AuthController::class, 'resendPhoneCode'])->name('student.verify-phone.resend');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin Auth Routes ──
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// Student routes
Route::middleware('auth')->prefix('student')->group(function () {
    Route::get('/dashboard', [RegistrationController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/registration/step/{step}', [RegistrationController::class, 'step'])->name('student.step');
    Route::post('/registration/profile', [RegistrationController::class, 'saveProfile'])->name('student.save.profile');
    Route::post('/registration/documents', [RegistrationController::class, 'saveDocuments'])->name('student.save.documents');
    Route::post('/registration/coursat', [RegistrationController::class, 'saveCoursat'])->name('student.save.coursat');
    Route::post('/registration/enrollment', [RegistrationController::class, 'saveEnrollment'])->name('student.save.enrollment');
    Route::get('/assessments', [RegistrationController::class, 'assessments'])->name('student.assessments');
    Route::get('/assessments/{assessment}/take', [RegistrationController::class, 'takeAssessment'])->name('student.assessment.take');
    Route::post('/assessments/{assessment}/submit', [RegistrationController::class, 'submitAssessment'])->name('student.assessment.submit');
    Route::post('/registration/questionnaire', [RegistrationController::class, 'saveQuestionnaire'])->name('student.save.questionnaire');
    Route::post('/registration/submit', [RegistrationController::class, 'submitRegistration'])->name('student.submit');
    Route::get('/registration/success', [RegistrationController::class, 'registrationSuccess'])->name('student.registration.success');
});

// Admin routes
Route::middleware(['auth:admin', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // Admins (Super Admin Only)
    Route::middleware('can:manage-admins')->group(function () {
        Route::resource('admins', Admin\AdminController::class)->names([
            'index' => 'admin.admins.index',
            'create' => 'admin.admins.create',
            'store' => 'admin.admins.store',
            'show' => 'admin.admins.show',
            'edit' => 'admin.admins.edit',
            'update' => 'admin.admins.update',
            'destroy' => 'admin.admins.destroy',
        ]);

        // Activity Logs
        Route::get('/activities', [Admin\ActivityController::class, 'index'])->name('admin.activities');
        Route::get('/activities/{activity}', [Admin\ActivityController::class, 'show'])->name('admin.activities.show');
        Route::get('/user-progress', [Admin\ActivityController::class, 'userProgress'])->name('admin.user-progress');
        Route::get('/user-progress/{user}', [Admin\ActivityController::class, 'userDetail'])->name('admin.user-progress.detail');
        Route::get('/missed-data', [Admin\ActivityController::class, 'missedData'])->name('admin.missed-data');

        // Excel Export
        Route::get('/export-excel', [Admin\DashboardController::class, 'exportExcel'])->name('admin.export-excel');

        // PDF Report
        Route::get('/export-pdf', [Admin\DashboardController::class, 'exportPdf'])->name('admin.export-pdf');
    });

    // Operations (Coordinators + Managers + Super Admin)
    Route::middleware('can:manage-operations')->group(function () {
        // Users
        Route::get('/users', [Admin\UserController::class, 'index'])->name('admin.users');
        Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('admin.users.show');
        Route::post('/users/{user}/toggle', [Admin\UserController::class, 'toggleActive'])->name('admin.users.toggle');
        Route::post('/users/{user}/filtration', [Admin\UserController::class, 'updateFiltration'])->name('admin.users.filtration');

        // Enrollments
        Route::get('/enrollments', [Admin\EnrollmentController::class, 'index'])->name('admin.enrollments');
        Route::post('/enrollments/{enrollment}/status', [Admin\EnrollmentController::class, 'updateStatus'])->name('admin.enrollments.status');

        // Academies
        Route::get('/academies', [Admin\AcademyController::class, 'index'])->name('admin.academies');
        Route::get('/academies/create', [Admin\AcademyController::class, 'create'])->name('admin.academies.create');
        Route::post('/academies', [Admin\AcademyController::class, 'store'])->name('admin.academies.store');
        Route::get('/academies/{academy}/edit', [Admin\AcademyController::class, 'edit'])->name('admin.academies.edit');
        Route::put('/academies/{academy}', [Admin\AcademyController::class, 'update'])->name('admin.academies.update');
        Route::delete('/academies/{academy}', [Admin\AcademyController::class, 'destroy'])->name('admin.academies.destroy');

        // Cohorts
        Route::get('/cohorts', [Admin\CohortController::class, 'index'])->name('admin.cohorts');
        Route::get('/cohorts/create', [Admin\CohortController::class, 'create'])->name('admin.cohorts.create');
        Route::post('/cohorts', [Admin\CohortController::class, 'store'])->name('admin.cohorts.store');
        Route::get('/cohorts/{cohort}/edit', [Admin\CohortController::class, 'edit'])->name('admin.cohorts.edit');
        Route::put('/cohorts/{cohort}', [Admin\CohortController::class, 'update'])->name('admin.cohorts.update');
        Route::delete('/cohorts/{cohort}', [Admin\CohortController::class, 'destroy'])->name('admin.cohorts.destroy');

        // Documents
        Route::resource('document_requirements', Admin\DocumentRequirementController::class)->names([
            'index' => 'admin.document_requirements.index',
            'create' => 'admin.document_requirements.create',
            'store' => 'admin.document_requirements.store',
            'show' => 'admin.document_requirements.show',
            'edit' => 'admin.document_requirements.edit',
            'update' => 'admin.document_requirements.update',
            'destroy' => 'admin.document_requirements.destroy',
        ]);
        Route::get('/documents', [Admin\DocumentController::class, 'index'])->name('admin.documents');
        Route::post('/documents/{document}/verify', [Admin\DocumentController::class, 'verify'])->name('admin.documents.verify');
        Route::post('/documents/{document}/unverify', [Admin\DocumentController::class, 'unverify'])->name('admin.documents.unverify');
    });

    // Interviews & Assessments (Job Coaches + Managers + Super Admin)
    Route::middleware('can:manage-interviews-assessments')->group(function () {
        // Assessments
        Route::get('/assessments', [Admin\AssessmentController::class, 'index'])->name('admin.assessments');
        Route::get('/assessments/create', [Admin\AssessmentController::class, 'create'])->name('admin.assessments.create');
        Route::post('/assessments', [Admin\AssessmentController::class, 'store'])->name('admin.assessments.store');
        Route::get('/assessments/{assessment}/edit', [Admin\AssessmentController::class, 'edit'])->name('admin.assessments.edit');
        Route::put('/assessments/{assessment}', [Admin\AssessmentController::class, 'update'])->name('admin.assessments.update');
        Route::post('/assessments/{assessment}/publish', [Admin\AssessmentController::class, 'togglePublish'])->name('admin.assessments.publish');
        Route::delete('/assessments/{assessment}', [Admin\AssessmentController::class, 'destroy'])->name('admin.assessments.destroy');
        Route::get('/assessments/{assessment}/submissions', [Admin\AssessmentController::class, 'submissions'])->name('admin.assessments.submissions');
        Route::get('/submissions/{submission}/grade', [Admin\AssessmentController::class, 'showGrade'])->name('admin.submissions.grade');
        Route::post('/submissions/{submission}/grade', [Admin\AssessmentController::class, 'saveGrade'])->name('admin.submissions.grade.save');

        // Questionnaires
        Route::get('/questionnaires', [Admin\QuestionnaireController::class, 'index'])->name('admin.questionnaires');
        Route::get('/questionnaires/create', [Admin\QuestionnaireController::class, 'create'])->name('admin.questionnaires.create');
        Route::post('/questionnaires', [Admin\QuestionnaireController::class, 'store'])->name('admin.questionnaires.store');
        Route::get('/questionnaires/{questionnaire}/edit', [Admin\QuestionnaireController::class, 'edit'])->name('admin.questionnaires.edit');
        Route::put('/questionnaires/{questionnaire}', [Admin\QuestionnaireController::class, 'update'])->name('admin.questionnaires.update');
        Route::delete('/questionnaires/{questionnaire}', [Admin\QuestionnaireController::class, 'destroy'])->name('admin.questionnaires.destroy');
        Route::get('/questionnaires/{questionnaire}/answers', [Admin\QuestionnaireController::class, 'answers'])->name('admin.questionnaires.answers');

        // Interviews
        Route::get('/interviews', [Admin\InterviewController::class, 'index'])->name('admin.interviews.index');
        Route::post('/interviews/academy/{academy}/criteria', [Admin\InterviewController::class, 'manageCriteria'])->name('admin.interviews.criteria');
        Route::post('/interviews/evaluate/{enrollment}', [Admin\InterviewController::class, 'evaluate'])->name('admin.interviews.evaluate');
        Route::post('/interviews/evaluate-user/{user}', [Admin\InterviewController::class, 'evaluateWithoutEnrollment'])->name('admin.interviews.evaluate-user');
        Route::get('/interviews/export', [Admin\InterviewController::class, 'exportAccepted'])->name('admin.interviews.export');
    });
});
