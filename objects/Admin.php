<?php
class Admin {
  /**/

  private $conn;
  private $table_name = "admins";

 //Atributes table
 public $name;
 public $password;

  //Constructor, primeste conexiunea la baza de data
  public function __construct($database){
    $this->conn = $database;
  }

  //CRUD methods for accessing the table

  //Get table data
  public function read() {
    //Create query
    $query = 'SELECT name FROM ' . $this->table_name;

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  //Get a single trader_line
  public function read_single() {
    //Create query
    $query = 'SELECT name, password FROM ' . $this->table_name . ' WHERE name = ? AND password = ? LIMIT 0,1';

    //Prepare statement
    $stmt = $this->conn->prepare($query);
    //Clean lowercase.
    //Bind UID
    $stmt->bindParam(1, $this->name);
    $stmt->bindParam(2, $this->password);

    //Executa query - Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //seteaza proprietatile(atributele public) - Set proprierties (public attributes)
    $this->name = $row['name'];
    $this->password = $row['password'];
  }

  //Creaza o noua intrare in tabel - Create new entry in table
  public function create() {
    //Creaza query - Create query
    $query = "INSERT INTO " . $this->table_name . " (name, password)" . " VALUES(:name, :password)";

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data (done in create.php)

    //Bind data
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':password', $this->password);

    //Executa query - Execute query
    if($stmt->execute()){
      return true;
    }

    //Error $stmt->error;
    return false;
  }

  //Retruneaza o lista de name & password
  public function read_login() {
    //Creaza query - Create query
    $query = 'SELECT name, password FROM ' . $this->table_name ;

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->execute();

    return $stmt;
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
