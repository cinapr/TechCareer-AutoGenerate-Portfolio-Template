<?php
// db.php - SQLite Database connection

//$dsn = 'sqlite:timeline.db';
try {
    $pdo = new PDO('sqlite:timeline.db');  // Replace with your SQLite database path
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
