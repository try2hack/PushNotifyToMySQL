<?php

$servername = "localhost";
$username = "admin";
$password = "1234";
$dbname = "noob";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Execute query
$sql = "SELECT * FROM notify LIMIT 10";
$result = mysqli_query($conn, $sql);

// Check for errors
if (!$result) {
  die("Query failed: " . mysqli_error($conn));
}

// Fetch data and print as JSON
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
  $data[] = array(
    "id" => $row["id"],
    "title" => $row["title"],
    "text" => $row["text"],
    "status" => $row["status"]
  );
}
echo json_encode($data);

// Free result set
mysqli_free_result($result);

// Close connection
mysqli_close($conn);

?>
