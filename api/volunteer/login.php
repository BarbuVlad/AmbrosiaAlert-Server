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

  // volunteer query
	$result = $volunteer->read_login();

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to volunteer
  $volunteer->email = $data['email'];
  $volunteer->password = $data['password'];

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      if($volunteer->email == $row['email']){
        if(password_verify($volunteer->password, $row['password'])){
          http_response_code(200);
          echo json_encode(array("message" => "volunteer_login_successfull",
                                  "uid" => $row['uid']));
          exit(0);
        }
      }
    }
    http_response_code(403);
    echo json_encode(array("message" => "no such volunteer found"));

?>
