<?php
echo "test_ban_user SCRIPT RUN";

include_once '../config/Database.php';
include_once '../objects/Blue_marker.php';
include_once '../objects/User.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a blue marker object
$blue = new Blue_marker($db);

// Instantiate a user object
$user = new User($db);

//Instanciate first values for latitude and longitude
$latitude = 42.4444444443;
$longitude = 42.5555555553;

//Instanciate a value_min that will be added in order to have different markers in same area
$value_min = 0.00001;

//Instanciate value_max that will be added in order to have different areas
$value_max = 0.1;

//Instanciate value for number of users
$user_no = 20;

//Instanciate minimum and maximum number of markers per user
$min = 0;
$max = 5;

//for to make users with random number of blue markers each
for($i = 100 ; $i < $user_no + 100 ; ++$i){
  //create a random variable for number of markers
  $random = rand($min, $max);
  //Creaza query - Create query
  $query = "INSERT INTO `users` (`uid`, `mac_user`, `blocked`) VALUES ($i, NULL, NULL)";
  //Pregateste statement - Prepare statement
  $stmt = $db->prepare($query);
  //Executa query - Execute query
  if($stmt->execute()){
    echo " User created ";
  }
  //second for that will create the markers
  for($j = 0 ; $j < $random ; ++$j){
    //instantiate values to marker , uid_user will be 3 because is not blocked
    $blue->uid_user = $i;
    $blue->latitude = $latitude;
    $blue->longitude = $longitude;
    $blue->time = date('Y-m-d-H-i-s');
    //create the marker
    if($blue->create()){
      echo " Marker created ";
    }
    //add value_min in order to avoid primary key violation
    $latitude = $latitude + $value_min;
    $longitude = $longitude + $value_min;
  }
  //at the end of first for add value_max in order to move to different area
  $latitude = $latitude + $value_max;
  $longitude = $longitude + $value_max;
}

?>
