<?php
/*

*/
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for user data

  include_once '../../config/Database.php';
  include_once '../../objects/Yellow_marker.php';
  include_once '../../objects/New_volunteer.php';
  // Instantiate DB & connect //cine face request?? un user minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $yellow_marker = new Yellow_marker($db);
  $new_volunteer = new New_volunteer($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to new_volunteer
  $new_volunteer->email = $data['email_volunteer'];
  //read this one new_volunteer
  $new_volunteer->read_single();

  if(strcmp($new_volunteer->blocked, "bs") == 0){
    //if user is blocked throw error code 403 Forbidden
    http_response_code(403);
    echo json_encode(array('message' => 'ERROR occurred. Volunteer no longer has rights'));
  } else {
  $yellow_marker->latitude = $data['latitude'];
  $yellow_marker->longitude = $data['longitude'];
  $yellow_marker->email_volunteer = isset($data['email_volunteer']) ? $data['email_volunteer'] : null;//if exists
  $yellow_marker->time = date('Y-m-d-H-i-s');
  if($yellow_marker->create()){
      http_response_code(200);
      echo json_encode(array('message' => 'yellow marker created'));

  } else {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Yellow marker NOT created'));
  }
}
?>
