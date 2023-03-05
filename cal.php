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

// Check if form submitted
if (isset($_POST["number"])) {
  $number = $_POST["number"];

  // Execute query
  $sql = "SELECT * FROM notify";
  $result = mysqli_query($conn, $sql);

  // Check for errors
  if (!$result) {
    die("Query failed: " . mysqli_error($conn));
  }

  // Fetch data and compare with input number
  while ($row = mysqli_fetch_assoc($result)) {
    $text = preg_replace("/[^0-9.]/", "", $row["text"]);
    if ($text == $number) {
      // Update status to "read"
      $id = $row["id"];
      $sql = "UPDATE notify SET status='read' WHERE id=$id";
      if (mysqli_query($conn, $sql)) {
        echo "Status updated successfully";
      } else {
        echo "Error updating status: " . mysqli_error($conn);
      }
      break;
    }
  }

  // Free result set
  mysqli_free_result($result);
}

// Close connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Search and Update</title>
</head>
<body>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="number">Enter a number:</label>
    <input type="text" id="number" name="number">
    <input type="submit" value="Submit">
  </form>

</body>
</html>
