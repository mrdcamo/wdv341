<?php
require_once 'dbConnect.php'; // Connects to the database

try {
    // Get all events from the table
    $sql = "SELECT * FROM events ORDER BY event_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Store the results in an array
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Listings</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        table { width: 80%; margin: auto; border-collapse: collapse; background: white; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #333; color: white; }
        td { background-color: #fff; }
        .no-events { font-size: 20px; color: red; }
    </style>
</head>
<body>
    <h2>Upcoming Events</h2>

    <?php if (!empty($events)) : ?>
        <table>
    <tr>
        <th>Event Name</th>
        <th>Event Date</th>
        <th>Description</th>
        <!-- add: -->
        <th>Action</th>
    </tr>
<?php foreach ($events as $event) : ?>
    <tr>
        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
        <td><?php echo htmlspecialchars($event['event_date']); ?></td>
        <td><?php echo htmlspecialchars($event['event_description']); ?></td>
        <!-- add this cell: -->
        <td>
          <a href="updateEvent.php?recid=<?php echo $event['event_id']; ?>">
            Update
          </a>
        </td>
    </tr>
<?php endforeach; ?>
        </table>
    <?php else : ?>
        <p class="no-events">No events found.</p>
    <?php endif; ?>

</body>
</html>
