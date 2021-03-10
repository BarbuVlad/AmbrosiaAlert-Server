<?php
class Grey_marker {
  /**/

  //Atribute BD: conexiunea la baza de date si numele tabelului
  private $conn;
  private $table_name = "grey_markers";

 //Atribute relative la tabel
 //public $uid;
 public $latitude;
 public $longitude;
 public $uid_volunteer;
 public $time_of_delete; // refers to red_marker (when was red_marker deleted)

  //Constructor, primeste conexiunea la baza de data
  public function __construct($database){
    $this->conn = $database;
  }

  //CRUD methods for accessing the table
  //Get table data
  public function read() {
    //Create query
    $query = 'SELECT latitude, longitude, email_volunteer, time_of_delete FROM ' . $this->table_name;

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  //Retruneaza o singura linie - Get a single trader_line
  public function read_single() {
    //Create query
    $query = 'SELECT latitude, longitude, email_volunteer, time_of_delete FROM ' . $this->table_name . ' WHERE latitude = ? AND longitude = ? LIMIT 0,1';

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Bind
    $stmt->bindParam(1, $this->latitude);
    $stmt->bindParam(2, $this->longitude);

    //Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);// if dose not exist, will return null

    //Set proprierties (public attributes)
    //$this->uid = $row['uid'];
    $this->latitude = $row['latitude'];
    $this->longitude = $row['longitude'];
    $this->uid_user = $row['email_volunteer'];
    $this->time_of_delete = $row['time_of_delete'];
  }

  //Creaza o noua intrare in tabel - Create new entry in table
  public function create() {
    //Creaza query - Create query
    $query = "INSERT INTO " . $this->table_name . " (latitude, longitude, email_volunteer, time_of_delete)" . " VALUES(:latitude, :longitude, :uid_volunteer, :time_of_delete)";

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data

    //Bind data
    $stmt->bindParam(':latitude', floatval($this->latitude));
    $stmt->bindParam(':longitude', floatval($this->longitude));
    $stmt->bindParam(':email_volunteer', $this->uid_volunteer);
    $stmt->bindParam(':time_of_delete', $this->time_of_delete);

    //Executa query - Execute query
      if($stmt->execute()){
        return true;
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
    //Creaza query - create query
    $query = 'DELETE FROM ' . $this->table_name . ' WHERE longitude= :longitude AND latitude= :latitude';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data

    // Bind data
    $stmt->bindParam(':longitude', $this->longitude);
    $stmt->bindParam(':latitude', $this->latitude);

    // Execute query
    if($stmt->execute()) {
      return true;
    }

    //Error $stmt->error;
    return false;
  }
}
?>
