<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: DELETE");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for grey_marker data

  include_once '../../config/Database.php';
  include_once '../../objects/Grey_marker.php';
  // Instantiate DB & connect //cine face request?? un grey_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $grey_marker = new Grey_marker($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request (PK)
  //pass data
  $grey_marker->latitude = $data['latitude'];
  $grey_marker->longitude = $data['longitude'];

//ideal: use transations and rollback  $dbh->beginTransaction(); $dbh->rollBack()
  if ($grey_marker->delete()){
    http_response_code(200);
    echo json_encode(array("message" => "grey marker deleted"));
  } else {
    http_response_code(503);
    echo json_encode(array("message" => "ERROR occurred. grey marker NOT deleted"));
  }
?>
