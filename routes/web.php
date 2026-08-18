<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Qms\ActionController;
use App\Http\Controllers\Qms\AdminController;
use App\Http\Controllers\Qms\AiController;
use App\Http\Controllers\Qms\AuditController;
use App\Http\Controllers\Qms\ComplianceController;
use App\Http\Controllers\Qms\DashboardController;
use App\Http\Controllers\Qms\DocumentController;
use App\Http\Controllers\Qms\ExportController;
use App\Http\Controllers\Qms\InvestigationController;
use App\Http\Controllers\Qms\IntelligenceController;
use App\Http\Controllers\Qms\NotificationController;
use App\Http\Controllers\Qms\OccurrenceController;
use App\Http\Controllers\Qms\ObjectiveController;
use App\Http\Controllers\Qms\ManagementReviewController;
use App\Http\Controllers\Qms\PlatformController;
use App\Http\Controllers\Qms\PublicReportController;
use App\Http\Controllers\Qms\ReportingController;
use App\Http\Controllers\Qms\RiskController;
use App\Http\Controllers\Qms\SearchController;
use App\Http\Controllers\Qms\SupplierController;
use App\Http\Controllers\Qms\TrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/portal/report', [PublicReportController::class, 'create'])->name('portal.report');
Route::post('/portal/report', [PublicReportController::class, 'store'])->name('portal.report.store');

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('qms.dashboard');
    Route::get('/dashboard', DashboardController::class)->name('qms.dashboard.path');
    Route::get('/qms', DashboardController::class)->name('qms.index');
    Route::get('search', [SearchController::class, 'index'])->name('search.index');
    Route::get('intelligence', [IntelligenceController::class, 'index'])->name('intelligence.index');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('reporting', [ReportingController::class, 'index'])->name('reporting.index');
    Route::get('reporting/{reportType}/create', [ReportingController::class, 'create'])->name('reporting.create');

    Route::resource('occurrences', OccurrenceController::class)->only(['index', 'create', 'store', 'show']);
    Route::patch('occurrences/{occurrence}/advance', [OccurrenceController::class, 'advance'])->name('occurrences.advance');
    Route::post('occurrences/{occurrence}/notes', [OccurrenceController::class, 'storeNote'])->name('occurrences.notes.store');

    Route::get('actions', [ActionController::class, 'index'])->name('actions.index');
    Route::patch('actions/{action}', [ActionController::class, 'update'])->name('actions.update');

    Route::get('investigations', [InvestigationController::class, 'index'])->name('investigations.index');
    Route::get('investigations/{investigation}', [InvestigationController::class, 'show'])->name('investigations.show');
    Route::patch('investigations/{investigation}', [InvestigationController::class, 'update'])->name('investigations.update');
    Route::post('investigations/{investigation}/notes', [InvestigationController::class, 'storeNote'])->name('investigations.notes.store');

    Route::get('audits', [AuditController::class, 'index'])->name('audits.index');
    Route::get('risks', [RiskController::class, 'index'])->name('risks.index');
    Route::patch('risks/{risk}', [RiskController::class, 'update'])->name('risks.update');
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::patch('documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::get('compliance', [ComplianceController::class, 'index'])->name('compliance.index');
    Route::get('ai', [AiController::class, 'index'])->name('ai.index');
    Route::post('ai/request-review', [AiController::class, 'requestReview'])->name('ai.request-review');
    Route::get('objectives', [ObjectiveController::class, 'index'])->name('objectives.index');
    Route::get('management-reviews', [ManagementReviewController::class, 'index'])->name('management-reviews.index');
    Route::get('training', [TrainingController::class, 'index'])->name('training.index');
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('public-reports', [PublicReportController::class, 'index'])->name('public-reports.index');
    Route::get('platform', [PlatformController::class, 'index'])->name('platform.index');
    Route::post('platform/forms', [PlatformController::class, 'storeForm'])->name('platform.forms.store');
    Route::post('platform/workflows', [PlatformController::class, 'storeWorkflow'])->name('platform.workflows.store');
    Route::post('platform/saved-views', [PlatformController::class, 'storeSavedView'])->name('platform.saved-views.store');
    Route::get('exports/occurrences', [ExportController::class, 'occurrences'])->name('exports.occurrences');
    Route::get('exports/actions', [ExportController::class, 'actions'])->name('exports.actions');
    Route::get('exports/risks', [ExportController::class, 'risks'])->name('exports.risks');
    Route::get('exports/documents', [ExportController::class, 'documents'])->name('exports.documents');
    Route::get('exports/audit-trail', [ExportController::class, 'auditTrail'])->name('exports.audit-trail');

    Route::get('admin', [AdminController::class, 'index'])->name('admin.index');
});
