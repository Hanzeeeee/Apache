# PHP CRUD Exam Study Guide (offline, XAMPP)

Keep this file OUTSIDE the project folder during the exam (or just don't need it).

---

## STEP 1 - Folder structure

Save everything in `C:\xampp\htdocs\acquaintance_system\`

```
acquaintance_system/
├── database.sql   (all SQL)
├── db.php         (connection)
├── index.php      (login check + ALL CRUD)
├── login.php
├── logout.php
└── style.css
```

## STEP 2 - database.sql
Import: open `http://localhost/phpmyadmin/` -> click **Import** (top) -> **Choose File** -> pick `database.sql` -> **Import** (bottom).
It creates database `acquaintance_db`, tables `users`, `acquaintances`, `students`, `instructors`, and the admin account.
The admin password is stored as a hash made by PHP's `password_hash`; `login.php` checks it with `password_verify`.

## STEP 3 - db.php
`new mysqli("localhost", "root", "", "acquaintance_db")` = host, username, password (XAMPP default is empty), database name.
If the professor changes the database name, change the 4th value here AND the two lines at the top of `database.sql`.

## STEP 4 - login.php
1. `session_start()` turns on sessions.
2. User submits form -> we look up the username with a prepared statement (`?` is a safe placeholder).
3. `password_verify(typed, hashed)` true -> `$_SESSION['user'] = ...` -> redirect to `index.php`.
4. Otherwise show the red error message.

## STEP 5 - index.php (the important one)
- Top: `session_start()`, `include "db.php"`, and a login check (`if (!isset($_SESSION['user']))` -> go to login).
- `$t` = which table is shown (from the URL: `index.php?t=student`).
- Then one block per table: `if ($t == 'acquaintance') { ... }`
- Inside each block, the SAME 6 parts in the SAME order:
  ADD -> UPDATE -> DELETE -> LOAD EDIT ROW -> SEARCH/READ -> HTML (form + search + table).

How a prepared statement works (simple version):
```php
$stmt = $conn->prepare("INSERT INTO t (a, b) VALUES (?, ?)");  // ? = empty slots
$stmt->bind_param("si", $a, $b);   // s = string, i = integer, one letter per ?
$stmt->execute();                  // run it
```

Delete confirmation without JavaScript: clicking Delete goes to `?t=...&delete=5`, which shows a Yes/Cancel box. "Yes" goes to `?t=...&yes=5`, which actually deletes.

## STEP 6 - logout.php
Destroys the session and goes back to login.

## STEP 7 - style.css
Linked in each page with `<link rel="stylesheet" href="style.css">`. All local, no CDN.

## STEP 8 - Run it
1. Open XAMPP Control Panel -> **Start** Apache and MySQL (both turn green).
2. Import `database.sql` (Step 2).
3. Go to `http://localhost/acquaintance_system/` -> login `admin` / `admin123`.

---

## STEP 9 - Duplicate a CRUD section

1. In `index.php`, select from `// CRUD SECTION: ACQUAINTANCE` down to `// ============ END OF ACQUAINTANCE SECTION ============`.
2. Copy, paste right below it.
3. Inside the pasted block, change (in this order):
   - `'acquaintance'` in `if ($t == 'acquaintance')` and in every `?t=acquaintance` -> new key
   - table name `acquaintances` -> new table
   - primary key `acquaintance_id` -> new primary key
   - each column / form field / variable (`first_name` ...)
   - the type letters in `bind_param` ("sssssi")
   - the number of `?` in INSERT
   - HTML `<th>` headings and the `<label>` texts
4. Add a nav link in the header: `<a href="index.php?t=student">Students</a>`
5. Add the `CREATE TABLE` in `database.sql` (or run it in phpMyAdmin's SQL tab).

## STEP 10 - Practice: 3 tables (all three are already in the project)

| | Acquaintance | Student | Instructor |
|---|---|---|---|
| URL key | acquaintance | student | instructor |
| Table | acquaintances | students | instructors |
| Primary key | acquaintance_id | student_id | instructor_id |
| Columns | first_name, last_name, address, contact_number, email, age | student_name, course, year_level, section | instructor_name, specialization, contact_number |
| bind_param types | sssssi | ssis | sss |
| Search columns | first_name, last_name | student_name, course | instructor_name, specialization |

Try this drill: delete the Student and Instructor sections, then rebuild them from the Acquaintance block using this table. Time yourself.

---

## Fast exam workflow (do in this order)

1. **database.sql** - write the database and ALL tables first. Import. (5 min)
2. **db.php** - set the database name. (1 min)
3. **style.css, login.php, logout.php** - these are copy-paste from your practice; only the title changes. (3 min)
4. **index.php** - finish ONE table completely and test Add/Edit/Delete/Search. (10 min)
5. **Copy the section** for each remaining table and rename. (5 min each)
6. Add nav links. Test every table once.
7. Last: title text, tidy-up.

Rule: get one table fully working before copying. A bug copied 4 times is 4 bugs.

---

## CHEAT SHEET

| To change | Where |
|---|---|
| Database name | `db.php` (4th value) + `CREATE DATABASE` and `USE` in `database.sql` |
| Table name | `database.sql` + every `FROM/INTO/UPDATE ...` in that section |
| Primary key | `database.sql` + `WHERE pk=?` (UPDATE, DELETE, SELECT) + hidden input + `$row['pk']` + `$edit['pk']` |
| Input names | `name="..."` in the form AND `$_POST['...']` in PHP (must match) |
| PHP variables | the `$var = $_POST['...']` lines and `bind_param(...)` |
| INSERT | column list, the `?` count, `bind_param` letters + variables |
| UPDATE | `SET col=?, col=?` list, then `WHERE pk=?`, `bind_param` gets one extra `i` at the end |
| DELETE | table name + pk only |
| Table headings | `<th>` row and the `<td><?php echo $row['col']; ?></td>` cells |
| Duplicate for new table | Step 9 |

### Before/after example (acquaintance -> student)

```
acquaintance -> student
first_name   -> student_name
last_name    -> course
age          -> year_level
```

### Using Ctrl+Shift+L in VS Code
1. Double-click a word (e.g. `first_name`) so it is selected.
2. Press **Ctrl+Shift+L** - every match in the **file** is selected.
3. Type the new name.

DANGER: this changes the whole file, not just your pasted block. Fix:
- First select ONLY your pasted block with the mouse, then use **Ctrl+F** -> turn on **"Find in selection"** (the small icon) -> type the old word, type the new word in the replace box -> **Replace All**.
- Or rename in the pasted block BEFORE it sits beside the original (paste it in a new empty file, rename with Ctrl+Shift+L, then paste back).

### Important: renaming PHP variables does NOT change the database
`$first_name` is only a PHP name. The SQL column is the word inside the quotes (`first_name`). If you rename a column you must change it in 3 places: `database.sql` (and re-import or ALTER the table), the SQL text in the section, and the form field / `$_POST['...']` name. A mismatch gives "Unknown column" errors.

If the table already exists in phpMyAdmin, re-importing a changed `database.sql` will NOT update it (`IF NOT EXISTS`). Either drop the database first or edit the table in phpMyAdmin.

---

## XAMPP: Apache port 80 problem

**Is Apache running?** In the XAMPP Control Panel, the Apache name turns **green** with PID(s) and port(s) shown (80, 443). Or open `http://localhost/` - if you see the XAMPP page, it works.

**Why it conflicts:** another program already uses port 80. Usual causes on Windows: IIS / World Wide Web Publishing Service, Skype, VMware, a second web server, or "Web Deployment Agent". Apache then shows red text "Port 80 in use".

**Find what uses port 80** (Command Prompt as Administrator):
```
netstat -ano | findstr :80
tasklist | findstr <PID>
```
The last number on the line is the PID; the second command shows the program name. If it is a service you do not need, stop it in `services.msc` (e.g. "World Wide Web Publishing Service"). Quick fix if allowed: stop that service, restart Apache. Otherwise use 8080 below.

**Change Apache to 8080:**
1. XAMPP Control Panel -> Apache row -> **Config** -> **Apache (httpd.conf)**.
2. Press Ctrl+F, find `Listen 80`, change to `Listen 8080`.
3. Find `ServerName localhost:80`, change to `ServerName localhost:8080`.
4. **Save** the file and close Notepad.
5. If Apache was running, click **Stop**, then **Start**.
6. SSL port 443 also conflicts sometimes (Skype/VMware). You do not need it: if Apache still fails, open **Apache (httpd-ssl.conf)** and change `Listen 443` to `Listen 4433`, and `<VirtualHost _default_:443>` to `<VirtualHost _default_:4433>`.

**URLs afterwards:**

| | Port 80 | Port 8080 |
|---|---|---|
| Project | `http://localhost/acquaintance_system/` | `http://localhost:8080/acquaintance_system/` |
| phpMyAdmin | `http://localhost/phpmyadmin/` | `http://localhost:8080/phpmyadmin/` |

My code uses relative links only (`index.php`, `style.css`), so nothing in the project needs changing for 8080.

**If 8080 is also taken:** use `8081` or `8000` the same way (both lines in httpd.conf), and use that number in the URL.

**Do NOT change MySQL's port** (3306) when only Apache fails - `db.php` assumes the default.

**Save exam time:**
- Before exam day, test XAMPP on the lab PC if allowed, and know which port works.
- Open XAMPP first thing; if Apache is red, go straight to the 8080 fix. Do not spend time hunting the culprit.
- Run XAMPP Control Panel as Administrator.
- Check `localhost` works BEFORE writing code.

---

## Small notes
- Output is not escaped with `htmlspecialchars` to keep typing short. A quote (`"`) typed into a field can break the edit form. Fine for the exam; not for real projects.
- `age` and `year_level` are integers, so their inputs use `type="number"` and `bind_param` letter `i`.
- If a page is blank, enable errors by adding `ini_set('display_errors', 1);` at the top of `index.php`.
