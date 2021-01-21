<?php
// Send headers with HTTP
header('Access-Control-Allow-Origin: *'); // anyone can read
header('Content-Type: application/json'); // returns/accepts a json format file

include_once '../config/Database.php';
include_once '../objects/New_volunteer.php';
include_once '../objects/Volunteer.php';
include_once '../objects/Yellow_marker.php';

include_once '../config/_writeFile.php';

//Instantiate a name and a string to be written in the text file
$name = "validate_new_volunteer_test";
$str = "\n___________________________________________________________________\n\n This test will run after using the corresponding scenario.";

// Instantiate DB & connect //cine face request?? un new_volunteer minimal, ...
$database = new Database();
$db = $database->connect();

// Instantiate a table object
$new_volunteer = new New_volunteer($db);
$volunteer = new Volunteer($db);
$yellow_marker = new Yellow_marker($db);

// new_volunteer query
$result = $new_volunteer->read();

// yellow_marker query
$result3 = $yellow_marker->read();
$yellow_arr = array();
while($rows3 = $result3->fetch(PDO::FETCH_ASSOC)){
  array_push($yellow_arr,$rows3);
}

while($row = $result->fetch(PDO::FETCH_ASSOC)){
  //echo json_encode(array('message' => $row ));
  $str = $str."\n___________________________________________________________________\n\n New volunteer id: ".$row['uid']."\n Confirmations: ".$row['confirmations']."\n";
  if(intval($row['confirmations']) >= 2){
    //if a new volunteer has 2 confirmations, then make it a valid volunteer

    //give the current uid to new_volunteer
    $new_volunteer->uid = $row['uid'];

    //call function read_login for new_volunteer in order to get $password
    $result2 = $new_volunteer->read_login();
    $row2 = $result2->fetch(PDO::FETCH_ASSOC);
    //echo json_encode(array('message' => $row2['password']));

    //pass data to volunteer
    $volunteer->phone = $row['phone'];
    $volunteer->email = $row['email'];
    //read will not give the password , try sth with read_login
    $volunteer->password = $row2['password'];
    $volunteer->first_name = isset($row['first_name']) ? $row['first_name'] : 'null';
    $volunteer->last_name = isset($row['last_name']) ? $row['last_name'] : 'null';
    $volunteer->address = isset($row['address']) ? $row['address'] : 'null';
    $volunteer->blocked = $row['blocked'];

    try{
  $db->beginTransaction();
  //create a new volunteer with credentials of a new_volunteer
  $a = $volunteer->create();

  //in the future this shoud be modified according to preferences

  //read all yellow markers for each new_volunteer
  foreach($yellow_arr as $row3){
    if($new_volunteer->uid == $row3['uid_volunteer']){
        //for each new_volunteer that becomes a valid volunteer, delete his yellow markers
        //might not be so efficient because the list remains the same size
        $yellow_marker->latitude = $row3['latitude'];
        $yellow_marker->longitude = $row3['longitude'];
        $yellow_marker->delete();
      }
    }

  //delete that new volunteer
  $b = $new_volunteer->delete();

  $db->commit();
  $str = $str."New volunteer has been validated !";
  }catch(PDOException $e){
    http_response_code(503);
    //echo json_encode(array('message' => 'ERROR occurred. Action cancelled !'));
    echo json_encode(array('message' => $e));
    $db->rollBack();
    $str = $str."New volunteer has NOT been validated ! \n Here is why: ".$e."\n";
    exit(1);
  }
  }
}
http_response_code(200);
echo json_encode(array('message' => 'Action performed!'));
$str = $str."\n___________________________________________________________________\n\n END OF TEST \n___________________________________________________________________\n\n";
writeFile($name,$str);
?>
