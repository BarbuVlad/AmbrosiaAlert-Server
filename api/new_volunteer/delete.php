<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: DELETE");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for new_volunteer data

  include_once '../../config/Database.php';
  include_once '../../objects/New_volunteer.php';
  // Instantiate DB & connect //cine face request?? un new_volunteer minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $new_volunteer = new New_volunteer($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request (uid)

  $new_volunteer->uid = $data['email'];

  if($new_volunteer->delete()){
    http_response_code(200);
    echo json_encode(array("message" => "new_volunteer deleted"));
  }
  else {
    http_response_code(503);
    echo json_encode(array("message" => "ERROR occurred. new_volunteer NOT deleted"));
  }
?>
