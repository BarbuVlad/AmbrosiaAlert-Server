<?php

header('Access-Control-Allow-Origin: *'); // can be read by anyone
header('Content-Type: application/json'); // returns a json format file
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
// json header can be passed for user data

include_once '../../config/Database.php';
include_once '../../objects/Yellow_marker.php';
include_once '../../objects/Red_marker.php';
include_once '../../objects/New_volunteer.php';
include_once '../../objects/Volunteer.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a table object
$yellow_marker = new Yellow_marker($db);
$new_volunteer = new New_volunteer($db);
$red_marker = new Red_marker($db);
$volunteer = new Volunteer($db);

//get data from request
$data = json_decode(file_get_contents("php://input"), true);

$yellow_marker->latitude = $data['latitude'];
$yellow_marker->longitude = $data['longitude'];
$new_volunteer->uid = $data['uid_volunteer'];
$volunteer->uid = $data['uid_volunteer_confirm'];

//create a red marker with data from yellow marker
$red_marker->latitude = $data['latitude'];
$red_marker->longitude = $data['longitude'];
$red_marker->time = date('Y-m-d-H-i-s');
$red_marker->uid_volunteer = $data['uid_volunteer_confirm'];

// volunteer query
$new_volunteer->read_single();

//increase number of confirmations by 1
$conf = $new_volunteer->confirmations + 1;
$new_volunteer->confirmations = $conf;

//read this one volunteer
$volunteer->read_single();

if(strcmp($new_volunteer->blocked, "bs") == 0 || strcmp($volunteer->blocked, "bs") == 0){
    //if user is blocked throw error code 403 Forbidden
    http_response_code(403);
    echo json_encode(array('message' => 'ERROR_occurred. Volunteer no longer has rights'));
    exit(0);
} else {
    try{
      $db->beginTransaction();
      //update with increased number
      $a = $new_volunteer->update();

      //create red marker
      $b = $red_marker->create();

      //delete yellow marker
      $c = $yellow_marker->delete();

      $db->commit();
  }catch(PDOException $e){
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Action cancelled !'));
    $db->rollBack();
    exit(0);
  }
}
http_response_code(200);
echo json_encode(array('message' => 'Action performed!'));
?>
