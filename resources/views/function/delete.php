<?php
session_start();
if (strpos($_SERVER['REQUEST_URI'], 'delchat') !== false) {
    // Check if CSRF token matches session token
    if ($_GET['csrf'] === $_SESSION['csrf_token']) {
        // Check if ID is set
        if (isset($_GET['id'])) {
                // Get the path to the SQLite database file
      $dbPath = database_path('sqlite/chat.db');

    // Open the database
    $db = new SQLite3($dbPath);

    // Check if message exists
    $stmt = $db->prepare('SELECT COUNT(*) FROM messages WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $count = $result->fetchArray()[0];
    if ($count === 0) {
        header('HTTP/1.1 404 Not Found');
        exit;
    }

    // Delete the message
    $stmt = $db->prepare('DELETE FROM messages WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    // Close the database
    $db->close();

    // Redirect to the homepage
    header('Location: /');
        } else {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
    }
}

echo 'test';
?>