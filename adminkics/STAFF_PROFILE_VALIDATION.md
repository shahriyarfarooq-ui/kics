# Staff profile validation and routing

This note explains how the public staff profile flow is validated and where the rules live.

## 1. Public staff visibility rules

The public staff API is filtered in:

- [adminkics/app/Http/Controllers/PeopleController.php](adminkics/app/Http/Controllers/PeopleController.php)

The important checks are in `apiStaff()` and `apiStaffById()`:

- `People::where('status', 0)`
- `where('profile_visible', true)`
- `whereHas('user', fn ($query) => $query->where('role', 'staff'))`

This means only active staff profiles with a public flag and a user role of `staff` are returned in the public API.

If a person is hidden, missing, or not linked to a staff user, the route returns 404.

## 2. ERP employee to profile matching

The ERP directory page is built from employee data, but each employee is matched to the public People profile in:

- [adminkics/app/Http/Controllers/ErpApiController.php](adminkics/app/Http/Controllers/ErpApiController.php)

This controller does the following:

- loads current ERP employees (`state = Current`)
- filters only visible ERP employees
- loads public People records where `status = 0` and `profile_visible = true`
- matches by email first
- falls back to employee name + People name when email differs
- keeps only employees with meaningful public profile content

This is what allows profiles such as Shahzaib Ali to appear in the staff directory even when the ERP email differs from the public People email.

## 3. Frontend rendering

The public list is rendered in:

- [kics-frontend/src/pages/ErpEmployees.jsx](kics-frontend/src/pages/ErpEmployees.jsx)

The detail page is rendered in:

- [kics-frontend/src/pages/StaffDetail.jsx](kics-frontend/src/pages/StaffDetail.jsx)

The frontend expects these fields:

- `employee.profile.people_id`
- `employee.profile.image_path`
- `employee.profile.email`

The email button is only shown when the value is a valid email string.

## 4. Login and role validation

The login and role checks are in:

- [adminkics/app/Http/Controllers/AuthController.php](adminkics/app/Http/Controllers/AuthController.php)

Important rules:

- `loginAdmin()` only accepts an authenticated user whose role is `admin`
- `loginStaff()` only accepts an authenticated user whose role is `staff`
- the app logs the user out and rejects unauthorized access if the wrong role tries to use the wrong login page

## 5. Future ERP login integration

When the ERP login API is ready, the same validation pattern should remain:

- ERP user login validates the external account
- the system checks the mapped local user role
- only `staff` users can access the public profile editor
- all public profile visibility still remains controlled by `status`, `profile_visible`, and `user.role`

This keeps the public directory safe while allowing ERP-based staff accounts to edit profile information after approval.
