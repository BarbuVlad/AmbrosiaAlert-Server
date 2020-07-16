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
  include_once '../../objects/Red_marker.php';
  // Instantiate DB & connect //cine face request?? un user minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $red_marker = new Red_marker($db);
  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to user
  $red_marker->latitude = $data['latitude'];
  $red_marker->longitude = $data['longitude'];
  $red_marker->uid_volunteer = isset($data['uid_volunteer']) ? $data['uid_volunteer'] : null;//if exists
  $red_marker->time = date('Y-m-d-H-i-s');
  if($red_marker->create()){
      http_response_code(200);
      echo json_encode(array('message' => 'red marker created'));

  } else {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Red marker NOT created'));
  }
?>
