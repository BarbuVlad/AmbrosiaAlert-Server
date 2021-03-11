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
  include_once '../../objects/Blue_marker.php';
  include_once '../../objects/User.php';
  // Instantiate DB & connect //cine face request?? un user minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $blue_marker = new Blue_marker($db);
  $user = new User($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to user

  $user->vendor_id = $data['vendor_id'];
  //read this one user
  $user->read_single();

  if(strcmp($user->blocked, "bs") == 0){
    //if user is blocked throw error code 403 Forbidden
    http_response_code(403);
    echo json_encode(array('message' => 'ERROR occurred. User no longer has rights'));
  } else {
  $blue_marker->latitude = $data['latitude'];
  $blue_marker->longitude = $data['longitude'];
  $blue_marker->vendor_id = $data['vendor_id'];//if exists
  $blue_marker->time = date('Y-m-d-H-i-s');
  if($blue_marker->create()){
      http_response_code(200);
      echo json_encode(array('message' => 'Blue marker created'));

  } else {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Blue marker NOT created'));
  }
}
?>
