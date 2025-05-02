<?php
// eventsForm.php
require __DIR__ . '/dbconnect.php';  // your PDO connection to mordecai_dbconnect

$errors  = [];
$success = false;
$newId   = null;

// 1) Honeypot (bot trap)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['website'])) {
    exit; // silently drop bots
}

// 2) On POST: pull & trim inputs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['events_name']        ?? '');
    $description = trim($_POST['events_description'] ?? '');
    $presenter   = trim($_POST['events_presenter']   ?? '');
    $date        = trim($_POST['events_date']        ?? '');
    $time        = trim($_POST['events_time']        ?? '');

    // 3) Validate
    if ($name === '') {
        $errors['events_name'] = 'Event name is required.';
    }
    if ($description === '') {
        $errors['events_description'] = 'Description is required.';
    }
    if ($presenter === '') {
        $errors['events_presenter'] = 'Presenter is required.';
    }
    if ($date === '') {
        $errors['events_date'] = 'Date is required.';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !strtotime($date)) {
        $errors['events_date'] = 'Use YYYY-MM-DD format.';
    }
    if ($time === '') {
        $errors['events_time'] = 'Time is required.';
    } elseif (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
        // note your table stores HH:MM:SS
        $errors['events_time'] = 'Use HH:MM:SS format.';
    }

    // 4) If valid, insert into wdv341_events
    if (empty($errors)) {
        try {
            $sql = <<<SQL
INSERT INTO wdv341_events
  (events_name, events_description, events_presenter,
   events_date, events_time,
   events_date_inserted, events_date_updated)
VALUES
  (:name, :description, :presenter,
   :date, :time,
   NOW(), NOW())
SQL;
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'        => $name,
                ':description' => $description,
                ':presenter'   => $presenter,
                ':date'        => $date,
                ':time'        => $time,
            ]);
            $newId   = $pdo->lastInsertId();
            $success = true;
        } catch (PDOException $e) {
            $errors['database'] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create a New Event</title>
  <style>
    .hp    { display:none; }
    .error { color:red; list-style:none; }
    label { display:block; margin-bottom:1em; }
  </style>
</head>
<body>

<?php if ($success): ?>
  <h2>✓ Event #<?= htmlspecialchars($newId) ?> created!</h2>
  <p>
    <a href="eventsForm.php">Create another</a> |
    <a href="selectOneEvent.php">View an event</a>
  </p>

<?php else: ?>

  <h1>Create a New Event</h1>

  <?php if ($errors): ?>
    <ul class="error">
      <?php foreach ($errors as $msg): ?>
        <li><?= htmlspecialchars($msg) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="post" action="eventsForm.php">
    <!-- honeypot field -->
    <div class="hp">
      <label>Website (leave blank):
        <input type="text" name="website" value="">
      </label>
    </div>

    <label>
      Name:<br>
      <input type="text" name="events_name"
             value="<?= htmlspecialchars($_POST['events_name'] ?? '') ?>">
    </label>

    <label>
      Description:<br>
      <textarea name="events_description" rows="4" cols="40"><?= 
        htmlspecialchars($_POST['events_description'] ?? '') 
      ?></textarea>
    </label>

    <label>
      Presenter:<br>
      <input type="text" name="events_presenter"
             value="<?= htmlspecialchars($_POST['events_presenter'] ?? '') ?>">
    </label>

    <label>
      Date:<br>
      <input type="date" name="events_date"
             value="<?= htmlspecialchars($_POST['events_date'] ?? '') ?>">
    </label>

    <label>
      Time:<br>
      <input type="time" step="1" name="events_time"
             value="<?= htmlspecialchars($_POST['events_time'] ?? '') ?>">
    </label>

    <button type="submit">Save Event</button>
  </form>

<?php endif; ?>

</body>
</html>
