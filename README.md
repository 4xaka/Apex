 # 📝 PHP CRUD Blog Application

This is a secure and role-based blog application built using PHP and MySQL. It includes features for user registration, login, post creation, editing, and deletion — all controlled with user roles (`admin`, `editor`).

---

## 🔐 Security Measures

- **Prepared Statements**: All SQL queries use prepared statements (`mysqli->prepare()`) to prevent SQL Injection.
- **Password Hashing**: User passwords are securely hashed using `password_hash()` and verified using `password_verify()`.
- **Session Management**: PHP sessions store logged-in user information like `username`, `role`, and `user_id` safely.
- **Validation**:
  - ✅ Client-side: Forms use JavaScript to validate empty fields.
  - ✅ Server-side: PHP checks every input for validity and sanitizes it.
- **Error Reporting**: PHP error reporting is enabled in development (`error_reporting(E_ALL)`).

---

## 🧑‍💼 Role-Based Access Control (RBAC)

| Role     | Permissions                             |
|----------|-----------------------------------------|
| Admin    | Full control (view, edit, delete all posts) |
| Editor   | Can only manage their own posts         |

- On login, `$_SESSION["role"]` is stored and used to restrict or grant access.
- Editors cannot access or edit posts they did not create.

---

## 🛠 Features

- User Registration & Login
- Dashboard with post list
- Create, Edit, Delete posts
- Role-based access enforcement
- Secure database communication
