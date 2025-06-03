# WDV341 Coursework Repository

This project contains assignments and sample code for the Des Moines Area Community College (DMACC) course **WDV341 Advanced JavaScript**.  The repository focuses on a simple PHP/MySQL events application along with supporting HTML exercises.

## Contents

- `wdv341homework.html` – Landing page linking to each assignment.
- `events.sql` – SQL script that defines the `events` table and inserts example data.
- `eventsForm.php` – Form to create a new event with validation and a honeypot spam trap.
- `InsertEvent.php` – Minimal script to add an event to the database.
- `selectEvents.php` – Lists all events and links to an update form.
- `selectOneEvent.php` – Displays a single event (ID is currently hard-coded).
- `updateEvent.php` – Allows editing an existing event record.
- `gitterminology.html` – Brief explanations of common Git commands.
- `Unit-1 Review.html` – HTML page describing a JavaScript review assignment.
- `Events URL` – Text file containing the URL to the live event listing.

## Requirements

- PHP 8.0 or higher with PDO extension enabled
- MariaDB or MySQL database
- A `dbConnect.php` file placed in the project root that establishes a PDO connection.

Example `dbConnect.php`:

```php
<?php
$dsn = 'mysql:host=localhost;dbname=wdv341;charset=utf8mb4';
$username = 'your_db_user';
$password = 'your_db_password';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    // some scripts expect $conn instead of $pdo
    $conn = $pdo;
} catch (PDOException $e) {
    exit('Connection failed: ' . $e->getMessage());
}
```

## Setup

1. Import `events.sql` into your MySQL/MariaDB server to create the `events` table and seed it with sample rows.
2. Create `dbConnect.php` using the template above and update the credentials for your environment.
3. Start a PHP server in the repository directory:
   ```bash
   php -S localhost:8000
   ```
4. Visit `http://localhost:8000/selectEvents.php` in your browser to see the list of events.

## Usage

- **Add an event**: open `eventsForm.php` (or `InsertEvent.php`) and submit the form.
- **View all events**: navigate to `selectEvents.php`. Each row contains a link to update that record.
- **Update an event**: click the **Update** link next to an event or load `updateEvent.php?recid=ID` directly.
- **Display a single event**: access `selectOneEvent.php` (currently hard-coded to ID 1).

## Additional Pages

`wdv341homework.html` links to other coursework such as the Unit‑1 JavaScript review and Git terminology guide. These pages are static and provide instructions or reference material used in class.

## Notes

This repository is intended for learning purposes. The scripts are simplified for a classroom environment and may need further security and error-handling improvements before deployment in a production setting.
