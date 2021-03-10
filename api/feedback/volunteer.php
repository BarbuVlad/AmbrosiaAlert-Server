<?php
/*
Feedback from volunteer for red_marker 
*/
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for volunteer data

  include_once '../../config/Database.php';
  include_once '../../objects/Feedback_volunteer.php';
  include_once '../../objects/Volunteer.php';
  include_once '../../objects/Red_marker.php';
  // Instantiate DB & connect //cine face request?? un volunteer minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $feedback_volunteer = new Feedback_volunteer($db);
  $volunteer = new Volunteer($db);
  $red_marker = new Red_marker($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to volunteer

  $volunteer->email = $data['email'];
  //read this one volunteer
  $volunteer->read_single();

  if(strcmp($volunteer->blocked, "bs") == 0){
    //if volunteer is blocked throw error code 403 Forbidden
    http_response_code(403);
    echo json_encode(array('message' => 'ERROR occurred. volunteer no longer has rights'));
    exit();
  }
  $feedback_volunteer->latitude = $data['latitude'];
  $feedback_volunteer->longitude = $data['longitude'];
  $feedback_volunteer->email = $data['email'];
  $feedback_volunteer->time = date('Y-m-d-H-i-s');
  $feedback_volunteer->type = $data['type'];//'like' or 'dislike'

  $red_marker->latitude = $data['latitude'];
  $red_marker->longitude = $data['longitude'];
  try{
    $db->beginTransaction();
  
    //increment like or decrement dislikes
    if($data['type'] === 'like'){
      $red_marker->like(10);
    }
    else if($data['type'] === 'dislike'){
      $red_marker->dislike(10);
    }
  
    $result=$feedback_volunteer->create();
    if($result===0){
        http_response_code(200);
        echo json_encode(array('message' => "Feedback of volunteer created ($feedback_volunteer->type)"));
  
    } else if($result===1){
      http_response_code(403);
      echo json_encode(array('message' => "Feedback of volunteer NOT created (feedback exists)"));
  
    } else {
      http_response_code(503);
      echo json_encode(array('message' => 'ERROR occurred. Feedback of volunteer NOT created'));
    }
  
    $db->commit();
  } catch (Exception $e){
    $db->rollBack();
    http_response_code(500);
    echo json_encode(array('message' => 'ERROR occurred. Internal database error (failed transaction; rollback done)'));
  }
?>
