<?php
session_start();
// Open the database
$db = new SQLite3('sqlite\chat.db');

// Retrieve the messages from the database
$stmt = $db->prepare('SELECT * FROM messages ORDER BY timestamp DESC LIMIT 50');
$result = $stmt->execute();
$messages = array();
$kknownUserIds = ['228343232520519680', '1107988165849002034'];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
  // Only include the ip_address variable if the user has a session set as admin
  if (isset($_SESSION['token']) && in_array($_SESSION['user_id'], $kknownUserIds)) {
    $messages[] = $row;
  } else {
    unset($row['ip_address']);
    $messages[] = $row;
  }
}

// Close the database
$db->close();

// Output the messages as JSON
header('Content-Type: application/json');
echo json_encode($messages);
?>