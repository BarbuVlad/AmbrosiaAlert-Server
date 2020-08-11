<?php
echo "test_ban_volunteer SCRIPT RUN";

include_once '../config/Database.php';
include_once '../objects/New_volunteer.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a new_volunteer object
$new_volunteer = new New_volunteer($db);

//Instanciate value for number of new_volunteers
$new_volunteer_no = 20;

//Instanciate minimum and maximum number of confirmation per new_volunteer
$min = 0;
$max = 4;

//for to make new_volunteers with random number of yellow markers each
for($i = 100 ; $i < $new_volunteer_no + 100 ; ++$i){
  //create a random variable for number of confirmations
  $random = rand($min, $max);
  //Creaza query - Create query
  $query = "INSERT INTO `new_volunteers` (`uid`, `phone`, `email`, `password`, `first_name`, `last_name`, `address`, `blocked`, `confirmations`) VALUES ($i, '', '', NULL, NULL, NULL, NULL, NULL, $random)";
  //Pregateste statement - Prepare statement
  $stmt = $db->prepare($query);
  //Executa query - Execute query
  if($stmt->execute()){
   echo " New_volunteer created ";
  }
}

?>
