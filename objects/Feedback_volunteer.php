<?php
class Feedback_volunteer {
  /**/

  private $conn;
  private $table_name = "feedback_volunteers";

 //Atributes table
 public $email_volunteer;
 public $latitude;
 public $longitude;
 public $time;
 public $type;

  //Constructor, primeste conexiunea la baza de data
  public function __construct($database){
    $this->conn = $database;
  }

  //CRUD methods for accessing the table

  //Get table data
  public function read() {
    //Create query
    $query = 'SELECT email_volunteer, latitude, longitude, time, type FROM ' . $this->table_name;

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  //Get a single trader_line
  public function read_single_user() {
    //Create query
    $query = 'SELECT email_volunteer, latitude, longitude, time, type FROM ' . $this->table_name . ' WHERE email_volunteer = ?';

    //Prepare statement
    $stmt = $this->conn->prepare($query);
    //Clean lowercase.
    //Bind UID
    $stmt->bindParam(1, $this->email_volunteer);

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  //Create new entry in table
  public function create() {
    //Create query
    $query = "INSERT INTO " . $this->table_name . " (email_volunteer, latitude, longitude, time, type)" . 
    " VALUES(:email_volunteer, :latitude, :longitude, :time, :type)";

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data (done in create.php)

    //Bind data
    $stmt->bindParam(':email_volunteer', $this->email_volunteer);
    $stmt->bindParam(':latitude', $this->latitude);
    $stmt->bindParam(':longitude', $this->longitude);
    $stmt->bindParam(':time', $this->time);
    $stmt->bindParam(':type', $this->type);

    //Execute query
  try{
      if($stmt->execute()){
        return 0;
      }
    } catch (PDOException $e){
      if($stmt->errorInfo()[1] == "1062"){
        return 1;
      }
    }

    //Error $stmt->error;
    return false;
  }

  public function delete_user() {
    //Creaza query - create query
    $query = 'DELETE FROM ' . $this->table_name . ' WHERE email_volunteer = :email_volunteer';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data

    // Bind data
    $stmt->bindParam(':email_volunteer', $this->email_volunteer);

    // Execute query
    if($stmt->execute()) {
      return true;
    }

    //Error $stmt->error;
    return false;
  }


}//delete
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

  //Sterge o linie din tabel - Delete row
  public function delete() {
    //Creaza query - create query
    $query = 'DELETE FROM ' . $this->table_name . ' WHERE uid = :uid';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data

    // Bind data
    $stmt->bindParam(':uid', $this->uid);

    // Execute query
    if($stmt->execute()) {
      return true;
    }

    //Error $stmt->error;
    return false;
  }
}
*/
?>
