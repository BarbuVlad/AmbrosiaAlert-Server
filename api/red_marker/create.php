<?php
/*
This function attemts to create a new red marker.
Condtion: this new marker must be within 100 meters from any other red marker.
If it is not, then the confirmation factor of that other red marker will increment by 1.
*/
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for user data

  include_once '../../config/Database.php';
  include_once '../../objects/Red_marker.php';

  include_once '../../objects/Volunteer.php';
  include_once '../../config/_distance.php';
  // Instantiate DB & connect

  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $volunteer = new Volunteer($db);
  $red_marker = new Red_marker($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request

  //pass data to volunteer

  $volunteer->uid = $data['uid_volunteer'];

  //read this one volunteer
  if($data['uid_volunteer'] != null){

    $volunteer->read_single();
    //read does not happen , result is null
  //echo json_encode(array('message' => $volunteer->phone));
    if(strcmp($volunteer->blocked, "bs") == 0){
      //if user is blocked throw error code 403 Forbidden
      http_response_code(403);
      echo json_encode(array('message' => 'ERROR occurred. Volunteer no longer has rights'));
      exit(1);
    }
  }
  //-- Create or increment marker --
  //pass data to red marker

  $red_marker->latitude = $data['latitude'];
  $red_marker->longitude = $data['longitude'];
  $red_marker->uid_volunteer = $data['uid_volunteer'];//if exists
  $red_marker->time = date('Y-m-d-H-i-s');

  $created = $red_marker->create();
  if($created==0){
      http_response_code(200);
      echo json_encode(array('message' => 'red marker NOT created! In area of other marker (incremented)'));
      exit(0);
  } elseif ($created == 1) {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Red marker in area of other marker; NO increment'));
    exit(2);
  } elseif ($created == 2) {
    http_response_code(200);
    echo json_encode(array('message' => 'red marker created!')); 
    exit(0); 
  } else {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Red marker NOT created'));
    exit(2);
  }
?>
