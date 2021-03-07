<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for volunteer data

  include_once '../../config/Database.php';
  include_once '../../objects/Volunteer.php';
  // Instantiate DB & connect //cine face request?? un volunteer minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $volunteer = new Volunteer($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to volunteer
  $volunteer->email = $data['email'];
  $volunteer->password = $data['password'];

  // volunteer query
	$result = $volunteer->read_login();
  if($result === 1){
    http_response_code(400);
    echo json_encode(array("message" => "invalid_email_format"));
    exit(1);
  }

  if(password_verify($volunteer->password, $result['password'])){
    http_response_code(200);
    echo json_encode(array("message" => "volunteer_login_successfull",
                            "email" => $result['email']));
    exit(0);
  }
  
    
    http_response_code(403);
    echo json_encode(array("message" => "no_such_volunteer_found"));

?>
