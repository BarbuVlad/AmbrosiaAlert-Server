<?php
class Database {

  //Parametrii database (atribute ale clasei)
  private $host = 'localhost';

  private $db_name = 'ambrosia3';
  private $username = 'pcuser1';

  private $password = 'Admin1234!';
  private $conn;

  //Creaza conexiunea
  public function connect() {
    $this->conn = null;
    try {
      $this->conn = new PDO ('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch (PDOException $e) { echo 'Error at connection. ' . $e->getMessage();}

    return $this->conn;
  }

}

?>
