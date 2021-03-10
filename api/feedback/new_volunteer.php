<?php
/*
Feedback from new_volunteer for red_marker 
*/
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for new_volunteer data

  include_once '../../config/Database.php';
  include_once '../../objects/Feedback_volunteer.php';
  include_once '../../objects/New_volunteer.php';
  include_once '../../objects/Red_marker.php';
  // Instantiate DB & connect //cine face request?? un new_volunteer minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $feedback_new_volunteer = new Feedback_volunteer($db);
  $new_volunteer = new New_volunteer($db);
  $red_marker = new Red_marker($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //pass data to new_volunteer

  $new_volunteer->email = $data['email'];
  //read this one new_volunteer
  $new_volunteer->read_single();

  if(strcmp($new_volunteer->blocked, "bs") == 0){
    //if new_volunteer is blocked throw error code 403 Forbidden
    http_response_code(403);
    echo json_encode(array('message' => 'ERROR occurred. New_volunteer no longer has rights'));
    exit();
  }
  $feedback_new_volunteer->latitude = $data['latitude'];
  $feedback_new_volunteer->longitude = $data['longitude'];
  $feedback_new_volunteer->email = $data['email'];
  $feedback_new_volunteer->time = date('Y-m-d-H-i-s');
  $feedback_new_volunteer->type = $data['type'];//'like' or 'dislike'

  $red_marker->latitude = $data['latitude'];
  $red_marker->longitude = $data['longitude'];
  try{
    $db->beginTransaction();
  
    //increment like or decrement dislikes
    if($data['type'] === 'like'){
      $red_marker->like(5);
    }
    else if($data['type'] === 'dislike'){
      $red_marker->dislike(5);
    }
  
    $result=$feedback_new_volunteer->create();
    if($result===0){
        http_response_code(200);
        echo json_encode(array('message' => "Feedback of new_volunteer created ($feedback_new_volunteer->type)"));
  
    } else if($result===1){
      http_response_code(403);
      echo json_encode(array('message' => "Feedback of new_volunteer NOT created (feedback exists)"));
  
    } else {
      http_response_code(503);
      echo json_encode(array('message' => 'ERROR occurred. Feedback of new_volunteer NOT created'));
    }
  
    $db->commit();
  } catch (Exception $e){
    $db->rollBack();
    http_response_code(500);
    echo json_encode(array('message' => 'ERROR occurred. Internal database error (failed transaction; rollback done)'));
  }
?>
