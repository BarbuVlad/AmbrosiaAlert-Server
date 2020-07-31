<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
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
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to new_volunteer
  $new_volunteer->phone = $data['phone'];
  $new_volunteer->email = $data['email'];

  //hashing the pasword
  $new_volunteer->password = password_hash($data['password'], PASSWORD_BCRYPT);

  $new_volunteer->first_name = isset($data['first_name']) ? $data['first_name'] : 'null';
  $new_volunteer->last_name = isset($data['last_name']) ? $data['last_name'] : 'null';
  $new_volunteer->address = isset($data['address']) ? $data['address'] : 'null';
  //$new_volunteer->uid autogenerated PK

  if($new_volunteer->create()){
    http_response_code(200);
    echo json_encode(array('message' => 'new_volunteer created'));
  } else {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. new_volunteer NOT created'));
  }
?>
