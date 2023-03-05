<?php
declare(strict_types=1);
require_once "db.php";

class Notify {
  private $conn;

  public function __construct($db) {
    $this->conn = $db->conn;
  }

  public function searchAndUpdate($number) {
    // Prepare statement to prevent SQL injection
    $stmt = mysqli_prepare($this->conn, "SELECT * FROM notify WHERE text LIKE CONCAT('%', ?, '%')");
    mysqli_stmt_bind_param($stmt, "s", $number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

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
        $stmt = mysqli_prepare($this->conn, "UPDATE notify SET status='read' WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
          echo "Status updated successfully";
        } else {
          echo "Error updating status: " . mysqli_error($this->conn);
        }
        break;
      }
    }

    // Free result set
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
  }
}

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["number"])) {
  $number = htmlspecialchars($_POST["number"]);

  // Connect to database
  $db = new Database();
  $conn = $db->conn;

  // Search and update
  $notify = new Notify($db);
  $notify->searchAndUpdate($number);

  // Close connection
  mysqli_close($conn);
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
