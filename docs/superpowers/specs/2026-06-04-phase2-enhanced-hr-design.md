# Phase 2: Enhanced HR Module — Design Spec

**Date:** 2026-06-04
**Project:** Modular & Multi-Branch Enterprise Management System
**Depends on:** Phase 1 (Core Architecture — RBAC, Audit Log, Module Infrastructure)

---

## Goal

Extend the existing HR module with three new sub-features:
1. **การศึกษา** — Employee education / academic qualification history
2. **ประวัติการทำงาน** — Employee work history (previous jobs)
3. **เลื่อนขั้นเงินเดือน** — Salary promotion log (record-only, immutable)

---

## Architecture

Approach A: Extend the existing flat HR structure — new tables, models, controllers, and Vue tab components added alongside the existing HR code. No structural migration of existing code required.

**Access control:** HR Admin only can create, update, and delete records. All authenticated users can read their own records via `GET /api/employees/{id}/*` routes (scoped by employee ID).

---

## Database Schema

### Table: `employee_educations`

| Column | Type | Constraints |
|---|---|---|
| `id` | bigint unsigned PK | auto-increment |
| `user_id` | bigint unsigned | FK → `users.user_id`, cascade delete |
| `institution_name` | string(255) | not null |
| `degree_level` | string(100) | not null — e.g. "Bachelor's", "Master's", "PhD", "High School", "Vocational", "Other" |
| `field_of_study` | string(255) | not null |
| `graduation_year` | smallint unsigned | nullable — 4-digit year |
| `gpa` | decimal(3,2) | nullable — e.g. 3.75 |
| `notes` | text | nullable |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

Index: `(user_id)`

### Table: `employee_work_histories`

| Column | Type | Constraints |
|---|---|---|
| `id` | bigint unsigned PK | auto-increment |
| `user_id` | bigint unsigned | FK → `users.user_id`, cascade delete |
| `company_name` | string(255) | not null |
| `position_title` | string(255) | not null |
| `start_date` | date | not null |
| `end_date` | date | nullable — null means current position |
| `reason_for_leaving` | string(255) | nullable |
| `notes` | text | nullable |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

Index: `(user_id)`, `(user_id, start_date)`

### Table: `employee_salary_histories`

| Column | Type | Constraints |
|---|---|---|
| `id` | bigint unsigned PK | auto-increment |
| `user_id` | bigint unsigned | FK → `users.user_id`, cascade delete |
| `effective_date` | date | not null — date the salary change took effect |
| `old_salary` | decimal(12,2) | not null |
| `new_salary` | decimal(12,2) | not null |
| `promotion_type` | string(100) | not null — "Annual Raise", "Promotion", "Merit", "Adjustment" |
| `notes` | text | nullable |
| `recorded_by` | bigint unsigned | FK → `users.user_id`, set null on delete |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

Index: `(user_id)`, `(user_id, effective_date)`

**Note:** Salary history is immutable — no `UPDATE` operation allowed. Records can only be created or deleted.

---

## Backend

### Models (`app/Models/Hr/`)

**`EmployeeEducation`**
- `$fillable`: `user_id`, `institution_name`, `degree_level`, `field_of_study`, `graduation_year`, `gpa`, `notes`
- `$casts`: `graduation_year` → integer, `gpa` → decimal
- Relationships: `belongsTo(User::class, 'user_id', 'user_id')`
- Traits: `LogsActivity` with `$auditModule = 'HR'`

**`EmployeeWorkHistory`**
- `$fillable`: `user_id`, `company_name`, `position_title`, `start_date`, `end_date`, `reason_for_leaving`, `notes`
- `$casts`: `start_date` → date, `end_date` → date
- Relationships: `belongsTo(User::class, 'user_id', 'user_id')`
- Traits: `LogsActivity` with `$auditModule = 'HR'`

**`EmployeeSalaryHistory`**
- `$fillable`: `user_id`, `effective_date`, `old_salary`, `new_salary`, `promotion_type`, `notes`, `recorded_by`
- `$casts`: `effective_date` → date, `old_salary` → decimal, `new_salary` → decimal
- Relationships: `belongsTo(User::class, 'user_id', 'user_id')`, `belongsTo(User::class, 'recorded_by', 'user_id')`
- Traits: `LogsActivity` with `$auditModule = 'HR'`

### Controllers (`app/Http/Controllers/Hr/`)

**`EmployeeEducationController`**
- `index(userId)` → return all education records for employee, ordered by `graduation_year DESC`
- `store(userId, request)` → validate + create record
- `update(userId, educationId, request)` → validate + update record (verify record belongs to employee)
- `destroy(userId, educationId)` → delete record (verify record belongs to employee)

**`EmployeeWorkHistoryController`**
- `index(userId)` → return all work history records, ordered by `start_date DESC`
- `store(userId, request)` → validate + create. Validate: `end_date >= start_date` if both provided
- `update(userId, histId, request)` → validate + update
- `destroy(userId, histId)` → delete

**`EmployeeSalaryHistoryController`**
- `index(userId)` → return all salary history, ordered by `effective_date DESC`
- `store(userId, request)` → validate + create. Auto-fills `recorded_by` from `auth()->id()`. Suggest `old_salary` = employee's current salary (read from `users.position.min_salary` as hint, not enforced).
- `destroy(userId, salId)` → delete

### Validation Rules

**Education:**
```
institution_name: required|string|max:255
degree_level:     required|string|max:100
field_of_study:   required|string|max:255
graduation_year:  nullable|integer|min:1900|max:{current_year}
gpa:              nullable|numeric|min:0|max:4
notes:            nullable|string
```

**Work History:**
```
company_name:       required|string|max:255
position_title:     required|string|max:255
start_date:         required|date
end_date:           nullable|date|after_or_equal:start_date
reason_for_leaving: nullable|string|max:255
notes:              nullable|string
```

**Salary History:**
```
effective_date:  required|date
old_salary:      required|numeric|min:0
new_salary:      required|numeric|min:0
promotion_type:  required|in:step_increment,level_promotion,qualification_adjustment,special_adjustment
notes:           nullable|string
```

Promotion type display labels:
- `step_increment` → "เลื่อนขั้น"
- `level_promotion` → "เลื่อนระดับ"
- `qualification_adjustment` → "ปรับวุฒิ"
- `special_adjustment` → "ปรับพิเศษ"

### API Routes

Routes are split into two groups in `routes/api.php`:

**Employee self-read routes** — protected by `auth:sanctum` + `session.timeout` only. An employee may only read their own records (controller enforces `{id}` == `auth()->id()` or HR Admin bypass).

```
GET    /api/employees/{id}/education
GET    /api/employees/{id}/work-history
GET    /api/employees/{id}/salary-history
```

**HR Admin write routes** — protected by `auth:sanctum`, `session.timeout`, and `admin_hr` middleware.

```
# Education
POST   /api/employees/{id}/education
PUT    /api/employees/{id}/education/{eduId}
DELETE /api/employees/{id}/education/{eduId}

# Work History
POST   /api/employees/{id}/work-history
PUT    /api/employees/{id}/work-history/{histId}
DELETE /api/employees/{id}/work-history/{histId}

# Salary History
POST   /api/employees/{id}/salary-history
DELETE /api/employees/{id}/salary-history/{salId}
```

---

## Frontend

### Component Structure

```
resources/js/components/
  EmployeeManager.vue              ← refactored: tab switching + employee list only
  employees/
    EmployeeProfileTab.vue         ← extracted: existing profile edit form
    EmployeeEducationTab.vue       ← NEW
    EmployeeWorkHistoryTab.vue     ← NEW
    EmployeeSalaryHistoryTab.vue   ← NEW
```

### Tab Bar (inside employee detail panel)

```
[ ข้อมูลทั่วไป ] [ การศึกษา ] [ ประวัติการทำงาน ] [ เงินเดือน ]
```

Active tab shown with Tailwind underline/highlight. Default tab: ข้อมูลทั่วไป.

### EmployeeManager.vue (refactored)

Responsibilities after refactor:
- Employee list sidebar (search, filter by branch)
- Selected employee state (`selectedEmployee`)
- Active tab state (`activeTab`)
- Pass `selectedEmployee` as prop to each tab component
- No more inline form logic — all delegated to tab components

### EmployeeEducationTab.vue

Props: `employee` (object with `user_id`)

State:
- `records[]` — education records fetched from API
- `showForm` — toggle add/edit form
- `editingRecord` — null for new, object for edit

Behavior:
- On mount: `GET /api/employees/{id}/education`
- Table columns: Institution | Degree | Field | Year | GPA | Actions
- Add button → opens inline form (same page, no modal)
- Edit (pencil icon) → populates form with existing record
- Delete (trash icon) → SweetAlert2 confirm → `DELETE`
- Form fields: institution_name, degree_level (dropdown), field_of_study, graduation_year, gpa, notes
- On save: `POST` (new) or `PUT` (edit) → refresh records list

### EmployeeWorkHistoryTab.vue

Props: `employee`

Same CRUD pattern as Education tab.

Table columns: Company | Position | Start Date | End Date | Reason | Actions

Form fields: company_name, position_title, start_date, end_date, reason_for_leaving, notes

### EmployeeSalaryHistoryTab.vue

Props: `employee`

**No Edit button** — salary records are immutable. Only Add + Delete.

Table columns: Effective Date | Old Salary | New Salary | Change | Type | Recorded By | Notes

"Change" column = computed: `new_salary - old_salary`, shown with ▲ green (raise) or ▼ red (cut).

Form fields: effective_date, old_salary (pre-filled with last record's new_salary if any), new_salary, promotion_type (dropdown), notes

---

## Testing

### Feature tests (PHPUnit)

**`EmployeeEducationTest`** — 4 tests:
1. HR admin can list education records
2. HR admin can create education record
3. HR admin can update education record
4. HR admin can delete education record

**`EmployeeWorkHistoryTest`** — 4 tests:
1. HR admin can list work history
2. HR admin can create work history (validates end_date >= start_date)
3. HR admin can update work history
4. HR admin can delete work history

**`EmployeeSalaryHistoryTest`** — 3 tests:
1. HR admin can list salary history
2. HR admin can create salary history (recorded_by auto-filled)
3. HR admin can delete salary history (no update test — immutable)

---

## Out of Scope for Phase 2

- Employee self-service (submitting their own history) — Phase 2b if needed
- Salary promotion approval workflow — Phase 2b if needed
- Printing employee profile as PDF — Phase 4 (Digital Signature)
- Moving HR code into `app/Modules/HR/` module structure — separate cleanup task
