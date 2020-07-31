<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for volunteer data

  include_once '../../config/Database.php';
  include_once '../../objects/Admin.php';
  // Instantiate DB & connect //cine face request?? un volunteer minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $admin = new Admin($db);

  // volunteer query
	$result = $admin->read_login();

  //declare a switch variable 0 initial , 1 if password found
  $login_ok = 0;

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to volunteer
  $admin->email = $data['email'];
  $admin->password = $data['password'];

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      if($admin->email == $row['email']){
        if(password_verify($admin->password, $row['password'])){
          //if the email and password are found in database than login successfull
          $login_ok = 1;
          echo json_encode(array("message" => $row['password']));
        }
      }
    }

    if($login_ok == 1){
      http_response_code(200);
      echo json_encode(array("message" => "login successfull"));
    }
    else {
      http_response_code(403);
      echo json_encode(array("message" => "no such volunteer found"));
    }
?>
