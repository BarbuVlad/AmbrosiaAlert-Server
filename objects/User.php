<?php
class User {
  /**/

  //Atribute BD: conexiunea la baza de date si numele tabelului
  private $conn;
  private $table_name = "users";

 //Atribute relative la tabel
 public $vendor_id;
 public $blocked;

  //Constructor, primeste conexiunea la baza de data
  public function __construct($database){
    $this->conn = $database;
  }

  //CRUD methods for accessing the table

  //Get table data
  public function read() {
    //Create query
    $query = 'SELECT vendor_id, blocked FROM ' . $this->table_name . ';';// ORDER BY vendor_id ASC

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  //Get a single trader_line
  public function read_single() {
    //Create query
    $query = 'SELECT vendor_id, blocked FROM ' . $this->table_name . ' WHERE vendor_id = ? LIMIT 0,1';

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Bind UID
    $stmt->bindParam(1, $this->vendor_id);

    //Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Set proprierties (public attributes)
    $this->vendor_id = $row['vendor_id'];
    $this->blocked = $row['blocked'];
  }

  //Create new entry in table
  public function create() {
    //Create query
    $query = "INSERT INTO " . $this->table_name . " (vendor_id)" . " VALUES(:vendor)";

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data (done in create.php)

    //Bind data
    $stmt->bindParam(':vendor', $this->vendor_id);

    //Execute query
    if($stmt->execute()){
      return true;
    }

    //Error $stmt->error;
    return false;
  }
  //Block a user - bs means blocked by server
  public function blocked(){
    //Create query
    $query = 'UPDATE ' . $this->table_name . ' SET blocked = "bs" WHERE vendor_id = :vendor_id';

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->bindParam(':vendor_id', $this->vendor_id);
    if($stmt->execute()){
     // echo $this->uid . "blocked";
      return true;
    }

    //Error $stmt->error;
    return false;
  }

  //Sterge o linie din tabel - Delete row
  public function delete() {
    //Creaza query - create query
    $query = 'DELETE FROM ' . $this->table_name . ' WHERE vendor_id = :vendor_id';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data

    // Bind data
    $stmt->bindParam(':vendor_id', $this->vendor_id);

    // Execute query
    if($stmt->execute()) {
      return true;
    }

    //Error $stmt->error;
    return false;
  }
}

/*
  //-->Must be refactored to delete row and insert new one. Is this useful?
  //Update a line form table

  public function update(){
    //Create query
    $query = 'UPDATE ' . $this->table_name . ' SET vendor_id = :vendor WHERE vendor_id = :vendor_same';
    //Prepare statement
    $stmt = $this->conn->prepare($query);
    //Clean data
    //Bind data
    $stmt->bindParam(':vendor_same', $this->vendor_id);
    $stmt->bindParam(':vendor', $this->vendor_id);
    //Executa query - Execute query
    if($stmt->execute()){
      return true;
    }
    //Error $stmt->error;
    return false;
  }
  */
?>
