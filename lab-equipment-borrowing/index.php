<?php
require_once __DIR__ . '/config/app.php';
require_login();
$hideNavigation = true;
$pageTitle = 'Dashboard - Laboratory Borrowing';
include __DIR__ . '/includes/header.php';
?>
<div class="dashboard-header">
  <h1>Welcome to the Laboratory Equipment Borrowing System</h1>
  <form method="post" action="<?= e(app_url('logout.php')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <button class="logout-button" type="submit">Logout</button>
  </form>
</div>
<p>Select a module to manage:</p>

<div class="home-grid">
  <?php
  $descriptions = [
      'students' => 'Store and manage student information.',
      'equipment' => 'Store and manage equipment records.',
      'staff' => 'Manage laboratory staff information.',
      'borrowings' => 'Manage borrowing transaction records.',
  ];
  $buttonColors = [
      'students' => 'blue',
      'equipment' => 'green',
      'staff' => 'yellow',
      'borrowings' => 'blue',
  ];
  foreach (MODULES as $slug => $module):
  ?>
    <div class="home-card">
      <h3><?= e($module['label']) ?></h3>
      <p><?= e($descriptions[$slug] ?? 'Manage records.') ?></p>
      <a class="button <?= e($buttonColors[$slug] ?? 'blue') ?>"
         href="<?= e(app_url('crud/index.php?module=' . $slug)) ?>">
        Manage <?= e($module['label']) ?>
      </a>
    </div>
  <?php endforeach; ?>
  <div class="home-card">
    <h3>Borrowing Inquiries</h3>
    <p>Search records using the five required inquiries and COUNT.</p>
    <a class="button green" href="<?= e(app_url('inquiries/index.php')) ?>">Open Inquiries</a>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
