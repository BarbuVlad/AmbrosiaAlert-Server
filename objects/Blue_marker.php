<?php
class Blue_marker {
  /*
  Clasafnuidfhuid

  */

  //Atribute BD: conexiunea la baza de date si numele tabelului
  private $conn;
  private $table_name = "blue_markers";

 //Atribute relative la tabel
 //public $uid;
 public $latitude;
 public $longitude;
 public $uid_user;
 public $time;
  //Constructor, primeste conexiunea la baza de data
  public function __construct($database){
    $this->conn = $database;
  }

  //Metode CRUD pentru tabel - CRUD methods for accessing the table

  //Returneaza datele din tabel - Get table data
  public function read() {
    //Creaza query - Create query
    $query = 'SELECT latitude, longitude, uid_user, time FROM ' . $this->table_name;

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->execute();

    return $stmt;
  }

  //Retruneaza o singura linie - Get a single trader_line
  public function read_single() {
    //Creaza query - Create query
    $query = 'SELECT latitude, longitude, uid_user, time FROM ' . $this->table_name . ' WHERE latitude = ? AND longitude = ? LIMIT 0,1';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Bind
    $stmt->bindParam(1, $this->latitude);
    $stmt->bindParam(2, $this->longitude);

    //Executa query - Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);// if dose not exist, will return null

    //seteaza proprietatile(atributele public) - Set proprierties (public attributes)
    //$this->uid = $row['uid'];
    $this->latitude = $row['latitude'];
    $this->longitude = $row['longitude'];
    $this->uid_user = $row['uid_user'];
    $this->time = $row['time'];
  }

  //Creaza o noua intrare in tabel - Create new entry in table
  public function create() {
    //Creaza query - Create query
    $query = "INSERT INTO " . $this->table_name . " (latitude, longitude, uid_user, time)" . " VALUES(:latitude, :longitude, :uid_user, :time)";

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data

    //Bind data
    $stmt->bindParam(':latitude', floatval($this->latitude));
    $stmt->bindParam(':longitude', floatval($this->longitude));
    $stmt->bindParam(':uid_user', $this->uid_user);
    $stmt->bindParam(':time', $this->time);

    //Execute query

    try{
      if($stmt->execute()){
        return true;
      }
    } catch (PDOException $e) {
      if($stmt->errorInfo()[1] == '1062'){ // Special case (rare)
      //1062 MySQL -> Duplicate entry for key primary (Composite PK: longitude, latitude)
      //Randomize the value
      $random_change = round(rand(10, 99) * 0.0000001, 6); // 0.000 00X -> 1.5m-0.1m
      $this->longitude = $this->longitude + $random_change;
      $this->latitude = $this->latitude + $random_change;
      //re-bind
      $stmt->bindParam(':latitude', floatval($this->latitude));
      $stmt->bindParam(':longitude', floatval($this->longitude));
      //try again
      if($stmt->execute()){
        return true;}
    }
}
    //Error $stmt->error;
    return false;
  }

  //Update o linie din tabel - Update a line form table
  /*
  public function update(){
    //Creaza query - Create query
    $query = 'UPDATE ' . $this->table_name . ' SET MAC_user = :mac WHERE uid = :uid';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data

    //Bind data
    $stmt->bindParam(':uid', $this->uid);
    $stmt->bindParam(':mac', $this->mac_user);

    //Executa query - Execute query
    if($stmt->execute()){
      return true;
    }

    //Error $stmt->error;
    return false;
  }
*/
  //Sterge o linie din tabel - Delete row
  public function delete() {
    //create query
    $query = 'DELETE FROM ' . $this->table_name . ' WHERE longitude= :longitude AND latitude= :latitude';
    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data

    // Bind data
    $stmt->bindValue(':longitude', $this->longitude, PDO::PARAM_STR);
    $stmt->bindValue(':latitude', $this->latitude, PDO::PARAM_STR);

    // Execute query
    if($stmt->execute()) {
      return true;
    }

    //Error $stmt->error;
    return false;
  }
}
?>
