<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: PUT");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for user data
  // Update was rendered obsolete after arch. refactor. Must be adapted
  http_response_code(405);
  echo json_encode(array("message" => "Update not supported!"));
  exit(0);

  include_once '../../config/Database.php';
  include_once '../../objects/User.php';
  // Instantiate DB & connect //cine face request?? un user minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $user = new User($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  $user->mac_user = $data['mac_user'];
  $user->uid = $data['uid'];

  if(true){//$user->update()
    http_response_code(200);
    echo json_encode(array("message" => "User updated"));
  }
  else {
    http_response_code(503);
    echo json_encode(array("message" => "ERROR occurred. User NOT updated"));
  }
?>
