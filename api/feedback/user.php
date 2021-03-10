<?php
/*
Feedback from user for red_marker 
*/
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for user data

  include_once '../../config/Database.php';
  include_once '../../objects/Feedback_user.php';
  include_once '../../objects/User.php';
  include_once '../../objects/Red_marker.php';
  // Instantiate DB & connect //cine face request?? un user minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $feedback_user = new Feedback_user($db);
  $user = new User($db);
  $red_marker = new Red_marker($db);

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
    exit(1);
  }
$feedback_user->latitude = $data['latitude'];
$feedback_user->longitude = $data['longitude'];
$feedback_user->vendor_id = $data['vendor_id'];
$feedback_user->time = date('Y-m-d-H-i-s');
$feedback_user->type = $data['type'];//'like' or 'dislike'

$red_marker->latitude = $data['latitude'];
$red_marker->longitude = $data['longitude'];
try{
  $db->beginTransaction();

  //increment like or decrement dislikes
  if($data['type'] === 'like'){
    $red_marker->like(1);
  }
  else if($data['type'] === 'dislike'){
    $red_marker->dislike(1);
  }

  $result=$feedback_user->create();
  if($result===0){
      http_response_code(200);
      echo json_encode(array('message' => "Feedback of user created ($feedback_user->type)"));

  } else if($result===1){
    http_response_code(403);
    echo json_encode(array('message' => "Feedback of user NOT created (feedback exists)"));

  } else {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Feedback of user NOT created'));
  }

  $db->commit();
} catch (Exception $e){
  $db->rollBack();
  http_response_code(500);
  echo json_encode(array('message' => 'ERROR occurred. Internal database error (failed transaction; rollback done)'));
}
?>
