<?php
echo "test_ban_volunteer SCRIPT RUN";

include_once '../config/Database.php';
include_once '../objects/Yellow_marker.php';
include_once '../objects/New_volunteer.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a yellow marker object
$yellow = new Yellow_marker($db);

// Instantiate a new_volunteer object
$new_volunteer = new New_volunteer($db);

//Instanciate first values for latitude and longitude
$latitude = 42.4444444443;
$longitude = 42.5555555553;

//Instanciate a value_min that will be added in order to have different markers in same area
$value_min = 0.00001;

//Instanciate value_max that will be added in order to have different areas
$value_max = 0.1;

//Instanciate value for number of new_volunteers
$new_volunteer_no = 20;

//Instanciate minimum and maximum number of markers per new_volunteer
$min = 0;
$max = 5;

//for to make new_volunteers with random number of yellow markers each
for($i = 100 ; $i < $new_volunteer_no + 100 ; ++$i){
  //create a random variable for number of markers
  $random = rand($min, $max);
  //Creaza query - Create query
  $query = "INSERT INTO `new_volunteers` (`uid`, `phone`, `email`, `password`, `first_name`, `last_name`, `address`, `blocked`, `confirmations`) VALUES ($i, '', '', NULL, NULL, NULL, NULL, NULL, '0');";
  //Pregateste statement - Prepare statement
  $stmt = $db->prepare($query);
  //Executa query - Execute query
  if($stmt->execute()){
    echo " New_volunteer created ";
  }
  //second for that will create the markers
  for($j = 0 ; $j < $random ; ++$j){
    //instantiate values to marker , uid_volunteer will be 3 because is not blocked
    $yellow->uid_volunteer = $i;
    $yellow->latitude = $latitude;
    $yellow->longitude = $longitude;
    $yellow->time = date('Y-m-d-H-i-s');
    //create the marker
    if($yellow->create()){
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
