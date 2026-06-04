# Phase 2: Enhanced HR Module Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add employee education history, work history, and salary promotion log to the HR module with full CRUD backend APIs and Vue tab-based frontend.

**Architecture:** Flat extension of the existing HR module — new migrations, models, and controllers in `database/migrations/` and `app/Http/Controllers/Hr/`. `EmployeeManager.vue` is refactored into a master-detail layout, delegating form logic to 4 tab sub-components under `resources/js/components/employees/`.

**Tech Stack:** Laravel 12 (PHP 8.2), Eloquent ORM, SQLite (testing via RefreshDatabase), Vue 3.5 (`<script setup>`), Tailwind CSS 4, SweetAlert2, Axios.

---

## File Map

**Create (backend):**
- `database/migrations/2026_06_04_100001_create_employee_educations_table.php`
- `database/migrations/2026_06_04_100002_create_employee_work_histories_table.php`
- `database/migrations/2026_06_04_100003_create_employee_salary_histories_table.php`
- `app/Models/Hr/EmployeeEducation.php`
- `app/Models/Hr/EmployeeWorkHistory.php`
- `app/Models/Hr/EmployeeSalaryHistory.php`
- `app/Http/Controllers/Hr/EmployeeEducationController.php`
- `app/Http/Controllers/Hr/EmployeeWorkHistoryController.php`
- `app/Http/Controllers/Hr/EmployeeSalaryHistoryController.php`
- `tests/Feature/EmployeeEducationTest.php`
- `tests/Feature/EmployeeWorkHistoryTest.php`
- `tests/Feature/EmployeeSalaryHistoryTest.php`

**Modify (backend):**
- `routes/api.php` — add 3 GET self-read routes (sanctum group) + 9 write routes (admin_hr group)

**Create (frontend):**
- `resources/js/components/employees/EmployeeProfileTab.vue`
- `resources/js/components/employees/EmployeeEducationTab.vue`
- `resources/js/components/employees/EmployeeWorkHistoryTab.vue`
- `resources/js/components/employees/EmployeeSalaryHistoryTab.vue`

**Modify (frontend):**
- `resources/js/components/EmployeeManager.vue` — refactor to master-detail layout with tabs

---

### Task 1: Database Migrations

**Files:**
- Create: `database/migrations/2026_06_04_100001_create_employee_educations_table.php`
- Create: `database/migrations/2026_06_04_100002_create_employee_work_histories_table.php`
- Create: `database/migrations/2026_06_04_100003_create_employee_salary_histories_table.php`

- [ ] **Step 1: Create the employee_educations migration**

Create `database/migrations/2026_06_04_100001_create_employee_educations_table.php`:

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_educations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('institution_name', 255);
            $table->string('degree_level', 100);
            $table->string('field_of_study', 255);
            $table->smallInteger('graduation_year')->unsigned()->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_educations');
    }
};
```

- [ ] **Step 2: Create the employee_work_histories migration**

Create `database/migrations/2026_06_04_100002_create_employee_work_histories_table.php`:

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_work_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('company_name', 255);
            $table->string('position_title', 255);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('reason_for_leaving', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->index('user_id');
            $table->index(['user_id', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_work_histories');
    }
};
```

- [ ] **Step 3: Create the employee_salary_histories migration**

Create `database/migrations/2026_06_04_100003_create_employee_salary_histories_table.php`:

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_salary_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('effective_date');
            $table->decimal('old_salary', 12, 2);
            $table->decimal('new_salary', 12, 2);
            $table->string('promotion_type', 100);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->foreign('recorded_by')->references('user_id')->on('users')->nullOnDelete();
            $table->index('user_id');
            $table->index(['user_id', 'effective_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_salary_histories');
    }
};
```

- [ ] **Step 4: Run migrations one at a time**

```bash
php artisan migrate --path=database/migrations/2026_06_04_100001_create_employee_educations_table.php
php artisan migrate --path=database/migrations/2026_06_04_100002_create_employee_work_histories_table.php
php artisan migrate --path=database/migrations/2026_06_04_100003_create_employee_salary_histories_table.php
```

Expected: each prints `Migrating:` then `Migrated:` with no errors.

- [ ] **Step 5: Commit**

```bash
git add database/migrations/2026_06_04_100001_create_employee_educations_table.php \
        database/migrations/2026_06_04_100002_create_employee_work_histories_table.php \
        database/migrations/2026_06_04_100003_create_employee_salary_histories_table.php
git commit -m "feat: add employee education, work history, and salary history migrations"
```

---

### Task 2: Eloquent Models

**Files:**
- Create: `app/Models/Hr/EmployeeEducation.php`
- Create: `app/Models/Hr/EmployeeWorkHistory.php`
- Create: `app/Models/Hr/EmployeeSalaryHistory.php`

- [ ] **Step 1: Create EmployeeEducation model**

Create `app/Models/Hr/EmployeeEducation.php`:

```php
<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\LogsActivity;

class EmployeeEducation extends Model
{
    use LogsActivity;

    protected static string $auditModule = 'HR';

    protected $fillable = [
        'user_id',
        'institution_name',
        'degree_level',
        'field_of_study',
        'graduation_year',
        'gpa',
        'notes',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'gpa' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
```

- [ ] **Step 2: Create EmployeeWorkHistory model**

Create `app/Models/Hr/EmployeeWorkHistory.php`:

```php
<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\LogsActivity;

class EmployeeWorkHistory extends Model
{
    use LogsActivity;

    protected static string $auditModule = 'HR';

    protected $fillable = [
        'user_id',
        'company_name',
        'position_title',
        'start_date',
        'end_date',
        'reason_for_leaving',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
```

- [ ] **Step 3: Create EmployeeSalaryHistory model**

Create `app/Models/Hr/EmployeeSalaryHistory.php`:

```php
<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\LogsActivity;

class EmployeeSalaryHistory extends Model
{
    use LogsActivity;

    protected static string $auditModule = 'HR';

    protected $fillable = [
        'user_id',
        'effective_date',
        'old_salary',
        'new_salary',
        'promotion_type',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'old_salary' => 'decimal:2',
        'new_salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by', 'user_id');
    }
}
```

- [ ] **Step 4: Commit**

```bash
git add app/Models/Hr/EmployeeEducation.php \
        app/Models/Hr/EmployeeWorkHistory.php \
        app/Models/Hr/EmployeeSalaryHistory.php
git commit -m "feat: add EmployeeEducation, EmployeeWorkHistory, EmployeeSalaryHistory models"
```

---

### Task 3: Education Controller, Routes, and Tests

**Files:**
- Create: `app/Http/Controllers/Hr/EmployeeEducationController.php`
- Modify: `routes/api.php`
- Create: `tests/Feature/EmployeeEducationTest.php`

- [ ] **Step 1: Write the failing tests**

Create `tests/Feature/EmployeeEducationTest.php`:

```php
<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Hr\EmployeeEducation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeEducationTest extends TestCase
{
    use RefreshDatabase;

    private function makeHrAdmin(): User
    {
        $permission = Permission::create([
            'module' => 'HR',
            'action' => 'create',
            'description' => 'HR Admin',
        ]);
        $role = Role::create([
            'role_name' => 'hr_admin',
            'description' => 'HR Admin',
            'legacy_permissions' => [],
        ]);
        $role->permissions()->attach($permission->id);
        $admin = User::factory()->create();
        $admin->roles()->attach($role->role_id);
        return $admin;
    }

    public function test_hr_admin_can_list_education_records(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        EmployeeEducation::create([
            'user_id'          => $employee->user_id,
            'institution_name' => 'Chulalongkorn University',
            'degree_level'     => "Bachelor's",
            'field_of_study'   => 'Computer Science',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/employees/{$employee->user_id}/education");

        $response->assertOk()->assertJsonCount(1);
    }

    public function test_hr_admin_can_create_education_record(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/employees/{$employee->user_id}/education", [
                'institution_name' => 'Kasetsart University',
                'degree_level'     => "Master's",
                'field_of_study'   => 'Information Technology',
                'graduation_year'  => 2020,
                'gpa'              => 3.75,
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('employee_educations', [
            'user_id'          => $employee->user_id,
            'institution_name' => 'Kasetsart University',
        ]);
    }

    public function test_hr_admin_can_update_education_record(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeEducation::create([
            'user_id'          => $employee->user_id,
            'institution_name' => 'Old University',
            'degree_level'     => "Bachelor's",
            'field_of_study'   => 'Physics',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/employees/{$employee->user_id}/education/{$record->id}", [
                'institution_name' => 'Updated University',
                'degree_level'     => "Master's",
                'field_of_study'   => 'Physics',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('employee_educations', [
            'id'               => $record->id,
            'institution_name' => 'Updated University',
        ]);
    }

    public function test_hr_admin_can_delete_education_record(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeEducation::create([
            'user_id'          => $employee->user_id,
            'institution_name' => 'Test University',
            'degree_level'     => "Bachelor's",
            'field_of_study'   => 'Math',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/employees/{$employee->user_id}/education/{$record->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('employee_educations', ['id' => $record->id]);
    }
}
```

- [ ] **Step 2: Run tests to confirm they fail**

```bash
php artisan test --filter=EmployeeEducationTest
```

Expected: 4 tests fail (404 — routes not registered yet).

- [ ] **Step 3: Create the controller**

Create `app/Http/Controllers/Hr/EmployeeEducationController.php`:

```php
<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\EmployeeEducation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeEducationController extends Controller
{
    public function index(Request $request, int $userId): JsonResponse
    {
        $auth = $request->user();
        if ($auth->user_id !== $userId && !$auth->hasPermission('HR', 'view')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $records = EmployeeEducation::where('user_id', $userId)
            ->orderByDesc('graduation_year')
            ->get();

        return response()->json($records);
    }

    public function store(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'degree_level'     => 'required|string|max:100',
            'field_of_study'   => 'required|string|max:255',
            'graduation_year'  => 'nullable|integer|min:1900|max:' . date('Y'),
            'gpa'              => 'nullable|numeric|min:0|max:4',
            'notes'            => 'nullable|string',
        ]);

        $validated['user_id'] = $userId;
        $record = EmployeeEducation::create($validated);

        return response()->json($record, 201);
    }

    public function update(Request $request, int $userId, int $eduId): JsonResponse
    {
        $record = EmployeeEducation::where('id', $eduId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'degree_level'     => 'required|string|max:100',
            'field_of_study'   => 'required|string|max:255',
            'graduation_year'  => 'nullable|integer|min:1900|max:' . date('Y'),
            'gpa'              => 'nullable|numeric|min:0|max:4',
            'notes'            => 'nullable|string',
        ]);

        $record->update($validated);

        return response()->json($record);
    }

    public function destroy(int $userId, int $eduId): JsonResponse
    {
        $record = EmployeeEducation::where('id', $eduId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $record->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}
```

- [ ] **Step 4: Register routes in `routes/api.php`**

At the top of `routes/api.php`, add these three imports after the existing `use` statements:

```php
use App\Http\Controllers\Hr\EmployeeEducationController;
use App\Http\Controllers\Hr\EmployeeWorkHistoryController;
use App\Http\Controllers\Hr\EmployeeSalaryHistoryController;
```

Inside the `Route::middleware(['session.timeout', 'auth:sanctum'])->group(...)` block, BEFORE the `admin_hr` subgroup, add the self-read routes:

```php
// Employee self-read routes (own records only — enforced in controller)
Route::get('/employees/{id}/education', [EmployeeEducationController::class, 'index']);
Route::get('/employees/{id}/work-history', [EmployeeWorkHistoryController::class, 'index']);
Route::get('/employees/{id}/salary-history', [EmployeeSalaryHistoryController::class, 'index']);
```

Inside the existing `Route::middleware('admin_hr')->group(...)` block, add:

```php
// Education
Route::post('/employees/{id}/education', [EmployeeEducationController::class, 'store']);
Route::put('/employees/{id}/education/{eduId}', [EmployeeEducationController::class, 'update']);
Route::delete('/employees/{id}/education/{eduId}', [EmployeeEducationController::class, 'destroy']);

// Work History
Route::post('/employees/{id}/work-history', [EmployeeWorkHistoryController::class, 'store']);
Route::put('/employees/{id}/work-history/{histId}', [EmployeeWorkHistoryController::class, 'update']);
Route::delete('/employees/{id}/work-history/{histId}', [EmployeeWorkHistoryController::class, 'destroy']);

// Salary History
Route::post('/employees/{id}/salary-history', [EmployeeSalaryHistoryController::class, 'store']);
Route::delete('/employees/{id}/salary-history/{salId}', [EmployeeSalaryHistoryController::class, 'destroy']);
```

- [ ] **Step 5: Run tests to confirm they pass**

```bash
php artisan test --filter=EmployeeEducationTest
```

Expected: 4 tests pass.

- [ ] **Step 6: Commit**

```bash
git add app/Http/Controllers/Hr/EmployeeEducationController.php \
        routes/api.php \
        tests/Feature/EmployeeEducationTest.php
git commit -m "feat: add EmployeeEducationController with CRUD routes and tests"
```

---

### Task 4: Work History Controller and Tests

**Files:**
- Create: `app/Http/Controllers/Hr/EmployeeWorkHistoryController.php`
- Create: `tests/Feature/EmployeeWorkHistoryTest.php`

(Routes were already registered in Task 3.)

- [ ] **Step 1: Write the failing tests**

Create `tests/Feature/EmployeeWorkHistoryTest.php`:

```php
<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Hr\EmployeeWorkHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeWorkHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function makeHrAdmin(): User
    {
        $permission = Permission::create([
            'module' => 'HR',
            'action' => 'create',
            'description' => 'HR Admin',
        ]);
        $role = Role::create([
            'role_name' => 'hr_admin',
            'description' => 'HR Admin',
            'legacy_permissions' => [],
        ]);
        $role->permissions()->attach($permission->id);
        $admin = User::factory()->create();
        $admin->roles()->attach($role->role_id);
        return $admin;
    }

    public function test_hr_admin_can_list_work_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        EmployeeWorkHistory::create([
            'user_id'        => $employee->user_id,
            'company_name'   => 'ACME Corp',
            'position_title' => 'Developer',
            'start_date'     => '2020-01-01',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/employees/{$employee->user_id}/work-history");

        $response->assertOk()->assertJsonCount(1);
    }

    public function test_hr_admin_can_create_work_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/employees/{$employee->user_id}/work-history", [
                'company_name'   => 'Tech Ltd',
                'position_title' => 'Senior Developer',
                'start_date'     => '2019-06-01',
                'end_date'       => '2022-12-31',
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('employee_work_histories', [
            'user_id'      => $employee->user_id,
            'company_name' => 'Tech Ltd',
        ]);
    }

    public function test_create_work_history_rejects_end_date_before_start_date(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/employees/{$employee->user_id}/work-history", [
                'company_name'   => 'Bad Dates Co',
                'position_title' => 'Tester',
                'start_date'     => '2022-06-01',
                'end_date'       => '2021-01-01',
            ]);

        $response->assertStatus(422);
    }

    public function test_hr_admin_can_update_work_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeWorkHistory::create([
            'user_id'        => $employee->user_id,
            'company_name'   => 'Old Co',
            'position_title' => 'Junior Dev',
            'start_date'     => '2018-01-01',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/employees/{$employee->user_id}/work-history/{$record->id}", [
                'company_name'   => 'New Co',
                'position_title' => 'Senior Dev',
                'start_date'     => '2018-01-01',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('employee_work_histories', [
            'id'           => $record->id,
            'company_name' => 'New Co',
        ]);
    }

    public function test_hr_admin_can_delete_work_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeWorkHistory::create([
            'user_id'        => $employee->user_id,
            'company_name'   => 'Delete Me Ltd',
            'position_title' => 'Temp',
            'start_date'     => '2021-01-01',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/employees/{$employee->user_id}/work-history/{$record->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('employee_work_histories', ['id' => $record->id]);
    }
}
```

- [ ] **Step 2: Run tests to confirm they fail**

```bash
php artisan test --filter=EmployeeWorkHistoryTest
```

Expected: 5 tests fail (controller class not found).

- [ ] **Step 3: Create the controller**

Create `app/Http/Controllers/Hr/EmployeeWorkHistoryController.php`:

```php
<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\EmployeeWorkHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeWorkHistoryController extends Controller
{
    public function index(Request $request, int $userId): JsonResponse
    {
        $auth = $request->user();
        if ($auth->user_id !== $userId && !$auth->hasPermission('HR', 'view')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $records = EmployeeWorkHistory::where('user_id', $userId)
            ->orderByDesc('start_date')
            ->get();

        return response()->json($records);
    }

    public function store(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'company_name'       => 'required|string|max:255',
            'position_title'     => 'required|string|max:255',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'reason_for_leaving' => 'nullable|string|max:255',
            'notes'              => 'nullable|string',
        ]);

        $validated['user_id'] = $userId;
        $record = EmployeeWorkHistory::create($validated);

        return response()->json($record, 201);
    }

    public function update(Request $request, int $userId, int $histId): JsonResponse
    {
        $record = EmployeeWorkHistory::where('id', $histId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $validated = $request->validate([
            'company_name'       => 'required|string|max:255',
            'position_title'     => 'required|string|max:255',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'reason_for_leaving' => 'nullable|string|max:255',
            'notes'              => 'nullable|string',
        ]);

        $record->update($validated);

        return response()->json($record);
    }

    public function destroy(int $userId, int $histId): JsonResponse
    {
        $record = EmployeeWorkHistory::where('id', $histId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $record->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}
```

- [ ] **Step 4: Run tests to confirm they pass**

```bash
php artisan test --filter=EmployeeWorkHistoryTest
```

Expected: 5 tests pass.

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/Hr/EmployeeWorkHistoryController.php \
        tests/Feature/EmployeeWorkHistoryTest.php
git commit -m "feat: add EmployeeWorkHistoryController with CRUD routes and tests"
```

---

### Task 5: Salary History Controller and Tests

**Files:**
- Create: `app/Http/Controllers/Hr/EmployeeSalaryHistoryController.php`
- Create: `tests/Feature/EmployeeSalaryHistoryTest.php`

(Routes were already registered in Task 3. No PUT route exists — salary records are immutable.)

- [ ] **Step 1: Write the failing tests**

Create `tests/Feature/EmployeeSalaryHistoryTest.php`:

```php
<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Hr\EmployeeSalaryHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeSalaryHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function makeHrAdmin(): User
    {
        $permission = Permission::create([
            'module' => 'HR',
            'action' => 'create',
            'description' => 'HR Admin',
        ]);
        $role = Role::create([
            'role_name' => 'hr_admin',
            'description' => 'HR Admin',
            'legacy_permissions' => [],
        ]);
        $role->permissions()->attach($permission->id);
        $admin = User::factory()->create();
        $admin->roles()->attach($role->role_id);
        return $admin;
    }

    public function test_hr_admin_can_list_salary_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        EmployeeSalaryHistory::create([
            'user_id'        => $employee->user_id,
            'effective_date' => '2024-01-01',
            'old_salary'     => 20000.00,
            'new_salary'     => 22000.00,
            'promotion_type' => 'step_increment',
            'recorded_by'    => $admin->user_id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/employees/{$employee->user_id}/salary-history");

        $response->assertOk()->assertJsonCount(1);
    }

    public function test_hr_admin_can_create_salary_history_and_recorded_by_is_autofilled(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/employees/{$employee->user_id}/salary-history", [
                'effective_date' => '2025-01-01',
                'old_salary'     => 22000.00,
                'new_salary'     => 25000.00,
                'promotion_type' => 'level_promotion',
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('employee_salary_histories', [
            'user_id'        => $employee->user_id,
            'promotion_type' => 'level_promotion',
            'recorded_by'    => $admin->user_id,
        ]);
    }

    public function test_hr_admin_can_delete_salary_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeSalaryHistory::create([
            'user_id'        => $employee->user_id,
            'effective_date' => '2024-06-01',
            'old_salary'     => 18000.00,
            'new_salary'     => 20000.00,
            'promotion_type' => 'special_adjustment',
            'recorded_by'    => $admin->user_id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/employees/{$employee->user_id}/salary-history/{$record->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('employee_salary_histories', ['id' => $record->id]);
    }
}
```

- [ ] **Step 2: Run tests to confirm they fail**

```bash
php artisan test --filter=EmployeeSalaryHistoryTest
```

Expected: 3 tests fail (controller class not found).

- [ ] **Step 3: Create the controller**

Create `app/Http/Controllers/Hr/EmployeeSalaryHistoryController.php`:

```php
<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\EmployeeSalaryHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeSalaryHistoryController extends Controller
{
    public function index(Request $request, int $userId): JsonResponse
    {
        $auth = $request->user();
        if ($auth->user_id !== $userId && !$auth->hasPermission('HR', 'view')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $records = EmployeeSalaryHistory::where('user_id', $userId)
            ->with('recorder:user_id,first_name,last_name')
            ->orderByDesc('effective_date')
            ->get();

        return response()->json($records);
    }

    public function store(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'effective_date' => 'required|date',
            'old_salary'     => 'required|numeric|min:0',
            'new_salary'     => 'required|numeric|min:0',
            'promotion_type' => 'required|in:step_increment,level_promotion,qualification_adjustment,special_adjustment',
            'notes'          => 'nullable|string',
        ]);

        $validated['user_id']     = $userId;
        $validated['recorded_by'] = auth()->id();

        $record = EmployeeSalaryHistory::create($validated);

        return response()->json($record, 201);
    }

    public function destroy(int $userId, int $salId): JsonResponse
    {
        $record = EmployeeSalaryHistory::where('id', $salId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $record->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}
```

- [ ] **Step 4: Run tests to confirm they pass**

```bash
php artisan test --filter=EmployeeSalaryHistoryTest
```

Expected: 3 tests pass.

- [ ] **Step 5: Run the full test suite to confirm no regressions**

```bash
php artisan test
```

Expected: all previously-passing tests still pass, plus 12 new tests (4 + 5 + 3).

- [ ] **Step 6: Commit**

```bash
git add app/Http/Controllers/Hr/EmployeeSalaryHistoryController.php \
        tests/Feature/EmployeeSalaryHistoryTest.php
git commit -m "feat: add EmployeeSalaryHistoryController with routes and tests (immutable records)"
```

---

### Task 6: Refactor EmployeeManager.vue + Extract EmployeeProfileTab.vue

**Files:**
- Modify: `resources/js/components/EmployeeManager.vue`
- Create: `resources/js/components/employees/EmployeeProfileTab.vue`

**Context:** `EmployeeManager.vue` currently has two top-level tabs (`employees` / `master_positions`). The `employees` tab shows a flat list with inline modals for editing and position assignment. This task refactors it into a master-detail layout: a narrow employee list on the left, and a tabbed detail panel on the right that appears when an employee is selected. The `master_positions` tab is **not changed**. The "Add Employee" modal stays in `EmployeeManager.vue` (it's a creation flow, not editing). The `EmployeeProfileTab.vue` handles editing an existing employee.

Sub-components `EmployeeEducationTab`, `EmployeeWorkHistoryTab`, `EmployeeSalaryHistoryTab` are imported as stubs in this task and fully implemented in Tasks 7–9.

- [ ] **Step 1: Create the employees/ subdirectory**

```bash
mkdir -p resources/js/components/employees
```

- [ ] **Step 2: Create stub components for the three new tabs**

Create `resources/js/components/employees/EmployeeEducationTab.vue` (stub — full implementation in Task 7):

```vue
<template>
  <div class="p-6 text-slate-400 text-sm text-center">การศึกษา — กำลังพัฒนา</div>
</template>
<script setup>
defineProps({ employee: { type: Object, required: true } });
</script>
```

Create `resources/js/components/employees/EmployeeWorkHistoryTab.vue` (stub — full implementation in Task 8):

```vue
<template>
  <div class="p-6 text-slate-400 text-sm text-center">ประวัติการทำงาน — กำลังพัฒนา</div>
</template>
<script setup>
defineProps({ employee: { type: Object, required: true } });
</script>
```

Create `resources/js/components/employees/EmployeeSalaryHistoryTab.vue` (stub — full implementation in Task 9):

```vue
<template>
  <div class="p-6 text-slate-400 text-sm text-center">ประวัติเงินเดือน — กำลังพัฒนา</div>
</template>
<script setup>
defineProps({ employee: { type: Object, required: true } });
</script>
```

- [ ] **Step 3: Create EmployeeProfileTab.vue**

Create `resources/js/components/employees/EmployeeProfileTab.vue`. This contains the edit form extracted from `EmployeeManager.vue`'s employee modal:

```vue
<template>
  <div class="p-6 space-y-6">
    <form @submit.prevent="save">
      <!-- Personal Info -->
      <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-6">
        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
          <span class="w-4 h-1 bg-blue-500 rounded-full"></span> ข้อมูลส่วนตัว
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
          <div class="col-span-12 md:col-span-3">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">คำนำหน้า</label>
            <select v-model="form.prefix"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium">
              <option value="">เลือก</option>
              <option value="นาย">นาย</option>
              <option value="นาง">นาง</option>
              <option value="นางสาว">นางสาว</option>
            </select>
          </div>
          <div class="col-span-12 md:col-span-4">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">ชื่อจริง <span class="text-rose-500">*</span></label>
            <input v-model="form.first_name" type="text" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
              placeholder="ชื่อ" />
          </div>
          <div class="col-span-12 md:col-span-5">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">นามสกุล <span class="text-rose-500">*</span></label>
            <input v-model="form.last_name" type="text" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
              placeholder="นามสกุล" />
          </div>
          <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">เลขบัตรประชาชน</label>
            <input v-model="form.id_card_number" type="text" maxlength="13"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
              placeholder="เลข 13 หลัก" />
          </div>
          <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">เบอร์โทรศัพท์</label>
            <input v-model="form.phone_number" type="tel" maxlength="10"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
              placeholder="08XXXXXXXX" />
          </div>
        </div>
      </div>

      <!-- Login Info -->
      <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 shadow-sm mb-6">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider">🔐 บัญชีเข้าสู่ระบบ</h4>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="enablePasswordEdit" class="w-4 h-4 text-blue-600 rounded border-slate-300" />
            <span class="text-sm font-bold text-slate-600">เปลี่ยนรหัสผ่าน?</span>
          </label>
        </div>
        <div>
          <label class="block text-sm font-bold text-slate-700 mb-1.5">อีเมล (Username) <span class="text-rose-500">*</span></label>
          <input v-model="form.email" type="email" required
            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 text-sm font-medium"
            placeholder="employee@enterprise.com" />
        </div>
        <div v-if="enablePasswordEdit" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-blue-100/50">
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-1.5">รหัสผ่านใหม่ <span class="text-rose-500">*</span></label>
            <input v-model="form.password" type="password"
              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm"
              placeholder="••••••••" />
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-1.5">ยืนยันรหัสผ่าน <span class="text-rose-500">*</span></label>
            <input v-model="form.password_confirmation" type="password"
              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm"
              :class="{ 'border-rose-400 bg-rose-50': passwordMismatch }"
              placeholder="••••••••" />
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <button type="submit"
          class="px-8 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow-md font-bold text-sm disabled:opacity-50 transition-colors"
          :disabled="isSaving || (enablePasswordEdit && passwordMismatch)">
          {{ isSaving ? 'กำลังบันทึก...' : 'บันทึกข้อมูล' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
  employee: { type: Object, required: true },
});

const emit = defineEmits(['updated']);

const isSaving = ref(false);
const enablePasswordEdit = ref(false);

const form = ref({ ...props.employee, password: '', password_confirmation: '' });

watch(() => props.employee, (newEmp) => {
  form.value = { ...newEmp, password: '', password_confirmation: '' };
  enablePasswordEdit.value = false;
});

const passwordMismatch = computed(() =>
  enablePasswordEdit.value && form.value.password && form.value.password !== form.value.password_confirmation
);

const save = async () => {
  isSaving.value = true;
  const payload = { ...form.value };
  if (!enablePasswordEdit.value) {
    delete payload.password;
    delete payload.password_confirmation;
  }
  try {
    await axios.put(`/api/employees/${props.employee.user_id}`, payload);
    Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1500, showConfirmButton: false });
    emit('updated');
  } catch (e) {
    Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
  } finally {
    isSaving.value = false;
  }
};
</script>
```

- [ ] **Step 4: Refactor EmployeeManager.vue**

Replace the entire content of `resources/js/components/EmployeeManager.vue` with the following. Key changes vs the original:
- `employees` tab now has a split layout: left list (w-80) + right detail panel
- Clicking a row or the ✏️ button sets `selectedEmployee` and switches `activeDetailTab` to `'profile'`
- Right panel shows 4 sub-tabs when an employee is selected
- The old edit modal is removed (form lives in `EmployeeProfileTab`)
- "Add Employee" modal is kept as-is for creating new employees
- `master_positions` tab is fully preserved from the original

```vue
<template>
  <div class="p-6 md:p-8 bg-slate-50 min-h-screen font-sans text-slate-800">

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">บุคลากรและตำแหน่งงาน</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">จัดการรายชื่อพนักงาน บริหารการสับเปลี่ยน และโครงสร้างตำแหน่ง</p>
      </div>
    </div>

    <!-- Top-level tab bar (employees / master_positions) -->
    <div class="flex gap-2 mb-8 bg-slate-200/50 p-1.5 rounded-2xl w-fit border border-slate-200/80 shadow-inner">
      <button @click="activeTab = 'employees'"
        class="px-6 py-2.5 font-bold text-sm rounded-xl transition-all duration-300 outline-none flex items-center gap-2"
        :class="activeTab === 'employees' ? 'bg-white text-blue-700 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50'">
        👥 พนักงานและการมอบหมาย
      </button>
      <button @click="activeTab = 'master_positions'"
        class="px-6 py-2.5 font-bold text-sm rounded-xl transition-all duration-300 outline-none flex items-center gap-2"
        :class="activeTab === 'master_positions' ? 'bg-white text-blue-700 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50'">
        🏢 ตั้งค่าโครงสร้างตำแหน่ง
      </button>
    </div>

    <!-- ==================== TAB: EMPLOYEES ==================== -->
    <div v-if="activeTab === 'employees'" class="animate-fade-in">
      <div class="flex gap-6">

        <!-- Left: Employee List (sidebar) -->
        <div class="w-80 shrink-0 flex flex-col gap-4">
          <div class="flex flex-col gap-3">
            <div class="relative">
              <input type="text" v-model="searchQueryEmp"
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all text-sm shadow-sm font-medium placeholder:text-slate-400"
                placeholder="ค้นหาชื่อ, อีเมล..." />
              <svg class="h-4 w-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <button @click="openEmpModal()"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl shadow-sm flex items-center justify-center gap-2 transition-all active:scale-95 font-bold text-sm">
              <span class="text-lg leading-none">+</span> เพิ่มพนักงานใหม่
            </button>
          </div>

          <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div v-if="isLoadingEmp" class="px-4 py-8 text-center text-blue-600 text-sm font-bold animate-pulse">กำลังโหลด...</div>
            <div v-else-if="filteredEmployees.length === 0" class="px-4 py-8 text-center text-slate-400 text-sm">ไม่พบพนักงาน</div>
            <div v-else>
              <button
                v-for="emp in filteredEmployees" :key="emp.user_id"
                @click="selectEmployee(emp)"
                class="w-full flex items-center gap-3 px-4 py-3 border-b border-slate-100 last:border-b-0 transition-colors text-left"
                :class="selectedEmployee?.user_id === emp.user_id ? 'bg-blue-50 border-l-2 border-l-blue-500' : 'hover:bg-slate-50'">
                <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-sm shrink-0">
                  {{ emp.first_name?.charAt(0) || '?' }}
                </div>
                <div class="min-w-0">
                  <div class="text-sm font-bold text-slate-800 truncate">{{ emp.prefix }}{{ emp.first_name }} {{ emp.last_name }}</div>
                  <div class="text-xs text-slate-400 truncate">{{ emp.position_name || '-' }}</div>
                </div>
              </button>
            </div>
          </div>
        </div>

        <!-- Right: Employee Detail Panel -->
        <div class="flex-1 min-w-0">
          <!-- No selection placeholder -->
          <div v-if="!selectedEmployee" class="flex flex-col items-center justify-center h-64 text-slate-400">
            <svg class="h-12 w-12 mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <p class="font-bold text-sm">เลือกพนักงานเพื่อดูรายละเอียด</p>
          </div>

          <!-- Detail panel -->
          <div v-else class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                  {{ selectedEmployee.first_name?.charAt(0) || '?' }}
                </div>
                <div>
                  <div class="font-bold text-slate-800">{{ selectedEmployee.prefix }}{{ selectedEmployee.first_name }} {{ selectedEmployee.last_name }}</div>
                  <div class="text-xs text-slate-400">{{ selectedEmployee.position_name || '-' }} · {{ selectedEmployee.branch_name || '-' }}</div>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <button @click="openAssignModal(selectedEmployee)"
                  class="h-8 w-8 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg flex items-center justify-center transition-colors text-sm" title="เปลี่ยนตำแหน่ง">💼</button>
                <button @click="deleteEmployee(selectedEmployee.user_id)"
                  class="h-8 w-8 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg flex items-center justify-center transition-colors text-sm" title="ลบพนักงาน">🗑️</button>
                <button @click="selectedEmployee = null"
                  class="h-8 w-8 bg-slate-100 text-slate-500 hover:bg-slate-200 rounded-lg flex items-center justify-center transition-colors font-bold">✕</button>
              </div>
            </div>

            <!-- Detail sub-tab bar -->
            <div class="flex gap-1 px-6 pt-4 border-b border-slate-100">
              <button v-for="tab in detailTabs" :key="tab.key"
                @click="activeDetailTab = tab.key"
                class="px-4 py-2 text-sm font-bold rounded-t-lg transition-all -mb-px border-b-2"
                :class="activeDetailTab === tab.key
                  ? 'text-blue-600 border-blue-500 bg-blue-50/50'
                  : 'text-slate-500 border-transparent hover:text-slate-700'">
                {{ tab.label }}
              </button>
            </div>

            <!-- Tab content -->
            <div class="min-h-64">
              <EmployeeProfileTab
                v-if="activeDetailTab === 'profile'"
                :employee="selectedEmployee"
                @updated="onEmployeeUpdated" />
              <EmployeeEducationTab
                v-if="activeDetailTab === 'education'"
                :employee="selectedEmployee" />
              <EmployeeWorkHistoryTab
                v-if="activeDetailTab === 'work_history'"
                :employee="selectedEmployee" />
              <EmployeeSalaryHistoryTab
                v-if="activeDetailTab === 'salary'"
                :employee="selectedEmployee" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ==================== TAB: MASTER POSITIONS ==================== -->
    <div v-if="activeTab === 'master_positions'" class="animate-fade-in">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="relative w-full md:w-96">
          <input type="text" v-model="searchQueryMasterPos"
            class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200/80 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all text-sm shadow-sm font-medium placeholder:text-slate-400"
            placeholder="ค้นหาชื่อตำแหน่งตั้งต้น..." />
          <svg class="h-5 w-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <button @click="openMasterPosModal()"
          class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-2xl shadow-lg flex items-center gap-2 transition-all active:scale-95 font-bold shrink-0">
          <span class="text-xl leading-none">+</span> สร้างตำแหน่งใหม่
        </button>
      </div>

      <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50/50">
              <tr>
                <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 w-16 text-center">ID</th>
                <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">ชื่อตำแหน่ง</th>
                <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">ระดับ</th>
                <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">สถานะ</th>
                <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">จัดการ</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="isLoadingMasterPos"><td colspan="5" class="px-6 py-12 text-center text-slate-500 font-bold animate-pulse">กำลังโหลด...</td></tr>
              <tr v-else-if="filteredMasterPositions.length === 0"><td colspan="5" class="px-6 py-12 text-center text-slate-400">ไม่พบข้อมูลตำแหน่งงาน</td></tr>
              <tr v-else v-for="(pos, index) in filteredMasterPositions" :key="pos.position_id" class="hover:bg-slate-50/70 transition-colors">
                <td class="px-6 py-4 text-center font-mono text-xs text-slate-400">{{ index + 1 }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm font-bold text-slate-800">{{ pos.position_name }}</div>
                  <div class="text-xs text-slate-400 mt-0.5">{{ pos.position_name_en || '-' }}</div>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="px-3 py-1 bg-slate-100 rounded-md text-xs font-bold text-slate-600 border border-slate-200/60">{{ pos.level_code }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                  <span v-if="pos.is_active == 1 || pos.is_active === true" class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold border border-emerald-200">เปิดใช้งาน</span>
                  <span v-else class="px-3 py-1 bg-slate-50 text-slate-500 rounded-full text-xs font-bold border border-slate-200">ปิดใช้งาน</span>
                </td>
                <td class="px-6 py-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button @click="openMasterPosModal(pos)" class="h-8 w-8 bg-slate-100 text-slate-500 hover:bg-blue-100 hover:text-blue-600 rounded-lg flex items-center justify-center transition-colors" title="แก้ไข">✏️</button>
                    <button @click="deleteMasterPosition(pos.position_id)" class="h-8 w-8 bg-slate-100 text-slate-500 hover:bg-rose-100 hover:text-rose-600 rounded-lg flex items-center justify-center transition-colors" title="ลบ">🗑️</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ==================== MODALS ==================== -->

    <!-- Add Employee Modal (creation only) -->
    <div v-if="isEmpModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeEmpModal"></div>
      <div class="bg-white rounded-4xl shadow-2xl w-full max-w-3xl z-10 overflow-hidden flex flex-col max-h-[90vh] animate-zoom-in">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
          <h3 class="text-xl font-extrabold text-slate-800">✨ เพิ่มพนักงานใหม่</h3>
          <button @click="closeEmpModal" class="text-slate-400 hover:text-rose-500 font-bold text-2xl transition-colors">&times;</button>
        </div>
        <div class="p-8 overflow-y-auto bg-slate-50/30">
          <form @submit.prevent="saveEmployee" class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
              <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-4 h-1 bg-blue-500 rounded-full"></span> ข้อมูลส่วนตัว
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-3">
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">คำนำหน้า</label>
                  <select v-model="formEmp.prefix"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium">
                    <option value="" disabled>เลือก</option>
                    <option value="นาย">นาย</option>
                    <option value="นาง">นาง</option>
                    <option value="นางสาว">นางสาว</option>
                  </select>
                </div>
                <div class="col-span-12 md:col-span-4">
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">ชื่อจริง <span class="text-rose-500">*</span></label>
                  <input v-model="formEmp.first_name" type="text" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium" placeholder="ชื่อ" />
                </div>
                <div class="col-span-12 md:col-span-5">
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">นามสกุล <span class="text-rose-500">*</span></label>
                  <input v-model="formEmp.last_name" type="text" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium" placeholder="นามสกุล" />
                </div>
                <div class="col-span-12 md:col-span-6">
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">เลขบัตรประชาชน</label>
                  <input v-model="formEmp.id_card_number" type="text" maxlength="13" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium" placeholder="เลข 13 หลัก" />
                </div>
                <div class="col-span-12 md:col-span-6">
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">เบอร์โทรศัพท์</label>
                  <input v-model="formEmp.phone_number" type="tel" maxlength="10" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium" placeholder="08XXXXXXXX" />
                </div>
              </div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
              <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-4 h-1 bg-amber-400 rounded-full"></span> สังกัดตำแหน่ง
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">สาขา <span class="text-rose-500">*</span></label>
                  <select v-model="formEmp.branch_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium">
                    <option value="">-- เลือกสาขา --</option>
                    <option v-for="branch in branches" :key="branch.branch_id" :value="branch.branch_id">{{ branch.branch_name }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">ตำแหน่ง <span class="text-rose-500">*</span></label>
                  <select v-model="formEmp.position_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium">
                    <option value="">-- เลือกตำแหน่ง --</option>
                    <option v-for="pos in masterPositions" :key="pos.position_id" :value="pos.position_id">{{ pos.position_name }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">ประเภทการจ้างงาน <span class="text-rose-500">*</span></label>
                  <select v-model="formEmp.employment_type_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium">
                    <option value="">-- เลือกประเภท --</option>
                    <option v-for="type in employmentTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">หมวดหมู่พนักงาน <span class="text-rose-500">*</span></label>
                  <select v-model="formEmp.employee_category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium">
                    <option value="">-- เลือกหมวดหมู่ --</option>
                    <option v-for="cat in employeeCategories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 shadow-sm">
              <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-4">🔐 ตั้งค่าบัญชีเข้าสู่ระบบ</h4>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1.5">อีเมล (Username) <span class="text-rose-500">*</span></label>
                  <input v-model="formEmp.email" type="email" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm font-medium" placeholder="employee@enterprise.com" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">รหัสผ่าน <span class="text-rose-500">*</span></label>
                    <input v-model="formEmp.password" type="password" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm" placeholder="••••••••" />
                  </div>
                  <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">ยืนยันรหัสผ่าน <span class="text-rose-500">*</span></label>
                    <input v-model="formEmp.password_confirmation" type="password" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm" placeholder="••••••••" />
                  </div>
                </div>
              </div>
            </div>
            <div class="pt-2 flex justify-end gap-3">
              <button type="button" @click="closeEmpModal" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 font-bold text-sm transition-colors shadow-sm">ยกเลิก</button>
              <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow-md font-bold text-sm disabled:opacity-50 transition-colors" :disabled="isLoadingEmp">บันทึกข้อมูล</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Assign Position Modal (unchanged) -->
    <div v-if="isAssignModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeAssignModal"></div>
      <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl z-10 overflow-hidden flex flex-col max-h-[90vh] animate-zoom-in">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
          <h3 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">💼 มอบหมายตำแหน่งงาน</h3>
          <button @click="closeAssignModal" class="text-slate-400 hover:text-rose-500 transition-colors text-2xl font-bold">&times;</button>
        </div>
        <div class="p-8 space-y-6 overflow-y-auto">
          <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">พนักงาน</label>
            <div class="text-base font-bold text-slate-800">{{ formAssign.employee_name }}</div>
          </div>
          <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-700">เลือกตำแหน่งใหม่ <span class="text-rose-500">*</span></label>
            <select v-model="formAssign.position_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-blue-400 outline-none cursor-pointer font-bold text-slate-700 shadow-sm">
              <option value="" disabled>-- กรุณาเลือกตำแหน่ง --</option>
              <option v-for="pos in allowedPositions" :key="pos.position_id" :value="pos.position_id">{{ pos.position_name }} ({{ pos.level_code }})</option>
            </select>
          </div>
          <div class="p-5 bg-amber-50/50 rounded-2xl border border-amber-100">
            <label class="flex items-center gap-3 cursor-pointer select-none">
              <input type="checkbox" v-model="formAssign.is_temporary" class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500 cursor-pointer" />
              <span class="text-sm font-bold text-slate-700">เป็นการแต่งตั้งชั่วคราว (รักษาการแทน)</span>
            </label>
            <div v-if="formAssign.is_temporary" class="mt-4">
              <label class="block text-xs font-bold text-slate-400 uppercase mb-2">วันที่สิ้นสุด <span class="text-rose-500">*</span></label>
              <input type="date" v-model="formAssign.end_date" :min="todayDate" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm outline-none font-bold shadow-sm" />
            </div>
          </div>
        </div>
        <div class="p-6 bg-slate-50 flex justify-end gap-3 border-t border-slate-100 shrink-0">
          <button @click="closeAssignModal" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm bg-white hover:bg-slate-100 transition-all">ยกเลิก</button>
          <button @click="saveAssignPos" class="px-8 py-2.5 rounded-xl bg-slate-800 text-white font-bold shadow-md text-sm hover:bg-slate-900 transition-all" :disabled="isSavingAssign">
            {{ isSavingAssign ? 'กำลังบันทึก...' : 'ยืนยันมอบหมาย' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Master Position Modal (unchanged) -->
    <div v-if="isMasterPosModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeMasterPosModal"></div>
      <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden flex flex-col max-h-[90vh] animate-zoom-in">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
          <h3 class="text-xl font-extrabold text-slate-800">{{ isEditingMasterPos ? '✏️ แก้ไขโครงสร้างตำแหน่ง' : '🏢 สร้างตำแหน่งใหม่' }}</h3>
          <button @click="closeMasterPosModal" class="text-slate-400 hover:text-rose-500 font-bold text-2xl transition-colors">&times;</button>
        </div>
        <div class="p-8 overflow-y-auto bg-slate-50/30">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-1.5 md:col-span-2">
              <label class="text-sm font-bold text-slate-700">ชื่อตำแหน่ง (ไทย) <span class="text-rose-500">*</span></label>
              <input type="text" v-model="formMasterPos.position_name" placeholder="เช่น ผู้จัดการฝ่ายขาย" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm outline-none shadow-sm" />
            </div>
            <div class="space-y-1.5 md:col-span-2">
              <label class="text-sm font-bold text-slate-700">ชื่อตำแหน่ง (อังกฤษ)</label>
              <input type="text" v-model="formMasterPos.position_name_en" placeholder="e.g. Sales Manager" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm outline-none shadow-sm" />
            </div>
            <div class="space-y-1.5">
              <label class="text-sm font-bold text-slate-700">ระดับ (Level) <span class="text-rose-500">*</span></label>
              <select v-model="formMasterPos.level_code" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm outline-none shadow-sm">
                <option value="" disabled>-- เลือกระดับ --</option>
                <option value="CEO">CEO (ผู้บริหารระดับสูง)</option>
                <option value="MGR">Manager (ผู้จัดการ)</option>
                <option value="STF">Staff (พนักงาน)</option>
              </select>
            </div>
            <div class="space-y-1.5">
              <label class="text-sm font-bold text-slate-700">ลำดับความสำคัญ</label>
              <input type="number" v-model.number="formMasterPos.priority_level" min="1" max="99" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm outline-none shadow-sm" />
            </div>
            <div class="col-span-1 md:col-span-2 mt-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
              <label class="flex items-center justify-between cursor-pointer">
                <span class="text-sm font-bold text-slate-700">สถานะการเปิดใช้งาน</span>
                <div class="relative">
                  <input type="checkbox" v-model="formMasterPos.is_active" class="sr-only peer">
                  <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </div>
              </label>
            </div>
          </div>
        </div>
        <div class="p-6 bg-white flex justify-end gap-3 border-t border-slate-100 shrink-0">
          <button @click="closeMasterPosModal" class="px-6 py-3 border border-slate-200 bg-white rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">ยกเลิก</button>
          <button @click="saveMasterPosition" class="px-8 py-3 bg-slate-800 text-white rounded-xl text-sm font-bold shadow-md hover:bg-slate-900 transition-colors">บันทึกโครงสร้าง</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import Swal from 'sweetalert2';
import EmployeeProfileTab from './employees/EmployeeProfileTab.vue';
import EmployeeEducationTab from './employees/EmployeeEducationTab.vue';
import EmployeeWorkHistoryTab from './employees/EmployeeWorkHistoryTab.vue';
import EmployeeSalaryHistoryTab from './employees/EmployeeSalaryHistoryTab.vue';

const router = useRouter();
const currentUser = ref(null);

// Top-level tab
const activeTab = ref('employees');

// Employee detail tabs
const activeDetailTab = ref('profile');
const selectedEmployee = ref(null);
const detailTabs = [
  { key: 'profile',      label: 'ข้อมูลทั่วไป' },
  { key: 'education',    label: 'การศึกษา' },
  { key: 'work_history', label: 'ประวัติการทำงาน' },
  { key: 'salary',       label: 'เงินเดือน' },
];

// Shared master data
const branches = ref([]);
const masterPositions = ref([]);
const employmentTypes = ref([]);
const employeeCategories = ref([]);

// Employee list
const employees = ref([]);
const searchQueryEmp = ref('');
const isLoadingEmp = ref(false);

// Add Employee Modal
const isEmpModalOpen = ref(false);
const formEmp = ref({
  prefix: '', first_name: '', last_name: '', id_card_number: '', phone_number: '',
  email: '', branch_id: '', position_id: '', employment_type_id: '', employee_category_id: '',
  password: '', password_confirmation: ''
});

// Assign Position Modal
const isAssignModalOpen = ref(false);
const isSavingAssign = ref(false);
const formAssign = reactive({
  id: null, employee_name: '', position_id: '', is_temporary: false, end_date: ''
});

// Master Positions
const searchQueryMasterPos = ref('');
const isLoadingMasterPos = ref(false);
const isMasterPosModalOpen = ref(false);
const isEditingMasterPos = ref(false);
const formMasterPos = ref({
  position_id: null, position_name: '', position_name_en: '', level_code: '', priority_level: 99, is_active: true
});

// Computed
const filteredEmployees = computed(() => {
  if (!searchQueryEmp.value) return employees.value;
  const q = searchQueryEmp.value.toLowerCase();
  return employees.value.filter(e =>
    (e.first_name || '').toLowerCase().includes(q) ||
    (e.last_name || '').toLowerCase().includes(q) ||
    (e.email || '').toLowerCase().includes(q)
  );
});

const filteredMasterPositions = computed(() => {
  if (!searchQueryMasterPos.value) return masterPositions.value;
  const s = searchQueryMasterPos.value.toLowerCase();
  return masterPositions.value.filter(p =>
    (p.position_name || '').toLowerCase().includes(s) ||
    (p.position_name_en || '').toLowerCase().includes(s)
  );
});

const allowedPositions = computed(() => {
  if (!currentUser.value) return [];
  const myRole = (currentUser.value?.position?.position_name || '').trim().toLowerCase();
  if (myRole === 'super admin' || myRole === 'system admin') return masterPositions.value;
  return masterPositions.value.filter(p =>
    p.position_name.toLowerCase() !== 'super admin' &&
    p.position_name.toLowerCase() !== 'system admin'
  );
});

const todayDate = computed(() => new Date().toISOString().split('T')[0]);

// API
const fetchAllData = async () => {
  isLoadingEmp.value = true;
  isLoadingMasterPos.value = true;
  try {
    const [empRes, branchRes, posRes, masterRes] = await Promise.all([
      axios.get('/api/employees'),
      axios.get('/api/branches'),
      axios.get('/api/positions'),
      axios.get('/api/master-data'),
    ]);
    employees.value = empRes.data;
    branches.value = branchRes.data;
    masterPositions.value = posRes.data.map(p => ({ ...p, is_active: Number(p.is_active) === 1 }));
    employmentTypes.value = masterRes.data.employment_types;
    employeeCategories.value = masterRes.data.employee_categories;
  } catch (e) { console.error('Fetch Error:', e); }
  finally { isLoadingEmp.value = false; isLoadingMasterPos.value = false; }
};

// Employee list actions
const selectEmployee = (emp) => {
  selectedEmployee.value = emp;
  activeDetailTab.value = 'profile';
};

const onEmployeeUpdated = () => {
  fetchAllData();
};

// Add employee modal
const openEmpModal = () => {
  formEmp.value = {
    prefix: '', first_name: '', last_name: '', id_card_number: '', phone_number: '',
    email: '', branch_id: '', position_id: '', employment_type_id: '', employee_category_id: '',
    password: '', password_confirmation: ''
  };
  isEmpModalOpen.value = true;
};
const closeEmpModal = () => isEmpModalOpen.value = false;

const saveEmployee = async () => {
  isLoadingEmp.value = true;
  try {
    await axios.post('/api/employees', formEmp.value);
    Swal.fire({ icon: 'success', title: 'เพิ่มพนักงานสำเร็จ', timer: 1500, showConfirmButton: false });
    closeEmpModal();
    fetchAllData();
  } catch (e) {
    Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
  } finally { isLoadingEmp.value = false; }
};

const deleteEmployee = (id) => {
  Swal.fire({ title: 'ยืนยันการลบพนักงาน?', text: 'ข้อมูลนี้จะถูกลบถาวร', icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#f43f5e' })
    .then(async (result) => {
      if (result.isConfirmed) {
        try {
          await axios.delete(`/api/employees/${id}`);
          if (selectedEmployee.value?.user_id === id) selectedEmployee.value = null;
          fetchAllData();
          Swal.fire({ icon: 'success', title: 'ลบสำเร็จ!', showConfirmButton: false, timer: 1500 });
        } catch (e) { Swal.fire('Error', 'ลบไม่สำเร็จ', 'error'); }
      }
    });
};

// Assign Position
const openAssignModal = (emp) => {
  Object.assign(formAssign, {
    id: emp.user_id,
    employee_name: `${emp.prefix || ''}${emp.first_name} ${emp.last_name}`,
    position_id: emp.position_id || '',
    is_temporary: !!emp.temp_position_end_date,
    end_date: emp.temp_position_end_date || ''
  });
  isAssignModalOpen.value = true;
};
const closeAssignModal = () => isAssignModalOpen.value = false;

const saveAssignPos = () => {
  if (!formAssign.position_id) return Swal.fire('แจ้งเตือน', 'กรุณาเลือกตำแหน่งใหม่', 'warning');
  if (formAssign.is_temporary && !formAssign.end_date) return Swal.fire('แจ้งเตือน', 'กรุณาระบุวันที่สิ้นสุด', 'warning');

  Swal.fire({ title: 'ยืนยันการมอบหมายตำแหน่ง?', icon: 'question', showCancelButton: true, confirmButtonText: 'ยืนยัน', confirmButtonColor: '#1e293b' })
    .then(async (result) => {
      if (result.isConfirmed) {
        isSavingAssign.value = true;
        try {
          await axios.patch(`/api/employees/${formAssign.id}/position`, {
            position_id: formAssign.position_id,
            is_temporary: formAssign.is_temporary,
            end_date: formAssign.end_date,
            is_notify_expired: 1,
          });
          closeAssignModal();
          Swal.fire({ icon: 'success', title: 'อัปเดตตำแหน่งสำเร็จ', timer: 1500, showConfirmButton: false });
          fetchAllData();
        } catch (e) { Swal.fire('Error', e.response?.data?.message || 'บันทึกไม่สำเร็จ', 'error'); }
        finally { isSavingAssign.value = false; }
      }
    });
};

// Master Positions
const openMasterPosModal = (pos = null) => {
  if (pos) {
    isEditingMasterPos.value = true;
    formMasterPos.value = { ...pos, is_active: Number(pos.is_active) === 1 };
  } else {
    isEditingMasterPos.value = false;
    formMasterPos.value = { position_id: null, position_name: '', position_name_en: '', level_code: '', priority_level: 99, is_active: true };
  }
  isMasterPosModalOpen.value = true;
};
const closeMasterPosModal = () => isMasterPosModalOpen.value = false;

const saveMasterPosition = async () => {
  if (!formMasterPos.value.position_name || !formMasterPos.value.level_code) return Swal.fire('แจ้งเตือน', 'กรอกข้อมูลสำคัญให้ครบถ้วน', 'warning');
  try {
    if (isEditingMasterPos.value) await axios.put(`/api/positions/${formMasterPos.value.position_id}`, formMasterPos.value);
    else await axios.post('/api/positions', formMasterPos.value);
    Swal.fire({ icon: 'success', title: 'บันทึกโครงสร้างสำเร็จ', showConfirmButton: false, timer: 1000 });
    closeMasterPosModal();
    fetchAllData();
  } catch (e) { Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error'); }
};

const deleteMasterPosition = (id) => {
  Swal.fire({ title: 'ลบโครงสร้างตำแหน่ง?', text: 'อาจกระทบพนักงานที่ใช้ตำแหน่งนี้อยู่', icon: 'warning', showCancelButton: true, confirmButtonColor: '#f43f5e', confirmButtonText: 'ลบ' })
    .then(async (result) => {
      if (result.isConfirmed) {
        try { await axios.delete(`/api/positions/${id}`); Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1000 }); fetchAllData(); }
        catch (e) { Swal.fire('Error', 'ไม่สามารถลบได้ (อาจมีคนใช้อยู่)', 'error'); }
      }
    });
};

onMounted(() => {
  const userStr = localStorage.getItem('user');
  if (userStr) { currentUser.value = JSON.parse(userStr); fetchAllData(); }
  else { router.push('/login'); }
});
</script>

<style scoped>
@keyframes fade-in { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in { animation: fade-in 0.3s ease-out; }
@keyframes zoomIn { from { opacity: 0; transform: scale(0.97); } to { opacity: 1; transform: scale(1); } }
.animate-zoom-in { animation: zoomIn 0.2s ease-out; }
</style>
```

- [ ] **Step 5: Verify compilation**

```bash
cd /Users/airin/Desktop/enterprise-app/enterprise-app && npm run build 2>&1 | tail -20
```

Expected: Build completes with no errors. Warnings about unused variables are acceptable.

- [ ] **Step 6: Commit**

```bash
git add resources/js/components/EmployeeManager.vue \
        resources/js/components/employees/EmployeeProfileTab.vue \
        resources/js/components/employees/EmployeeEducationTab.vue \
        resources/js/components/employees/EmployeeWorkHistoryTab.vue \
        resources/js/components/employees/EmployeeSalaryHistoryTab.vue
git commit -m "feat: refactor EmployeeManager into master-detail layout with employee tab sub-components"
```

---

### Task 7: EmployeeEducationTab.vue

**Files:**
- Modify: `resources/js/components/employees/EmployeeEducationTab.vue` (replace stub with full implementation)

- [ ] **Step 1: Replace the stub with the full component**

Replace the entire content of `resources/js/components/employees/EmployeeEducationTab.vue`:

```vue
<template>
  <div class="p-6">
    <!-- Add / Edit Form -->
    <div v-if="showForm" class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-6">
      <h4 class="text-sm font-bold text-slate-700 mb-4">{{ editingRecord ? '✏️ แก้ไขประวัติการศึกษา' : '➕ เพิ่มประวัติการศึกษา' }}</h4>
      <form @submit.prevent="saveRecord" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-600 mb-1">สถาบันการศึกษา <span class="text-rose-500">*</span></label>
          <input v-model="form.institution_name" type="text" required placeholder="เช่น มหาวิทยาลัยจุฬาลงกรณ์"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">ระดับการศึกษา <span class="text-rose-500">*</span></label>
          <select v-model="form.degree_level" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            <option value="" disabled>-- เลือก --</option>
            <option value="High School">มัธยมศึกษา / ม.6</option>
            <option value="Vocational">ปวช. / ปวส.</option>
            <option value="Bachelor's">ปริญญาตรี</option>
            <option value="Master's">ปริญญาโท</option>
            <option value="PhD">ปริญญาเอก</option>
            <option value="Other">อื่นๆ</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">สาขาวิชา <span class="text-rose-500">*</span></label>
          <input v-model="form.field_of_study" type="text" required placeholder="เช่น วิทยาการคอมพิวเตอร์"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">ปีที่จบ (พ.ศ.)</label>
          <input v-model.number="form.graduation_year" type="number" min="1900" :max="currentYear" placeholder="เช่น 2565"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">เกรดเฉลี่ย (GPA)</label>
          <input v-model.number="form.gpa" type="number" step="0.01" min="0" max="4" placeholder="เช่น 3.50"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-600 mb-1">หมายเหตุ</label>
          <textarea v-model="form.notes" rows="2" placeholder="รายละเอียดเพิ่มเติม..."
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
        </div>
        <div class="md:col-span-2 flex justify-end gap-3 pt-2">
          <button type="button" @click="cancelForm" class="px-5 py-2 rounded-xl border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 font-bold text-sm">ยกเลิก</button>
          <button type="submit" :disabled="isSaving" class="px-6 py-2 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 disabled:opacity-50">
            {{ isSaving ? 'กำลังบันทึก...' : 'บันทึก' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Toolbar -->
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-sm font-bold text-slate-700">ประวัติการศึกษา</h3>
      <button v-if="!showForm" @click="openAdd"
        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors flex items-center gap-1.5">
        <span class="text-base leading-none">+</span> เพิ่ม
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
      <div v-if="isLoading" class="py-10 text-center text-blue-600 font-bold text-sm animate-pulse">กำลังโหลด...</div>
      <div v-else-if="records.length === 0" class="py-10 text-center text-slate-400 text-sm">ไม่มีข้อมูลการศึกษา</div>
      <table v-else class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">สถาบัน</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">ระดับ</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">สาขา</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">ปี (พ.ศ.)</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">GPA</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">จัดการ</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="rec in records" :key="rec.id" class="hover:bg-slate-50/50">
            <td class="px-4 py-3 font-medium text-slate-800">{{ rec.institution_name }}</td>
            <td class="px-4 py-3 text-slate-600">{{ rec.degree_level }}</td>
            <td class="px-4 py-3 text-slate-600">{{ rec.field_of_study }}</td>
            <td class="px-4 py-3 text-center text-slate-500">{{ rec.graduation_year ? rec.graduation_year + 543 : '-' }}</td>
            <td class="px-4 py-3 text-center text-slate-500">{{ rec.gpa ?? '-' }}</td>
            <td class="px-4 py-3 text-center">
              <div class="flex items-center justify-center gap-1.5">
                <button @click="openEdit(rec)" class="h-7 w-7 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg flex items-center justify-center text-xs" title="แก้ไข">✏️</button>
                <button @click="deleteRecord(rec.id)" class="h-7 w-7 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg flex items-center justify-center text-xs" title="ลบ">🗑️</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({ employee: { type: Object, required: true } });

const records = ref([]);
const isLoading = ref(false);
const isSaving = ref(false);
const showForm = ref(false);
const editingRecord = ref(null);
const currentYear = new Date().getFullYear();

const emptyForm = () => ({ institution_name: '', degree_level: '', field_of_study: '', graduation_year: null, gpa: null, notes: '' });
const form = ref(emptyForm());

const fetchRecords = async () => {
  isLoading.value = true;
  try {
    const res = await axios.get(`/api/employees/${props.employee.user_id}/education`);
    records.value = res.data;
  } catch (e) { console.error(e); }
  finally { isLoading.value = false; }
};

const openAdd = () => { form.value = emptyForm(); editingRecord.value = null; showForm.value = true; };
const openEdit = (rec) => { form.value = { ...rec }; editingRecord.value = rec; showForm.value = true; };
const cancelForm = () => { showForm.value = false; editingRecord.value = null; };

const saveRecord = async () => {
  isSaving.value = true;
  try {
    if (editingRecord.value) {
      await axios.put(`/api/employees/${props.employee.user_id}/education/${editingRecord.value.id}`, form.value);
    } else {
      await axios.post(`/api/employees/${props.employee.user_id}/education`, form.value);
    }
    cancelForm();
    await fetchRecords();
    Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1200, showConfirmButton: false });
  } catch (e) {
    Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
  } finally { isSaving.value = false; }
};

const deleteRecord = (id) => {
  Swal.fire({ title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#f43f5e' })
    .then(async (r) => {
      if (r.isConfirmed) {
        try {
          await axios.delete(`/api/employees/${props.employee.user_id}/education/${id}`);
          await fetchRecords();
          Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', timer: 1200, showConfirmButton: false });
        } catch (e) { Swal.fire('Error', 'ลบไม่สำเร็จ', 'error'); }
      }
    });
};

watch(() => props.employee.user_id, () => { cancelForm(); fetchRecords(); });
onMounted(fetchRecords);
</script>
```

- [ ] **Step 2: Verify compilation**

```bash
cd /Users/airin/Desktop/enterprise-app/enterprise-app && npm run build 2>&1 | tail -10
```

Expected: Build succeeds with no errors.

- [ ] **Step 3: Commit**

```bash
git add resources/js/components/employees/EmployeeEducationTab.vue
git commit -m "feat: implement EmployeeEducationTab with CRUD table and inline form"
```

---

### Task 8: EmployeeWorkHistoryTab.vue

**Files:**
- Modify: `resources/js/components/employees/EmployeeWorkHistoryTab.vue` (replace stub with full implementation)

- [ ] **Step 1: Replace the stub with the full component**

Replace the entire content of `resources/js/components/employees/EmployeeWorkHistoryTab.vue`:

```vue
<template>
  <div class="p-6">
    <!-- Form -->
    <div v-if="showForm" class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-6">
      <h4 class="text-sm font-bold text-slate-700 mb-4">{{ editingRecord ? '✏️ แก้ไขประวัติการทำงาน' : '➕ เพิ่มประวัติการทำงาน' }}</h4>
      <form @submit.prevent="saveRecord" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">บริษัท / หน่วยงาน <span class="text-rose-500">*</span></label>
          <input v-model="form.company_name" type="text" required placeholder="ชื่อบริษัท"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">ตำแหน่ง <span class="text-rose-500">*</span></label>
          <input v-model="form.position_title" type="text" required placeholder="ชื่อตำแหน่ง"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">วันที่เริ่มงาน <span class="text-rose-500">*</span></label>
          <input v-model="form.start_date" type="date" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">วันที่สิ้นสุด <span class="text-slate-400 font-normal">(ว่างหมายถึงปัจจุบัน)</span></label>
          <input v-model="form.end_date" type="date" :min="form.start_date"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-600 mb-1">สาเหตุที่ออก</label>
          <input v-model="form.reason_for_leaving" type="text" placeholder="เช่น ลาออก, สัญญาหมดอายุ"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-600 mb-1">หมายเหตุ</label>
          <textarea v-model="form.notes" rows="2" placeholder="รายละเอียดเพิ่มเติม..."
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
        </div>
        <div class="md:col-span-2 flex justify-end gap-3 pt-2">
          <button type="button" @click="cancelForm" class="px-5 py-2 rounded-xl border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 font-bold text-sm">ยกเลิก</button>
          <button type="submit" :disabled="isSaving" class="px-6 py-2 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 disabled:opacity-50">
            {{ isSaving ? 'กำลังบันทึก...' : 'บันทึก' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Toolbar -->
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-sm font-bold text-slate-700">ประวัติการทำงาน</h3>
      <button v-if="!showForm" @click="openAdd" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 flex items-center gap-1.5">
        <span class="text-base leading-none">+</span> เพิ่ม
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
      <div v-if="isLoading" class="py-10 text-center text-blue-600 font-bold text-sm animate-pulse">กำลังโหลด...</div>
      <div v-else-if="records.length === 0" class="py-10 text-center text-slate-400 text-sm">ไม่มีข้อมูลประวัติการทำงาน</div>
      <table v-else class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">บริษัท</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">ตำแหน่ง</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">เริ่ม</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">สิ้นสุด</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">สาเหตุ</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">จัดการ</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="rec in records" :key="rec.id" class="hover:bg-slate-50/50">
            <td class="px-4 py-3 font-medium text-slate-800">{{ rec.company_name }}</td>
            <td class="px-4 py-3 text-slate-600">{{ rec.position_title }}</td>
            <td class="px-4 py-3 text-slate-500 text-xs">{{ formatDate(rec.start_date) }}</td>
            <td class="px-4 py-3 text-slate-500 text-xs">
              <span v-if="rec.end_date">{{ formatDate(rec.end_date) }}</span>
              <span v-else class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-xs font-bold border border-emerald-100">ปัจจุบัน</span>
            </td>
            <td class="px-4 py-3 text-slate-500">{{ rec.reason_for_leaving || '-' }}</td>
            <td class="px-4 py-3 text-center">
              <div class="flex items-center justify-center gap-1.5">
                <button @click="openEdit(rec)" class="h-7 w-7 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg flex items-center justify-center text-xs" title="แก้ไข">✏️</button>
                <button @click="deleteRecord(rec.id)" class="h-7 w-7 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg flex items-center justify-center text-xs" title="ลบ">🗑️</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({ employee: { type: Object, required: true } });

const records = ref([]);
const isLoading = ref(false);
const isSaving = ref(false);
const showForm = ref(false);
const editingRecord = ref(null);

const emptyForm = () => ({ company_name: '', position_title: '', start_date: '', end_date: '', reason_for_leaving: '', notes: '' });
const form = ref(emptyForm());

const fetchRecords = async () => {
  isLoading.value = true;
  try {
    const res = await axios.get(`/api/employees/${props.employee.user_id}/work-history`);
    records.value = res.data;
  } catch (e) { console.error(e); }
  finally { isLoading.value = false; }
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('th-TH', { year: 'numeric', month: 'short', day: 'numeric' }) : '';

const openAdd = () => { form.value = emptyForm(); editingRecord.value = null; showForm.value = true; };
const openEdit = (rec) => { form.value = { ...rec, end_date: rec.end_date || '' }; editingRecord.value = rec; showForm.value = true; };
const cancelForm = () => { showForm.value = false; editingRecord.value = null; };

const saveRecord = async () => {
  isSaving.value = true;
  try {
    const payload = { ...form.value, end_date: form.value.end_date || null };
    if (editingRecord.value) {
      await axios.put(`/api/employees/${props.employee.user_id}/work-history/${editingRecord.value.id}`, payload);
    } else {
      await axios.post(`/api/employees/${props.employee.user_id}/work-history`, payload);
    }
    cancelForm();
    await fetchRecords();
    Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1200, showConfirmButton: false });
  } catch (e) {
    Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
  } finally { isSaving.value = false; }
};

const deleteRecord = (id) => {
  Swal.fire({ title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#f43f5e' })
    .then(async (r) => {
      if (r.isConfirmed) {
        try {
          await axios.delete(`/api/employees/${props.employee.user_id}/work-history/${id}`);
          await fetchRecords();
          Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', timer: 1200, showConfirmButton: false });
        } catch (e) { Swal.fire('Error', 'ลบไม่สำเร็จ', 'error'); }
      }
    });
};

watch(() => props.employee.user_id, () => { cancelForm(); fetchRecords(); });
onMounted(fetchRecords);
</script>
```

- [ ] **Step 2: Verify compilation**

```bash
cd /Users/airin/Desktop/enterprise-app/enterprise-app && npm run build 2>&1 | tail -10
```

Expected: Build succeeds with no errors.

- [ ] **Step 3: Commit**

```bash
git add resources/js/components/employees/EmployeeWorkHistoryTab.vue
git commit -m "feat: implement EmployeeWorkHistoryTab with CRUD table and inline form"
```

---

### Task 9: EmployeeSalaryHistoryTab.vue

**Files:**
- Modify: `resources/js/components/employees/EmployeeSalaryHistoryTab.vue` (replace stub with full implementation)

**Note:** Salary records are **immutable** — no edit button, only Add and Delete. The Change column computes `new_salary - old_salary` and shows green ▲ or red ▼.

- [ ] **Step 1: Replace the stub with the full component**

Replace the entire content of `resources/js/components/employees/EmployeeSalaryHistoryTab.vue`:

```vue
<template>
  <div class="p-6">
    <!-- Add Form -->
    <div v-if="showForm" class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-6">
      <h4 class="text-sm font-bold text-slate-700 mb-4">➕ บันทึกการเลื่อนขั้นเงินเดือน</h4>
      <form @submit.prevent="saveRecord" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">วันที่มีผล <span class="text-rose-500">*</span></label>
          <input v-model="form.effective_date" type="date" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">ประเภทการปรับ <span class="text-rose-500">*</span></label>
          <select v-model="form.promotion_type" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            <option value="" disabled>-- เลือก --</option>
            <option value="step_increment">เลื่อนขั้น</option>
            <option value="level_promotion">เลื่อนระดับ</option>
            <option value="qualification_adjustment">ปรับวุฒิ</option>
            <option value="special_adjustment">ปรับพิเศษ</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">เงินเดือนเดิม (บาท) <span class="text-rose-500">*</span></label>
          <input v-model.number="form.old_salary" type="number" min="0" step="0.01" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">เงินเดือนใหม่ (บาท) <span class="text-rose-500">*</span></label>
          <input v-model.number="form.new_salary" type="number" min="0" step="0.01" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-600 mb-1">หมายเหตุ</label>
          <textarea v-model="form.notes" rows="2" placeholder="เหตุผล / รายละเอียด..."
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
        </div>
        <div v-if="form.old_salary && form.new_salary" class="md:col-span-2 p-3 rounded-xl text-sm font-bold"
          :class="form.new_salary >= form.old_salary ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100'">
          {{ form.new_salary >= form.old_salary ? '▲' : '▼' }}
          ส่วนต่าง: {{ formatMoney(Math.abs(form.new_salary - form.old_salary)) }} บาท
        </div>
        <div class="md:col-span-2 flex justify-end gap-3 pt-2">
          <button type="button" @click="cancelForm" class="px-5 py-2 rounded-xl border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 font-bold text-sm">ยกเลิก</button>
          <button type="submit" :disabled="isSaving" class="px-6 py-2 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 disabled:opacity-50">
            {{ isSaving ? 'กำลังบันทึก...' : 'บันทึก' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Toolbar -->
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-sm font-bold text-slate-700">ประวัติเงินเดือน</h3>
      <button v-if="!showForm" @click="openAdd" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 flex items-center gap-1.5">
        <span class="text-base leading-none">+</span> บันทึกการปรับ
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
      <div v-if="isLoading" class="py-10 text-center text-blue-600 font-bold text-sm animate-pulse">กำลังโหลด...</div>
      <div v-else-if="records.length === 0" class="py-10 text-center text-slate-400 text-sm">ยังไม่มีประวัติการปรับเงินเดือน</div>
      <table v-else class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">วันที่มีผล</th>
            <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase">เดิม (บาท)</th>
            <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase">ใหม่ (บาท)</th>
            <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase">ส่วนต่าง</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">ประเภท</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">บันทึกโดย</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">ลบ</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="rec in records" :key="rec.id" class="hover:bg-slate-50/50">
            <td class="px-4 py-3 text-slate-600 text-xs">{{ formatDate(rec.effective_date) }}</td>
            <td class="px-4 py-3 text-right text-slate-600 font-mono">{{ formatMoney(rec.old_salary) }}</td>
            <td class="px-4 py-3 text-right text-slate-800 font-bold font-mono">{{ formatMoney(rec.new_salary) }}</td>
            <td class="px-4 py-3 text-right font-bold font-mono"
              :class="(rec.new_salary - rec.old_salary) >= 0 ? 'text-emerald-600' : 'text-rose-600'">
              {{ (rec.new_salary - rec.old_salary) >= 0 ? '▲' : '▼' }}
              {{ formatMoney(Math.abs(rec.new_salary - rec.old_salary)) }}
            </td>
            <td class="px-4 py-3 text-center">
              <span class="px-2 py-0.5 rounded-md text-xs font-bold" :class="typeBadge(rec.promotion_type)">{{ typeLabel(rec.promotion_type) }}</span>
            </td>
            <td class="px-4 py-3 text-slate-500 text-xs">
              {{ rec.recorder ? `${rec.recorder.first_name} ${rec.recorder.last_name}` : '-' }}
            </td>
            <td class="px-4 py-3 text-center">
              <button @click="deleteRecord(rec.id)" class="h-7 w-7 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg flex items-center justify-center text-xs mx-auto" title="ลบ">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({ employee: { type: Object, required: true } });

const records = ref([]);
const isLoading = ref(false);
const isSaving = ref(false);
const showForm = ref(false);

const lastNewSalary = computed(() => records.value.length > 0 ? records.value[0].new_salary : 0);
const emptyForm = () => ({ effective_date: '', old_salary: lastNewSalary.value, new_salary: 0, promotion_type: '', notes: '' });
const form = ref(emptyForm());

const fetchRecords = async () => {
  isLoading.value = true;
  try {
    const res = await axios.get(`/api/employees/${props.employee.user_id}/salary-history`);
    records.value = res.data;
  } catch (e) { console.error(e); }
  finally { isLoading.value = false; }
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('th-TH', { year: 'numeric', month: 'short', day: 'numeric' }) : '';
const formatMoney = (n) => Number(n).toLocaleString('th-TH', { minimumFractionDigits: 2 });

const typeLabel = (t) => ({ step_increment: 'เลื่อนขั้น', level_promotion: 'เลื่อนระดับ', qualification_adjustment: 'ปรับวุฒิ', special_adjustment: 'ปรับพิเศษ' })[t] || t;
const typeBadge = (t) => ({ step_increment: 'bg-blue-50 text-blue-700 border border-blue-100', level_promotion: 'bg-violet-50 text-violet-700 border border-violet-100', qualification_adjustment: 'bg-amber-50 text-amber-700 border border-amber-100', special_adjustment: 'bg-emerald-50 text-emerald-700 border border-emerald-100' })[t] || 'bg-slate-100 text-slate-600';

const openAdd = () => { form.value = emptyForm(); showForm.value = true; };
const cancelForm = () => { showForm.value = false; };

const saveRecord = async () => {
  isSaving.value = true;
  try {
    await axios.post(`/api/employees/${props.employee.user_id}/salary-history`, form.value);
    cancelForm();
    await fetchRecords();
    Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1200, showConfirmButton: false });
  } catch (e) {
    Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
  } finally { isSaving.value = false; }
};

const deleteRecord = (id) => {
  Swal.fire({ title: 'ยืนยันการลบ?', text: 'ประวัติเงินเดือนที่ลบแล้วไม่สามารถกู้คืนได้', icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#f43f5e' })
    .then(async (r) => {
      if (r.isConfirmed) {
        try {
          await axios.delete(`/api/employees/${props.employee.user_id}/salary-history/${id}`);
          await fetchRecords();
          Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', timer: 1200, showConfirmButton: false });
        } catch (e) { Swal.fire('Error', 'ลบไม่สำเร็จ', 'error'); }
      }
    });
};

watch(() => props.employee.user_id, () => { cancelForm(); fetchRecords(); });
onMounted(fetchRecords);
</script>
```

- [ ] **Step 2: Verify compilation**

```bash
cd /Users/airin/Desktop/enterprise-app/enterprise-app && npm run build 2>&1 | tail -10
```

Expected: Build succeeds with no errors.

- [ ] **Step 3: Run the full PHPUnit suite to confirm all backend tests pass**

```bash
cd /Users/airin/Desktop/enterprise-app/enterprise-app && php artisan test
```

Expected: all tests pass (previously 14 passing + 12 new = 26 passing; 1 pre-existing ExampleTest failure about Vite manifest is unrelated and acceptable).

- [ ] **Step 4: Commit**

```bash
git add resources/js/components/employees/EmployeeSalaryHistoryTab.vue
git commit -m "feat: implement EmployeeSalaryHistoryTab with add/delete and change indicator"
```

---

*End of Phase 2 implementation plan.*
