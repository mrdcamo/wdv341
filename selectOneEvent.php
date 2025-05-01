<?php
require 'dbConnect.php';      // your PDO connection

$eventId = 1;                 // hard-coded for testing

try {
    // use event_id instead of id
    $sql  = 'SELECT * FROM events WHERE event_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $eventId]);
} catch (PDOException $e) {
    exit('Database error: ' . $e->getMessage());
}

if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<table border="1" cellpadding="5">';
    echo '<tr><th>Field</th><th>Value</th></tr>';
    foreach ($row as $col => $val) {
        // escape output to be safe
        echo '<tr><td>' . htmlspecialchars($col) . '</td>'
           . '<td>' . htmlspecialchars($val) . '</td></tr>';
    }
    echo '</table>';
} else {
    echo '<p>No event found with ID ' . htmlspecialchars($eventId) . '.</p>';
}
