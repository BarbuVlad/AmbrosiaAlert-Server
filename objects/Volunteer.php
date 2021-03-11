<?php
class Volunteer {
  /**/

  //Atribute BD: conexiunea la baza de date si numele tabelului
  private $conn;
  private $table_name = "volunteers";

 //Atribute relative la tabel
 public $email;//PK
 public $phone;
 public $first_name;
 public $last_name;
 public $address;
 public $password;// needs hashing, maybe private
 public $blocked;

  //Constructor, primeste conexiunea la baza de data
  public function __construct($database){
    $this->conn = $database;
  }

  //CRUD methods for accessing the table

  //Get table data
  public function read() {
    //Creaza query - Create query
    $query = 'SELECT email, phone, first_name, last_name, address, blocked FROM ' . $this->table_name;

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->execute();

    return $stmt;
  }

  //Get a single trader_line
  public function read_single() {
    //Creaza query - Create query
    $query = 'SELECT email, phone, first_name, last_name, address, blocked FROM ' . $this->table_name . ' WHERE email = ? LIMIT 0,1';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    //Bind email
    $stmt->bindParam(1, $this->email);

    //Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //Set proprierties (public attributes)
    $this->phone = $row['phone'];
    $this->email = $row['email'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->address = $row['address'];
    $this->blocked = $row['blocked'];
  }

  //Retruneaza email & password
  public function read_login() {
    //Creaza query - Create query
    $query = 'SELECT email, password FROM ' . $this->table_name . ' WHERE email = :email LIMIT 0,1';
    //Prepare statement
    $stmt = $this->conn->prepare($query);
    //Clean data
    $this->email = trim($this->email);
    if (!preg_match('/^.*@.*\..*$/',$this->email)){return 1;};
    //Bind data
    $stmt->bindParam(':email', $this->email);

    // Execute query
    try{
    $stmt->execute();
    }catch(Exception $e){echo $e;}
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
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
  

  //Update a line form table
  public function update(){
    // Create query
    $query = 'UPDATE ' . $this->table_name . ' SET___ WHERE__';

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data

    //Bind data
    $stmt->bindParam(':', $this);
    $stmt->bindParam(':', $this);

    // Execute query
    if($stmt->execute()){
      return true;
    }

    //Error $stmt->error;
    return false;
  }

  //Block a volunteer - bs means blocked by server; ba blocked by admin
  public function blocked(){
    //Creaza query - Create query
    $query = 'UPDATE ' . $this->table_name . ' SET blocked = "bs" WHERE email = :email';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->bindParam(':email', $this->email);
    if($stmt->execute()){
      //echo $this->email . "a fost blocat";
      return true;
    }

    //Error $stmt->error;
    return false;
  }

  //Sterge o linie din tabel - Delete row
  public function delete() {
    //Creaza query - create query
    $query = 'DELETE FROM ' . $this->table_name . ' WHERE email = :email';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data

    // Bind data
    $stmt->bindParam(':email', $this->email);

    // Execute query
    if($stmt->execute()) {
      return true;
    }

    //Error $stmt->error;
    return false;
  }
}
?>
