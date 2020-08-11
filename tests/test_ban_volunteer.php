<?php
echo "test_ban_volunteer SCRIPT RUN";

include_once '../config/Database.php';
include_once '../objects/Red_marker.php';
include_once '../objects/Volunteer.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a red marker object
$red = new Red_marker($db);

// Instantiate a volunteer object
$volunteer = new Volunteer($db);

//Instanciate first values for latitude and longitude
$latitude = 42.4444444443;
$longitude = 42.5555555553;

//Instanciate a value_min that will be added in order to have different markers in same area
$value_min = 0.00001;

//Instanciate value_max that will be added in order to have different areas
$value_max = 0.1;

//Instanciate value for number of volunteers
$volunteer_no = 20;

//Instanciate minimum and maximum number of markers per volunteer
$min = 0;
$max = 5;

//for to make volunteers with random number of red markers each
for($i = 100 ; $i < $volunteer_no + 100 ; ++$i){
  //create a random variable for number of markers
  $random = rand($min, $max);
  //Creaza query - Create query
  $query = "INSERT INTO `volunteers` (`uid`, `phone`, `email`, `password`, `first_name`, `last_name`, `address`, `blocked`) VALUES ($i, '', '', '', NULL, NULL, NULL, NULL)";
  //Pregateste statement - Prepare statement
  $stmt = $db->prepare($query);
  //Executa query - Execute query
  if($stmt->execute()){
    echo " Volunteer created ";
  }
  //second for that will create the markers
  for($j = 0 ; $j < $random ; ++$j){
    //instantiate values to marker , uid_volunteer will be 3 because is not blocked
    $red->uid_volunteer = $i;
    $red->latitude = $latitude;
    $red->longitude = $longitude;
    $red->time = date('Y-m-d-H-i-s');
    //create the marker
    if($red->create()){
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
