<?php
class New_volunteer {
  /**/

  //Atribute BD: conexiunea la baza de date si numele tabelului
  private $conn;
  private $table_name = "new_volunteers";

 //Atribute relative la tabel
 public $phone;
 public $email;// PK
 public $first_name;
 public $last_name;
 public $address;
 public $password;// needs hashing, maybe private
 public $blocked;
 public $confirmations;

  //Constructor, primeste conexiunea la baza de data
  public function __construct($database){
    $this->conn = $database;
  }

  //CRUD methods for accessing the table

  //Get table data
  public function read() {
    //Creaza query - Create query
    $query = 'SELECT email, phone, first_name, last_name, address, blocked, confirmations FROM ' . $this->table_name . ';';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->execute();

    return $stmt;
  }

  //Retruneaza o singura linie - Get a single trader_line
  public function read_single() {
    //Creaza query - Create query
    $query = 'SELECT email, phone, first_name, last_name, address, blocked, confirmations FROM ' . $this->table_name . ' WHERE email = ? LIMIT 0,1';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Bind email
    $stmt->bindParam(1, $this->email);

    //Executa query - Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //seteaza proprietatile(atributele public) - Set proprierties (public attributes)
    $this->email = $row['email'];
    $this->phone = $row['phone'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->address = $row['address'];
    $this->blocked = $row['blocked'];
    $this->confirmations = $row['confirmations'];
  }

  //Retruneaza email & password
  public function read_login() {
    //Creaza query - Create query
    $query = 'SELECT email, password FROM ' . $this->table_name . ' WHERE email = :email LIMIT 0,1';
    //Prepare statement
    $stmt = $this->conn->prepare($query);
    //Clean data
    $this->email = trim($this->email);
    if (!preg_match('/^.*@.*\..*$/',$this->email)){return 1;}
    //Bind data
    $stmt->bindParam(':email', $this->email);

    // Execute query
    try{
    $stmt->execute();
    }catch(Exception $e){echo $e;}
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
  }

  public function _read_all_data() {
    //Create query
    $query = 'SELECT email, phone, first_name, last_name, address, blocked, confirmations, password FROM '
     . $this->table_name
     . " WHERE email=" . "'". $this->email . "'"
     . ' LIMIT 0, 1;';

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //Set proprierties (public attributes)
    $this->email = $row['email'];
    $this->phone = $row['phone'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->address = $row['address'];
    $this->blocked = $row['blocked'];
    $this->confirmations = $row['confirmations'];
    $this->password = $row['password'];
  }

  //Creaza o noua intrare in tabel - Create new entry in table
  public function create() {
    //Create query
    $query = "INSERT INTO " . $this->table_name . "(phone, email, first_name, last_name, address, password)" . " VALUES(:phone, :email, :first_name, :last_name, :address, :password)";

    //Prepare statement
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
    $query = 'UPDATE ' . $this->table_name . ' SET confirmations = :confirmations WHERE email = :email';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data

    //Bind data
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':confirmations', $this->confirmations);//modified

    //Executa query - Execute query
    if($stmt->execute()){
      return true;
    }

    //Error $stmt->error;
    return false;
  }

  //Block a new_volunteer - bs means blocked by server
  public function blocked(){
    //Creaza query - Create query
    $query = 'UPDATE ' . $this->table_name . ' SET blocked = "bs" WHERE email = :email';

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->bindParam(':email', $this->email);
    if($stmt->execute()){
     // echo $this->uid . "a fost blocat";
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
