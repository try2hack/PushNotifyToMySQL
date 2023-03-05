<?php
class Database {
  private $servername = "localhost";
  private $username = "admin";
  private $password = "1234";
  private $dbname = "noob";
  public $conn;

  public function __construct() {
    $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);

    if (!$this->conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
  }

  public function __destruct() {
    mysqli_close($this->conn);
  }
}
?>
