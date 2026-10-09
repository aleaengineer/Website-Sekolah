<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\Admin\AcademicCalendarController as AdminAcademicCalendarController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\AgendaController as AdminAgendaController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\BackupController as AdminBackupController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\ExtracurricularController as AdminExtracurricularController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PpdbJalurController as AdminPpdbJalurController;
use App\Http\Controllers\Admin\PpdbRegistrationController as AdminPpdbRegistrationController;
use App\Http\Controllers\Admin\PpdbWaveController as AdminPpdbWaveController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\StudentStatisticController as AdminStudentStatisticController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/profil', ProfileController::class)->name('profile');
Route::get('/akademik', AcademicController::class)->name('academic');

Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{news:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/pengumuman', AnnouncementController::class)->name('announcements');

Route::get('/agenda', [AgendaController::class, 'index'])->name('agendas.index');
Route::get('/agenda/{agenda:slug}', [AgendaController::class, 'show'])->name('agendas.show');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/galeri', GalleryController::class)->name('gallery');

Route::get('/cari', [SearchController::class, 'index'])->name('search');

Route::get('/ppdb', [PpdbController::class, 'index'])->name('ppdb.index');
Route::post('/ppdb', [PpdbController::class, 'store'])->name('ppdb.store');
Route::get('/ppdb/cek', [PpdbController::class, 'check'])->name('ppdb.check');
Route::get('/ppdb/cetak/{registrationNumber}', [PpdbController::class, 'print'])->name('ppdb.print');

Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('role:admin,operator,guru,panitia')->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('/profil-saya', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil-saya', [AdminProfileController::class, 'update'])->name('profile.update');
    });

    Route::middleware('role:admin,operator,guru')->group(function (): void {
        Route::resource('berita', AdminNewsController::class)
            ->parameters(['berita' => 'news'])
            ->names('news')
            ->except(['show']);

        Route::resource('galeri', AdminGalleryController::class)
            ->parameters(['galeri' => 'gallery'])
            ->names('galleries')
            ->except(['show']);
    });

    Route::middleware('role:admin,operator,panitia')->group(function (): void {
        Route::resource('gelombang', AdminPpdbWaveController::class)
            ->parameters(['gelombang' => 'wave'])
            ->names('waves')
            ->except(['show']);

        Route::resource('jalur', AdminPpdbJalurController::class)
            ->parameters(['jalur' => 'jalur'])
            ->names('jalurs')
            ->except(['show']);

        Route::get('/ppdb', [AdminPpdbRegistrationController::class, 'index'])->name('ppdb.index');
        Route::get('/ppdb/export', [AdminPpdbRegistrationController::class, 'export'])->name('ppdb.export');
        Route::get('/ppdb/{ppdb}', [AdminPpdbRegistrationController::class, 'show'])->name('ppdb.show');
        Route::patch('/ppdb/{ppdb}', [AdminPpdbRegistrationController::class, 'update'])->name('ppdb.update');
        Route::delete('/ppdb/{ppdb}', [AdminPpdbRegistrationController::class, 'destroy'])->name('ppdb.destroy');

        Route::get('/pesan', [AdminContactMessageController::class, 'index'])->name('messages.index');
        Route::patch('/pesan/{message}', [AdminContactMessageController::class, 'update'])->name('messages.update');
        Route::delete('/pesan/{message}', [AdminContactMessageController::class, 'destroy'])->name('messages.destroy');
    });

    Route::middleware('role:admin,operator')->group(function (): void {
        Route::get('/pengaturan', [AdminSettingController::class, 'index'])->name('settings');
        Route::put('/pengaturan', [AdminSettingController::class, 'update'])->name('settings.update');

        Route::get('/guru/template', [AdminTeacherController::class, 'template'])->name('teachers.template');
        Route::get('/guru/export', [AdminTeacherController::class, 'export'])->name('teachers.export');
        Route::post('/guru/import', [AdminTeacherController::class, 'import'])->name('teachers.import');

        Route::resource('guru', AdminTeacherController::class)
            ->parameters(['guru' => 'teacher'])
            ->names('teachers')
            ->except(['show']);

        Route::resource('ekstrakurikuler', AdminExtracurricularController::class)
            ->parameters(['ekstrakurikuler' => 'extracurricular'])
            ->names('extracurriculars')
            ->except(['show']);

        Route::resource('pengumuman', AdminAnnouncementController::class)
            ->parameters(['pengumuman' => 'announcement'])
            ->names('announcements')
            ->except(['show']);

        Route::resource('agenda', AdminAgendaController::class)
            ->parameters(['agenda' => 'agenda'])
            ->names('agendas')
            ->except(['show']);

        Route::resource('kalender', AdminAcademicCalendarController::class)
            ->parameters(['kalender' => 'calendar'])
            ->names('calendars')
            ->except(['show']);

        Route::resource('dokumen', AdminDocumentController::class)
            ->parameters(['dokumen' => 'document'])
            ->names('documents')
            ->except(['show']);

        Route::get('/kategori', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/kategori', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/kategori/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/kategori/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/kategori/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        Route::resource('data-siswa', AdminStudentStatisticController::class)
            ->parameters(['data-siswa' => 'statistic'])
            ->names('statistics')
            ->except(['show']);
    });

    Route::middleware('role:admin')->group(function (): void {
        Route::resource('pengguna', AdminUserController::class)
            ->parameters(['pengguna' => 'user'])
            ->names('users')
            ->except(['show']);

        Route::get('/log-aktivitas', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');

        Route::get('/backup', [AdminBackupController::class, 'index'])->name('backups.index');
        Route::post('/backup', [AdminBackupController::class, 'store'])->name('backups.store');
        Route::post('/backup/restore', [AdminBackupController::class, 'restore'])->name('backups.restore');
        Route::get('/backup/unduh/{file}', [AdminBackupController::class, 'download'])->name('backups.download');
        Route::delete('/backup/{file}', [AdminBackupController::class, 'destroy'])->name('backups.destroy');
    });
});
