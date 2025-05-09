<?php
session_start();
require_once 'dbConnect.php';    // <-- exact filename casing

// 1) HANDLE FORM SUBMIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1a) Honeypot: if filled, assume bot and bail
    if (!empty($_POST['hp'])) {
        header('Location: selectEvents.php');
        exit;
    }
    // 1b) Validate recid
    if (empty($_POST['recid']) || !ctype_digit($_POST['recid'])) {
        $_SESSION['msg']      = "Invalid event ID.";
        $_SESSION['msg_type'] = "error";
        header('Location: selectEvents.php');
        exit;
    }
    $id          = (int)$_POST['recid'];
    $name        = trim($_POST['event_name']);
    $description = trim($_POST['event_description']);
    $date        = $_POST['event_date'];

    // 1c) Update query
    $sql = "UPDATE events
            SET
              event_name        = :name,
              event_description = :desc,
              event_date        = :date
            WHERE event_id = :id";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute([
          ':name' => $name,
          ':desc' => $description,
          ':date' => $date,
          ':id'   => $id
        ]);
        $_SESSION['msg']      = "Event updated successfully.";
        $_SESSION['msg_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['msg']      = "Update failed: " . $e->getMessage();
        $_SESSION['msg_type'] = "error";
    }
    // reload form to show message
    header("Location: updateEvent.php?recid=$id");
    exit;
}

// 2) SHOW FORM (GET)
if (empty($_GET['recid']) || !ctype_digit($_GET['recid'])) {
    $_SESSION['msg']      = "Invalid event ID.";
    $_SESSION['msg_type'] = "error";
    header('Location: selectEvents.php');
    exit;
}

$id   = (int)$_GET['recid'];
$sql  = "SELECT event_id, event_name, event_description, event_date
         FROM events
         WHERE event_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $_SESSION['msg']      = "Event not found.";
    $_SESSION['msg_type'] = "error";
    header('Location: selectEvents.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Event #<?= $event['event_id'] ?></title>
  <style>
    .success { color: green; }
    .error   { color: red; }
    form     { max-width: 500px; margin: auto; }
    label    { display: block; margin: .5em 0; }
    input, textarea, button { width: 100%; padding: .5em; }
    .hp { display: none; } /* hide honeypot */
  </style>
</head>
<body>

  <?php if (!empty($_SESSION['msg'])): ?>
    <p class="<?= $_SESSION['msg_type'] ?>">
      <?= htmlspecialchars($_SESSION['msg']) ?>
    </p>
    <?php unset($_SESSION['msg'], $_SESSION['msg_type']); ?>
  <?php endif; ?>

  <h1>Update Event #<?= $event['event_id'] ?></h1>

  <form method="post" action="updateEvent.php">
    <!-- keep track of which record -->
    <input type="hidden" name="recid" value="<?= $event['event_id'] ?>">

    <!-- honeypot -->
    <div class="hp">
      <label>Leave this blank:
        <input type="text" name="hp" value="">
      </label>
    </div>

    <label>
      Name:
      <input
        type="text"
        name="event_name"
        required
        value="<?= htmlspecialchars($event['event_name']) ?>"
      >
    </label>

    <label>
      Description:
      <textarea name="event_description" required><?= htmlspecialchars($event['event_description']) ?></textarea>
    </label>

    <label>
      Date:
      <input
        type="date"
        name="event_date"
        required
        value="<?= htmlspecialchars($event['event_date']) ?>"
      >
    </label>

    <button type="submit">Save Changes</button>
  </form>

  <p style="text-align:center">
    <a href="selectEvents.php">← Back to event list</a>
  </p>

</body>
</html>
