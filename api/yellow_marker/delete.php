<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: DELETE");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for yellow_marker data

  include_once '../../config/Database.php';
  include_once '../../objects/Yellow_marker.php';
  // Instantiate DB & connect //cine face request?? un yellow_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $yellow_marker = new Yellow_marker($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request (PK)
  //pass data
  $yellow_marker->latitude = $data['latitude'];
  $yellow_marker->longitude = $data['longitude'];

//ideal: use transations and rollback  $dbh->beginTransaction(); $dbh->rollBack()
  if ($yellow_marker->delete()){
    http_response_code(200);
    echo json_encode(array("message" => "Yellow marker deleted"));
  } else {
    http_response_code(503);
    echo json_encode(array("message" => "ERROR occurred. Yellow marker NOT deleted"));
  }
?>
