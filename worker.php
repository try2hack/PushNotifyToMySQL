<?php
require_once "db.php";

class Notify {
  private $conn;

  public function __construct($db) {
    $this->conn = $db->conn;
  }

  public function searchAndUpdate($number) {
    // Execute query
    $sql = "SELECT * FROM notify";
    $result = mysqli_query($this->conn, $sql);

    // Check for errors
    if (!$result) {
      die("Query failed: " . mysqli_error($this->conn));
    }

    // Fetch data and compare with input number
    while ($row = mysqli_fetch_assoc($result)) {
      $text = preg_replace("/[^0-9.]/", "", $row["text"]);
      if ($text == $number) {
        // Update status to "read"
        $id = $row["id"];
        $sql = "UPDATE notify SET status='read' WHERE id=$id";
        if (mysqli_query($this->conn, $sql)) {
          echo "Status updated successfully";
        } else {
          echo "Error updating status: " . mysqli_error($this->conn);
        }
        break;
      }
    }

    // Free result set
    mysqli_free_result($result);
  }
}

// Check if form submitted
if (isset($_POST["number"])) {
  $number = $_POST["number"];

  // Connect to database
  $db = new Database();

  // Search and update
  $notify = new Notify($db);
  $notify->searchAndUpdate($number);
}
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
