<?php
require 'dbConnect.php';  // your existing PDO connection

// initialize
$errors  = [];
$success = false;
$newId   = null;

// 1️⃣ When form is submitted...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2️⃣ grab & sanitize inputs
    $name = trim($_POST['event_name'] ?? '');
    $date = $_POST['event_date'] ?? '';
    $desc = trim($_POST['event_description'] ?? '');

    // 3️⃣ basic validation
    if ($name === '') {
        $errors[] = 'Event name is required.';
    }
    if ($date === '') {
        $errors[] = 'Event date is required.';
    }

    // 4️⃣ if OK, insert into DB
    if (empty($errors)) {
        try {
            $sql = 'INSERT INTO events (event_name, event_date, event_description)
                    VALUES (:name, :date, :desc)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':date' => $date,
                ':desc' => $desc
            ]);
            $newId   = $pdo->lastInsertId();
            $success = true;
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Insert New Event</title>
</head>
<body>
  <?php if ($success): ?>
    <h2>Thanks—you’ve created event #<?= htmlspecialchars($newId) ?>!</h2>
    <p><a href="">Add another event</a> | 
       <a href="selectOneEvent.php">View an event</a></p>

  <?php else: ?>
    <h1>Create a New Event</h1>

    <!-- show errors if any -->
    <?php if ($errors): ?>
      <ul style="color:red">
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <!-- self-posting form -->
    <form method="post" action="">
      <label>
        Name:<br>
        <input type="text" name="event_name"
               value="<?= htmlspecialchars($_POST['event_name'] ?? '') ?>">
      </label><br><br>

      <label>
        Date:<br>
        <input type="date" name="event_date"
               value="<?= htmlspecialchars($_POST['event_date'] ?? '') ?>">
      </label><br><br>

      <label>
        Description:<br>
        <textarea name="event_description" rows="4" cols="40"><?= 
          htmlspecialchars($_POST['event_description'] ?? '') 
        ?></textarea>
      </label><br><br>

      <button type="submit">Save Event</button>
    </form>
  <?php endif; ?>
</body>
</html>
