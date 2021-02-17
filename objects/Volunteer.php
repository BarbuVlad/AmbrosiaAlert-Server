<?php
class Volunteer {
  /**/

  //Atribute BD: conexiunea la baza de date si numele tabelului
  private $conn;
  private $table_name = "volunteers";

 //Atribute relative la tabel
 public $uid;
 public $phone;
 public $email;
 public $first_name;
 public $last_name;
 public $address;
 public $password;// needs hashing, maybe private
 public $blocked;

  //Constructor, primeste conexiunea la baza de data
  public function __construct($database){
    $this->conn = $database;
  }

  //Metode CRUD pentru tabel - CRUD methods for accessing the table

  //Returneaza datele din tabel - Get table data
  public function read() {
    //Creaza query - Create query
    $query = 'SELECT uid, phone, email, first_name, last_name, address, blocked FROM ' . $this->table_name . ' ORDER BY uid ASC;';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->execute();

    return $stmt;
  }

  //Retruneaza o singura linie - Get a single trader_line
  public function read_single() {
    //Creaza query - Create query
    $query = 'SELECT uid, phone, email, first_name, last_name, address, blocked FROM ' . $this->table_name . ' WHERE uid = ? LIMIT 0,1';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Bind UID
    $stmt->bindParam(1, $this->uid);

    //Executa query - Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //seteaza proprietatile(atributele public) - Set proprierties (public attributes)
    $this->uid = $row['uid'];
    $this->phone = $row['phone'];
    $this->email = $row['email'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->address = $row['address'];
    $this->blocked = $row['blocked'];
  }

  //Retruneaza o lista de email & password
  public function read_login() {
    //Creaza query - Create query
    $query = 'SELECT email, password, uid FROM ' . $this->table_name ; //optimizare: 'WHERE email=...'; apoi se compara doar parola in login.php

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->execute();

    return $stmt;
  }

  //Creaza o noua intrare in tabel - Create new entry in table
  public function create() {
    //Creaza query - Create query
    $query = "INSERT INTO " . $this->table_name . "(phone, email, first_name, last_name, address, password)" . " VALUES(:phone, :email, :first_name, :last_name, :address, :password)";

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data (done in create.php)

    //Bind data
    $stmt->bindParam(':phone', $this->phone);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':first_name', $this->first_name);
    $stmt->bindParam(':last_name', $this->last_name);
    $stmt->bindParam(':address', $this->address);
    $stmt->bindParam(':password', $this->password);


    //Create - but check for email dupicate Unique Key
    try{
      //Execute query
      if($stmt->execute()){
        return 1;
      }
    } catch (PDOException $e) {
        //Error $stmt->error;
        if($stmt->errorInfo()[1] == '1062'){
          return 2;
        }
      }

    return false;
  }
  

  //Update o linie din tabel - Update a line form table
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

  //Block a volunteer - bs means blocked by server
  public function blocked(){
    //Creaza query - Create query
    $query = 'UPDATE ' . $this->table_name . ' SET blocked = "bs" WHERE uid = :uid';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->bindParam(':uid', $this->uid);
    if($stmt->execute()){
      echo $this->uid . "a fost blocat";
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
?>
