<?php
require_once __DIR__ . '/../config/app.php';
require_login();

$type = (string) ($_GET['type'] ?? '');
$error = '';
$rows = null;
$count = 0;
$resultTitle = '';

$borrowSelect = "
 SELECT b.borrowID,
        CONCAT(s.stuFName, ' ', s.stuLName) AS studentName,
        e.equipName AS equipmentName,
        CONCAT(t.staffFName, ' ', t.staffLName) AS staffName,
        b.borrowDate, b.dueDate, b.returnDate, b.remarks
 FROM borrowing b
 INNER JOIN student s ON b.stuID = s.stuID
 INNER JOIN equipment e ON b.equipID = e.equipID
 INNER JOIN laboratory_staff t ON b.staffID = t.staffID
";

$studentOptions = $pdo->query(
    "SELECT stuID, CONCAT(stuFName, ' ', stuLName) AS fullName FROM student ORDER BY stuLName, stuFName"
)->fetchAll();
$staffOptions = $pdo->query(
    "SELECT staffID, CONCAT(staffFName, ' ', staffLName) AS fullName FROM laboratory_staff ORDER BY staffLName, staffFName"
)->fetchAll();

if ($type !== '') {
    $rowSql = '';
    $countSql = '';
    $params = [];

    switch ($type) {
        case 'category':
            $category = trim((string) ($_GET['category'] ?? ''));
            if ($category === '') {
                $error = 'Enter an equipment category.';
                break;
            }
            $resultTitle = 'Equipment in category: ' . $category;
            $rowSql = 'SELECT equipID, equipName, equipCategory, equipBrand, equipRoom
                       FROM equipment WHERE equipCategory LIKE ? ORDER BY equipName';
            $countSql = 'SELECT COUNT(*) FROM equipment WHERE equipCategory LIKE ?';
            $params = ['%' . substr($category, 0, 80) . '%'];
            break;

        case 'year_range':
            $start = filter_var($_GET['start_year'] ?? null, FILTER_VALIDATE_INT);
            $end = filter_var($_GET['end_year'] ?? null, FILTER_VALIDATE_INT);
            if ($start === false || $end === false || $start === null || $end === null
                || $start < 1 || $end > 6 || $start > $end) {
                $error = 'Choose a valid year-level range from 1 to 6.';
                break;
            }
            $resultTitle = 'Students from year level ' . $start . ' to ' . $end;
            $rowSql = 'SELECT stuID, stuFName, stuLName, stuCourse, stuYear
                       FROM student WHERE stuYear BETWEEN ? AND ? ORDER BY stuYear, stuLName';
            $countSql = 'SELECT COUNT(*) FROM student WHERE stuYear BETWEEN ? AND ?';
            $params = [$start, $end];
            break;

        case 'student':
            $id = filter_var($_GET['stuID'] ?? null, FILTER_VALIDATE_INT);
            if (!$id || $id < 1) {
                $error = 'Choose a valid Student ID.';
                break;
            }
            $resultTitle = 'Borrowing transactions for Student ID ' . $id;
            $rowSql = $borrowSelect . ' WHERE b.stuID = ? ORDER BY b.borrowDate DESC';
            $countSql = 'SELECT COUNT(*) FROM borrowing WHERE stuID = ?';
            $params = [$id];
            break;

        case 'staff':
            $id = filter_var($_GET['staffID'] ?? null, FILTER_VALIDATE_INT);
            if (!$id || $id < 1) {
                $error = 'Choose a valid Staff ID.';
                break;
            }
            $resultTitle = 'Borrowing transactions handled by Staff ID ' . $id;
            $rowSql = $borrowSelect . ' WHERE b.staffID = ? ORDER BY b.borrowDate DESC';
            $countSql = 'SELECT COUNT(*) FROM borrowing WHERE staffID = ?';
            $params = [$id];
            break;

        case 'date_range':
            $from = (string) ($_GET['from_date'] ?? '');
            $to = (string) ($_GET['to_date'] ?? '');
            if (!valid_date($from, 'Y-m-d') || !valid_date($to, 'Y-m-d') || $from > $to) {
                $error = 'Choose a valid start date and end date (start must not be later than end).';
                break;
            }
            $resultTitle = 'Borrowings dated ' . $from . ' to ' . $to;
            $rowSql = $borrowSelect . ' WHERE DATE(b.borrowDate) BETWEEN ? AND ? ORDER BY b.borrowDate DESC';
            $countSql = 'SELECT COUNT(*) FROM borrowing WHERE DATE(borrowDate) BETWEEN ? AND ?';
            $params = [$from, $to];
            break;

        default:
            $error = 'Unknown inquiry. Choose one of the five inquiry forms.';
    }

    if ($error === '') {
        // Two prepared SELECT queries:
        // (1) matching records, (2) aggregate COUNT(*) for the same filter.
        $statement = $pdo->prepare($rowSql);
        $statement->execute($params);
        $rows = $statement->fetchAll();
        $totalStatement = $pdo->prepare($countSql);
        $totalStatement->execute($params);
        $count = (int) $totalStatement->fetchColumn();
    }
}

$pageTitle = 'Borrowing Inquiries';
include __DIR__ . '/../includes/header.php';
?>
<section class="page-header">
  <div>
    <span class="eyebrow">INQUIRY MODULE - 5 POINTS</span>
    <h1>Search and COUNT Records</h1>
    <p class="muted">Each inquiry shows the matching records and an SQL aggregate COUNT(*).</p>
  </div>
  <a class="button light" href="<?= e(app_url('index.php')) ?>">Back to Dashboard</a>
</section>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="inquiry-grid">
  <section class="inquiry-card">
    <h3>1. Equipment by Category</h3>
    <p class="muted">Find equipment matching a category keyword.</p>
    <form method="get">
      <input type="hidden" name="type" value="category">
      <label for="category">Category search key</label>
      <input id="category" name="category" value="<?= e($_GET['category'] ?? '') ?>" placeholder="Example: Microcontroller" required>
      <button class="button primary" type="submit">Search Equipment</button>
    </form>
  </section>

  <section class="inquiry-card">
    <h3>2. Students by Year Level</h3>
    <p class="muted">Find students from one year level to another.</p>
    <form method="get">
      <input type="hidden" name="type" value="year_range">
      <div class="form-grid">
        <div><label for="start_year">From Year</label><input id="start_year" type="number" min="1" max="6" name="start_year" value="<?= e($_GET['start_year'] ?? '1') ?>" required></div>
        <div><label for="end_year">To Year</label><input id="end_year" type="number" min="1" max="6" name="end_year" value="<?= e($_GET['end_year'] ?? '4') ?>" required></div>
      </div>
      <button class="button primary" type="submit">Search Students</button>
    </form>
  </section>

  <section class="inquiry-card">
    <h3>3. Borrowings by Student ID</h3>
    <p class="muted">Display transactions for one student.</p>
    <form method="get">
      <input type="hidden" name="type" value="student">
      <label for="stuID">Student search key</label>
      <select id="stuID" name="stuID" required>
        <option value="">-- Select a student --</option>
        <?php foreach ($studentOptions as $student): ?>
          <option value="<?= e($student['stuID']) ?>" <?= (string) ($_GET['stuID'] ?? '') === (string) $student['stuID'] ? 'selected' : '' ?>>
            #<?= e($student['stuID']) ?> - <?= e($student['fullName']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button class="button primary" type="submit">Search Student Borrowings</button>
    </form>
  </section>

  <section class="inquiry-card">
    <h3>4. Borrowings by Staff ID</h3>
    <p class="muted">Display transactions handled by one staff member.</p>
    <form method="get">
      <input type="hidden" name="type" value="staff">
      <label for="staffID">Staff search key</label>
      <select id="staffID" name="staffID" required>
        <option value="">-- Select a staff member --</option>
        <?php foreach ($staffOptions as $member): ?>
          <option value="<?= e($member['staffID']) ?>" <?= (string) ($_GET['staffID'] ?? '') === (string) $member['staffID'] ? 'selected' : '' ?>>
            #<?= e($member['staffID']) ?> - <?= e($member['fullName']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button class="button primary" type="submit">Search Staff Borrowings</button>
    </form>
  </section>

  <section class="inquiry-card">
    <h3>5. Borrowings by Date Range</h3>
    <p class="muted">Find all transactions between two borrowing dates, inclusive.</p>
    <form method="get">
      <input type="hidden" name="type" value="date_range">
      <div class="form-grid">
        <div><label for="from_date">From</label><input id="from_date" type="date" name="from_date" value="<?= e($_GET['from_date'] ?? '2026-10-01') ?>" required></div>
        <div><label for="to_date">To</label><input id="to_date" type="date" name="to_date" value="<?= e($_GET['to_date'] ?? '2026-10-31') ?>" required></div>
      </div>
      <button class="button primary" type="submit">Search by Date</button>
    </form>
  </section>
</div>

<?php if ($rows !== null): ?>
<section class="panel" id="results">
  <h2><?= e($resultTitle) ?></h2>
  <div class="result-count">COUNT(*) = <?= e($count) ?> matching record(s)</div>
  <div class="table-wrap">
    <?php if (!$rows): ?>
      <p class="empty">No matching records found for this inquiry.</p>
    <?php else: ?>
      <table>
        <thead><tr>
          <?php foreach (array_keys($rows[0]) as $column): ?><th><?= e($column) ?></th><?php endforeach; ?>
        </tr></thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <tr>
              <?php foreach ($row as $value): ?><td><?= e($value ?? 'Not returned') ?></td><?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
