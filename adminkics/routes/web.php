<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\CareerApiController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ContactApiController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DirectorMessageController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\KicGroupProjectlistController;
use App\Http\Controllers\MenuLinkController;
use App\Http\Controllers\MenuSectionController;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectApiController;
use App\Http\Controllers\PublicationApiController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\VisionController;
use App\Http\Controllers\KicsPartnerController;
use App\Http\Controllers\LegacyAdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ErpApiController;
use App\Http\Controllers\ErpDepartmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventApiController;
use App\Http\Controllers\AnnouncementPopupController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageHeroController;
use App\Http\Controllers\UserController;
use App\Models\Group;
use App\Models\People;
use App\Models\KicGroupProjectlist;
use App\Models\User;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// routes/web.php
Route::get('/employees', [EmployeeController::class, 'index']);
Route::get('/departments', [DepartmentController::class, 'index']);
Route::get('/departments/{department}/projects', [DepartmentController::class, 'projects'])->name('departments.projects');
Route::get('/', function () {
    return redirect()->route('staff.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/staff/login', [AuthController::class, 'showStaffLoginForm'])->name('staff.login');
Route::post('/staff/login', [AuthController::class, 'loginStaff'])->name('staff.login.submit');
Route::get('/admin/secure-login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/secure-login', [AuthController::class, 'loginAdmin'])->name('admin.login.submit');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/staff/profile', [PeopleController::class, 'myProfile'])->name('staff.profile');
    Route::post('/staff/profile', [PeopleController::class, 'updateProfile'])->name('staff.profile.update');
});

Route::get('admin-page', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth');

// Admin Dashboard Route
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        $totalLabs = Group::count();
        $totalStaff = People::count();
        $activeStaff = People::active()->count();
        $activeLabs = Group::whereHas('staff', function ($query) {
            $query->active();
        })->count();
        $totalProjects = KicGroupProjectlist::count();
        $totalUsers = User::count();

        return view('admin.index', compact(
            'totalLabs',
            'totalStaff',
            'activeStaff',
            'activeLabs',
            'totalProjects',
            'totalUsers'
        ));
    })->name('admin.dashboard');

    /* -----------------------------------
    |  ERP Department Management Routes (FULL CRUD)
    ----------------------------------- */
    // View routes
    Route::get('/erp/departments', [ErpDepartmentController::class, 'index'])->name('admin.erp.departments.index');
    Route::get('/erp/departments/{erpDepartment}', [ErpDepartmentController::class, 'show'])->name('admin.erp.departments.show');
    Route::get('/erp/departments/{erpDepartment}/edit', [ErpDepartmentController::class, 'edit'])->name('admin.erp.departments.edit');
    
    // Update routes
    Route::put('/erp/departments/{erpDepartment}', [ErpDepartmentController::class, 'update'])->name('admin.erp.departments.update');
    Route::post('/erp/projects/{project}', [ErpDepartmentController::class, 'updateProject'])->name('admin.erp.projects.update');
    
    // Special actions
    Route::post('/erp/departments/{id}/delete-image', [ErpDepartmentController::class, 'deleteImage'])->name('admin.erp.departments.deleteImage');
    Route::post('/erp/toggle-visibility', [ErpDepartmentController::class, 'toggleVisibility'])->name('admin.erp.toggle.visibility');
    Route::post('/erp/bulk-action', [ErpDepartmentController::class, 'bulkAction'])->name('admin.erp.bulk.action');

    Route::get('/erp/employees', [EmployeeController::class, 'adminIndex'])->name('admin.erp.employees.index');
    Route::get('/erp/employees/create', [EmployeeController::class, 'create'])->name('admin.erp.employees.create');
    Route::post('/erp/employees', [EmployeeController::class, 'store'])->name('admin.erp.employees.store');
    Route::get('/erp/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('admin.erp.employees.edit');
    Route::put('/erp/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.erp.employees.update');
    Route::delete('/erp/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.erp.employees.destroy');
    Route::post('/erp/employees/{employee}/visibility', [EmployeeController::class, 'toggleVisibility'])->name('admin.erp.employees.visibility');
    
    // Sync
    Route::get('/erp/sync', [ErpDepartmentController::class, 'sync'])->name('admin.erp.sync');

    /* -----------------------------------
    |  Event Management Routes
    ----------------------------------- */
    Route::get('/events', [EventController::class, 'index'])->name('admin.events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('admin.events.create');
    Route::post('/events', [EventController::class, 'store'])->name('admin.events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('admin.events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('admin.events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');

    Route::get('/announcement-popup', [AnnouncementPopupController::class, 'edit'])->name('admin.announcement-popup.edit');
    Route::put('/announcement-popup', [AnnouncementPopupController::class, 'update'])->name('admin.announcement-popup.update');
    Route::get('/page-heroes', [PageHeroController::class, 'edit'])->name('admin.page-heroes.edit');
    Route::put('/page-heroes', [PageHeroController::class, 'update'])->name('admin.page-heroes.update');
});

Route::prefix('admin/legacy')->name('admin.legacy.')->middleware('auth')->group(function () {
    Route::get('/', [LegacyAdminController::class, 'index'])->name('index');
    Route::get('{table}', [LegacyAdminController::class, 'table'])->name('table');
    Route::get('{table}/create', [LegacyAdminController::class, 'create'])->name('create');
    Route::post('{table}', [LegacyAdminController::class, 'store'])->name('store');
    Route::get('{table}/{id}/edit', [LegacyAdminController::class, 'edit'])->name('edit');
    Route::put('{table}/{id}', [LegacyAdminController::class, 'update'])->name('update');
    Route::delete('{table}/{id}', [LegacyAdminController::class, 'destroy'])->name('destroy');
});

/* -----------------------------------
|  User Management Routes
----------------------------------- */
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

/* -----------------------------------
|  Admin Staff Routes
----------------------------------- */
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('staff', [PeopleController::class, 'index'])->name('staff.index');
    Route::post('staff', [PeopleController::class, 'store'])->name('staff.store');
    Route::get('staff/{person}/edit', [PeopleController::class, 'edit'])->name('staff.edit');
    Route::put('staff/{person}', [PeopleController::class, 'update'])->name('staff.update');
    Route::delete('staff/{person}', [PeopleController::class, 'destroy'])->name('staff.destroy');

    // ✅ Added to fix error in group_view.blade.php
    Route::get('people/{person}/edit', [PeopleController::class, 'edit'])->name('people.edit');
    Route::delete('people/{person}', [PeopleController::class, 'destroy'])->name('people.destroy');
});

/* -----------------------------------
|  Group Routes
----------------------------------- */
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('groups', [GroupController::class, 'index'])->name('admin.groups.index');
    Route::post('groups', [GroupController::class, 'store'])->name('admin.groups.store');
    Route::get('groups/{id}/view', [GroupController::class, 'view'])->name('admin.groups.view');
    Route::get('groups/{id}/edit', [GroupController::class, 'edit'])->name('admin.groups.edit');
    Route::put('groups/{group}', [GroupController::class, 'update'])->name('admin.groups.update');
    Route::delete('groups/{group}', [GroupController::class, 'destroy'])->name('admin.groups.destroy');

    // ✅ NEW: Show all staff for a specific group
    Route::get('groups/{group_id}/staff', [GroupController::class, 'groupStaff'])->name('admin.groups.staff');
});

/* -----------------------------------
|  Project Routes
----------------------------------- */
Route::get('/projects', [KicGroupProjectlistController::class, 'index'])->name('project.list');
Route::post('/projects', [KicGroupProjectlistController::class, 'store'])->name('project.store');
Route::put('/projects/{id}', [KicGroupProjectlistController::class, 'update'])->name('project.update');
Route::delete('/projects/{id}', [KicGroupProjectlistController::class, 'destroy'])->name('project.delete');
Route::get('/projects/{project}/manage', [KicGroupProjectlistController::class, 'manage'])->name('project.manage');
Route::post('/projects/{project}/manage/{section}', [KicGroupProjectlistController::class, 'storeManageRecord'])->name('project.manage.records.store');
Route::put('/projects/{project}/manage/{section}/{record}', [KicGroupProjectlistController::class, 'updateManageRecord'])->name('project.manage.records.update');
Route::delete('/projects/{project}/manage/{section}/{record}', [KicGroupProjectlistController::class, 'destroyManageRecord'])->name('project.manage.records.destroy');


Route::prefix('projects')->group(function () {
    Route::get('{id}/edit', [KicGroupProjectlistController::class, 'edit'])->name('projects.edit');
    Route::post('{id}/update', [KicGroupProjectlistController::class, 'update'])->name('projects.update');
    Route::delete('{id}/delete', [KicGroupProjectlistController::class, 'destroy'])->name('projects.delete');
});

/* -----------------------------------
|  Designation Routes
----------------------------------- */
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/designations', [DesignationController::class, 'index'])->name('designations.index');
    Route::post('/designations', [DesignationController::class, 'store'])->name('designations.store');
    Route::post('/designations/update/{id}', [DesignationController::class, 'update'])->name('designations.update');
    Route::delete('/designations/delete/{id}', [DesignationController::class, 'destroy'])->name('designations.destroy');
});

/* -----------------------------------
|  Post Routes
----------------------------------- */
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/update/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/delete/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
});

/* -----------------------------------
|  Publication Routes
----------------------------------- */
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/publications', [PublicationController::class, 'index'])->name('admin.publications.index');
    Route::post('/publications', [PublicationController::class, 'store'])->name('admin.publications.store');
    Route::put('/publications/{id}', [PublicationController::class, 'update'])->name('admin.publications.update');
    Route::delete('/publications/{id}', [PublicationController::class, 'destroy'])->name('admin.publications.destroy');
});

/*------------------------------------------
|    Create a news post
------------------------------------------*/

/*------------------------------------------
|    Career
-------------------------------------------*/

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/career', [CareerController::class, 'index'])->name('admin.career.index');

    Route::post('/career/store', [CareerController::class, 'store'])->name('admin.career.store');

    Route::get('/career/edit/{id}', [CareerController::class, 'edit'])->name('admin.career.edit');

    Route::post('/career/update/{id}', [CareerController::class, 'update'])->name('admin.career.update');

    Route::delete('/career/delete/{id}', [CareerController::class, 'destroy'])->name('admin.career.delete');
});

/*------------------------------------------
               Tags
-------------------------------------------*/

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/tags', [TagController::class, 'index'])->name('admin.tags.index');
    Route::post('/tags/store', [TagController::class, 'store'])->name('admin.tags.store');
    Route::post('/tags/update/{id}', [TagController::class, 'update'])->name('admin.tags.update');
    Route::delete('/tags/delete/{id}', [TagController::class, 'destroy'])->name('admin.tags.delete');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/news-categories', [NewsCategoryController::class, 'index'])->name('news_categories.index');
    Route::post('/news-categories/store', [NewsCategoryController::class, 'store'])->name('news_categories.store');
    Route::put('/news-categories/update/{id}', [NewsCategoryController::class, 'update'])->name('news_categories.update');
    Route::get('/news-categories/delete/{id}', [NewsCategoryController::class, 'delete'])->name('news_categories.delete');
});

// Single page for News management
Route::middleware('auth')->group(function () {
    Route::get('/admin/news', [NewsController::class, 'index'])->name('news.index');

    // AJAX CRUD routes
    Route::post('/admin/news/store', [NewsController::class, 'store'])->name('news.store');
    Route::get('/admin/news/edit/{id}', [NewsController::class, 'edit'])->name('news.edit');
    Route::post('/admin/news/update/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/admin/news/delete/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
});

/* -----------------------------------
|  About Routes
----------------------------------- */
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/about', [AboutController::class, 'index'])->name('about.index');
    Route::post('/about/store', [AboutController::class, 'store'])->name('about.store');
    Route::get('/about/edit/{id}', [AboutController::class, 'edit'])->name('about.edit');
    Route::post('/about/update/{id}', [AboutController::class, 'update'])->name('about.update');
    Route::delete('/about/delete/{id}', [AboutController::class, 'destroy'])->name('about.destroy');
});

/* -----------------------------------
|  Vision Routes
----------------------------------- */
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/vision', [VisionController::class, 'index'])->name('vision.index');
    Route::post('/vision/store', [VisionController::class, 'store'])->name('vision.store');
    Route::get('/vision/edit/{id}', [VisionController::class, 'edit'])->name('vision.edit');
    Route::post('/vision/update/{id}', [VisionController::class, 'update'])->name('vision.update');
    Route::delete('/vision/delete/{id}', [VisionController::class, 'destroy'])->name('vision.destroy');
});

/* -----------------------------------
|  Director Message Routes
----------------------------------- */
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/director_message', [DirectorMessageController::class, 'index'])->name('director_message.index');
    Route::post('/director_message/store', [DirectorMessageController::class, 'store'])->name('director_message.store');
    Route::get('/director_message/edit/{id}', [DirectorMessageController::class, 'edit'])->name('director_message.edit');
    Route::post('/director_message/update/{id}', [DirectorMessageController::class, 'update'])->name('director_message.update');
    Route::delete('/director_message/delete/{id}', [DirectorMessageController::class, 'destroy'])->name('director_message.destroy');
});

/* -----------------------------------
|  Partner Management Routes (Admin)
----------------------------------- */
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/partners', [KicsPartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/create', [KicsPartnerController::class, 'create'])->name('partners.create');
    Route::post('/partners/store', [KicsPartnerController::class, 'store'])->name('partners.store');
    Route::get('/partners/edit/{id}', [KicsPartnerController::class, 'edit'])->name('partners.edit');
    Route::post('/partners/update/{id}', [KicsPartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/delete/{id}', [KicsPartnerController::class, 'destroy'])->name('partners.delete');
});

/* -----------------------------------
|  Menu Management Routes
----------------------------------- */
Route::middleware('auth')->group(function () {
    Route::get('/admin/menu-management', function () {
        $menuSections = App\Models\MenuSection::ordered()->get();
        $menuLinks = App\Models\MenuLink::with('menuSection')->ordered()->get();

        return view('admin.menu-management', compact('menuSections', 'menuLinks'));
    })->name('menu-management');

    Route::resource('menu-sections', MenuSectionController::class)->only(['store', 'update', 'destroy']);
    Route::resource('menu-links', MenuLinkController::class)->only(['store', 'update', 'destroy']);
});

/* =============================================
   API ROUTES FOR REACT (ALL UNDER /api PREFIX)
   ============================================= */
Route::prefix('api')->group(function () {
    
    // ===== STAFF API =====
    Route::get('/staff', [PeopleController::class, 'apiStaff']);
    Route::get('/staff/{id}', [PeopleController::class, 'apiStaffById']);
    
    // ===== GROUPS API =====
    Route::get('/groups', [GroupController::class, 'apiGroups']);
    Route::get('/groups/{code}', [GroupController::class, 'apiGroupDetail']);
    
    // ===== NEWS API =====
    Route::get('/news', [NewsController::class, 'apiIndex']);
    Route::get('/news/{id}', [NewsController::class, 'apiShow']);
    
    // ===== ABOUT API =====
    Route::get('/about', [AboutController::class, 'apiIndex']);
    Route::get('/about/latest', [AboutController::class, 'apiLatest']);
    Route::get('/about/{id}', [AboutController::class, 'apiShow']);
    Route::post('/about', [AboutController::class, 'apiStore']);
    Route::put('/about/{id}', [AboutController::class, 'apiUpdate']);
    Route::delete('/about/{id}', [AboutController::class, 'apiDestroy']);
    
    // ===== VISION API =====
    Route::get('/vision', [VisionController::class, 'apiIndex']);
    Route::get('/vision/latest', [VisionController::class, 'apiLatest']);
    Route::get('/vision/{id}', [VisionController::class, 'apiShow']);
    Route::post('/vision', [VisionController::class, 'apiStore']);
    Route::put('/vision/{id}', [VisionController::class, 'apiUpdate']);
    Route::delete('/vision/{id}', [VisionController::class, 'apiDestroy']);
    
    // ===== DIRECTOR MESSAGE API =====
    Route::get('/director_message', [DirectorMessageController::class, 'apiIndex']);
    Route::get('/director_message/latest', [DirectorMessageController::class, 'apiLatest']);
    Route::get('/director_message/{id}', [DirectorMessageController::class, 'apiShow']);
    Route::post('/director_message', [DirectorMessageController::class, 'apiStore']);
    Route::put('/director_message/{id}', [DirectorMessageController::class, 'apiUpdate']);
    Route::delete('/director_message/{id}', [DirectorMessageController::class, 'apiDestroy']);
    
    // ===== CAREERS API =====
    Route::get('/careers', [CareerApiController::class, 'index']);
    Route::get('/careers/{id}', [CareerApiController::class, 'show']);
    
    // ===== PUBLICATIONS API =====
    Route::get('/publications', [PublicationApiController::class, 'index']);
    Route::get('/publications/{id}', [PublicationApiController::class, 'show']);
    
    // ===== PROJECTS API =====
    Route::get('/projects', [ProjectApiController::class, 'index']);
    Route::get('/projects/{id}/full', [ProjectApiController::class, 'full']);

    // ===== ERP API =====
    Route::get('/erp/departments', [ErpApiController::class, 'departments']);
    Route::get('/erp/departments/{id}', [ErpApiController::class, 'department']);
    Route::get('/erp/departments/{id}/projects', [ErpApiController::class, 'departmentProjects']);
    Route::get('/erp/projects', [ErpApiController::class, 'projects']);
    Route::get('/erp/employees', [ErpApiController::class, 'employees']);
    Route::get('/projects/{id}', [ProjectApiController::class, 'show']);
    
    // ===== CONTACT API =====
    Route::post('/contact', [ContactApiController::class, 'store']);
    
    // ===== PARTNERS API =====
    Route::get('/partners', [KicsPartnerController::class, 'apiIndex']);
    Route::get('/partners/{id}', [KicsPartnerController::class, 'apiShow']);
    Route::post('/partners', [KicsPartnerController::class, 'apiStore']);
    Route::put('/partners/{id}', [KicsPartnerController::class, 'apiUpdate']);
    Route::delete('/partners/{id}', [KicsPartnerController::class, 'apiDestroy']);
    
    // ===== MENU SECTIONS API =====
    Route::get('/menu-sections', [MenuSectionController::class, 'apiIndex']);
    Route::get('/page-heroes', [PageHeroController::class, 'apiIndex']);

    // ===== EVENTS API =====
    Route::get('/announcement-popup', [AnnouncementPopupController::class, 'show']);
    Route::get('/events', [EventApiController::class, 'index']);
    Route::get('/events/upcoming', [EventApiController::class, 'upcoming']);
    Route::get('/events/calendar', [EventApiController::class, 'calendar']);
    Route::get('/events/{slug}', [EventApiController::class, 'show']);
});

Route::get('/erp/departments', [ErpApiController::class, 'departments']);
Route::get('/erp/departments/{id}/projects', [ErpApiController::class, 'departmentProjects']);
Route::get('/erp/projects', [ErpApiController::class, 'projects']);
Route::get('/erp/employees', [ErpApiController::class, 'employees']);

/* =============================================
   REMOVED: Duplicate routes that were outside /api
   - Route::get('/api/staff', ...) moved to /api/staff
   - Route::get('/staff/{id}', ...) moved to /api/staff/{id}
   - Route::get('/groups', ...) moved to /api/groups
   - Route::get('/groups/{code}', ...) moved to /api/groups/{code}
   - Route::get('api/menu-sections', ...) moved to /api/menu-sections
   - Route::prefix('api/news') merged into main /api group
   ============================================= */

Route::resource('menu-sections', MenuSectionController::class)->only(['store', 'update', 'destroy']);
Route::resource('menu-links', MenuLinkController::class)->only(['store', 'update', 'destroy']);
