<?php
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
$new_volunteers_list = $new_volunteer->_read_all_data();

// yellow_marker query
$yellow_markers_list = $yellow_marker->read();
$yellow_arr = array();
while($row = $yellow_markers_list->fetch(PDO::FETCH_ASSOC)){
  array_push($yellow_arr,$row);
}

while($row = $new_volunteers_list->fetch(PDO::FETCH_ASSOC)){
  $str = $str."\n___________________________________________________________________\n\n New volunteer id: ".$row['uid']."\n Confirmations: ".$row['confirmations']."\n";
  if($row['blocked'] != null){ 
    $str = $str . "Skip blocked volunteer... " . "({$row['blocked']})\n" ;
    continue;
  }

  if(intval($row['confirmations']) >= 2){
    //if a new volunteer has 2 confirmations, then make it a valid volunteer

    //give the current uid to new_volunteer
    $new_volunteer->uid = $row['uid'];
  
    //pass data to volunteer
    $volunteer->phone = $row['phone'];
    $volunteer->email = $row['email'];
    $volunteer->password = $row['password'];
    $volunteer->first_name = isset($row['first_name']) ? $row['first_name'] : 'null';
    $volunteer->last_name = isset($row['last_name']) ? $row['last_name'] : 'null';
    $volunteer->address = isset($row['address']) ? $row['address'] : 'null';
    //$volunteer->blocked = $row['blocked'];

    try{
  $db->beginTransaction();
  //create a new volunteer with credentials of a new_volunteer
  $a = $volunteer->create();

  //in the future this shoud be modified according to preferences
  //read all yellow markers for each new_volunteer
  foreach($yellow_arr as $yellow){
    if($new_volunteer->uid == $yellow['uid_volunteer']){
        //for each new_volunteer that becomes a valid volunteer, delete his yellow markers
        //might not be so efficient because the list remains the same size
        $yellow_marker->latitude = $yellow['latitude'];
        $yellow_marker->longitude = $yellow['longitude'];
        $yellow_marker->delete();
      }
    }

  //delete that new volunteer
  $b = $new_volunteer->delete();

  $db->commit();
  $str = $str."New volunteer has been validated !";
  }catch(PDOException $e){
    $db->rollBack();
    $str = $str."New volunteer has NOT been validated ! \n ERROR: ".$e."\n";
    sleep(1);
   }
  }
}
$str = $str."\n___________________________________________________________________\n\n END OF TEST \n___________________________________________________________________\n\n";
//writeFile($name,$str);
//echo $str;
?>
