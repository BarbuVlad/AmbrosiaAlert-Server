<?php
class Red_marker {
  /**/

  //Atribute BD: conexiunea la baza de date si numele tabelului
  private $conn;
  private $table_name = "red_markers";

 //Atribute relative la tabel
 //public $uid;
 public $latitude;
 public $longitude;
 public $email_volunteer;
 public $time;
 public $likes;
 public $dislikes;
 //public $confirmations;
 //public $radius;

  //Constructor, primeste conexiunea la baza de data
  public function __construct($database){
    $this->conn = $database;
  }

  //Metode CRUD pentru tabel - CRUD methods for accessing the table

  //Returneaza datele din tabel - Get table data
  public function read() {
    //Creaza query - Create query
    $query = 'SELECT latitude, longitude, email_volunteer, time, likes, dislikes, radius FROM ' . $this->table_name;

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Executa query - Execute query
    $stmt->execute();

    return $stmt;
  }

  public function read_area() {
    /*
    takes a set of coordinates and returns all red markers in that  area
    ! For now this is hard-coded and not optimized; it takes roughly an area of 30 sq km
    - The problem of converting coordinates to distance can be problematic, planet Earth is an imperfect sphere
    https://www.usgs.gov/faqs/how-much-distance-does-a-degree-minute-and-second-cover-your-maps?qt-news_science_products=0#qt-news_science_products
    A good optimisation must be made when it comes to non-cluster indexes in DB
    Tests must be performed to determin if this approach is better. (in the end this will compare all markers against some values )
    */
    //This 0.2 difference represents roughly 31.11 km
    $query = 'SELECT latitude, longitude, email_volunteer, time, likes, dislikes, radius FROM ' . $this->table_name .
    ' WHERE (latitude BETWEEN :latitude_down  AND :latitude_up ) AND '.
    '(longitude BETWEEN :longitude_down AND :longitude_up)';
    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);
    //Bind
    $stmt->bindParam(":latitude_down", floatval($this->latitude-0.2));
    $stmt->bindParam(":latitude_up", floatval($this->latitude+0.2));
    $stmt->bindParam(":longitude_down", floatval($this->longitude-0.2));
    $stmt->bindParam(":longitude_up", floatval($this->longitude+0.2));

    $stmt->execute();

    return $stmt;
  }

  //Get a single trader_line
  public function read_single() {
    //Creaza query - Create query
    $query = 'SELECT latitude, longitude, email_volunteer, time, likes, dislikes, radius FROM ' . $this->table_name . ' WHERE latitude = ? AND longitude = ? LIMIT 0,1';

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
    // -- makes no sense to pass data to longitude and latitude again
    $this->latitude = $row['latitude'];
    $this->longitude = $row['longitude'];
    $this->uid_volunteer = $row['email_volunteer'];
    $this->time = $row['time'];
    $this->likes = $row['likes'];
    $this->dislikes = $row['dislikes'];
    $this->radius = $row['radius'];
  }

  //Creaza o noua intrare in tabel - Create new entry in table
  public function create() {
    //-- Create or increment marker --
    include_once '../../config/_distance.php';
    $markers = $this->_read_intersecting_markers($this->latitude)->fetchAll();
    //for every marker check the distance
    //make a list of markers and intersecting value
    $intersecting_markers=[];
    foreach($markers as $marker){
      //if the markers intersect, then do not create red marker
      //ideal: increment for the closest red_marker
      if(distance($this->latitude, $this->longitude, $marker['latitude'], $marker['longitude']) < $marker['radius']+40){//intersect more than 10 meters
        try{
          //increment the confirmations of that marker
            $this->_increment_confrimations($marker['confirmations']+1,$marker['latitude']);
            return 0;
        } catch(Exception $e){
            return 1;
        }
      }
    }
    //No intersection => create new marker
    // Create query
    $query = "INSERT INTO " . $this->table_name . " (latitude, longitude, email_volunteer, time)" . " VALUES(:latitude, :longitude, :email_volunteer, :time)";

    //Pregateste statement - Prepare statement
    $stmt = $this->conn->prepare($query);

    //Clean data

    //Bind data
    $stmt->bindParam(':latitude', floatval($this->latitude));
    $stmt->bindParam(':longitude', floatval($this->longitude));
    $stmt->bindParam(':email_volunteer', $this->email_volunteer);
    $stmt->bindParam(':time', $this->time);

    //Executa query - Execute query
    try{
      if($stmt->execute()){
        return 2;
      }
    } catch (PDOException $e) {
      return 3;
  }
    //Error $stmt->error;
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

  //Methods not intended for API requests
    public function _increment_confrimations($confirmations, $latitude){
      $query = 'UPDATE ' . $this->table_name . ' SET likes= :likes WHERE latitude = :latitude ';

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(':likes', $confirmations);
      $stmt->bindParam(':latitude', $latitude);

      try{
        if($stmt->execute()){
          return true;
        }
      }catch(PDOException $e){
        echo 'ERROR:' . $e;
      }

    }

    public function _read_intersecting_markers($latitude, $one_meter=0.000015){ // 0.000010 = 1.11meters

      $query = 'SELECT latitude, longitude, confirmations, radius FROM ' . $this->table_name .
      ' WHERE latitude BETWEEN :latitude_down  AND :latitude_up ';

      $stmt = $this->conn->prepare($query);

      //get intersecting value
      $intersecting_value = is_numeric(strval($latitude)[7]) ? strval($latitude)[7]*$one_meter : 0.0;
      //exemple: for latitude=10.1234567; $intersecting_value will be 5*0.00001 = 55 meters
      //Bind
      $stmt->bindParam(":latitude_down", floatval($latitude - $intersecting_value));
      $stmt->bindParam(":latitude_up", floatval($latitude + $intersecting_value));

      $stmt->execute();

      return $stmt;
    }

}


?>
