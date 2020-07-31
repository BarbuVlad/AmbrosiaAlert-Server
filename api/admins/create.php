<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for admin data

  include_once '../../config/Database.php';
  include_once '../../objects/Admin.php';
  // Instantiate DB & connect //cine face request?? un admin, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $admin = new Admin($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to admin
  $admin->name = $data['name'];
  //hashing the pasword
  $admin->password = password_hash($data['password'], PASSWORD_BCRYPT);

  if($admin->create()){
    http_response_code(200);
    echo json_encode(array('message' => 'admin created'));
  } else {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. admin NOT created'));
  }
?>
