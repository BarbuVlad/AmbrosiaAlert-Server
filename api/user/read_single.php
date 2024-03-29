<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: GET");

  include_once '../../config/Database.php';
  include_once '../../objects/User.php';
  // Instantiate DB & connect //cine face request?? un user minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $user = new User($db);

  // Get vendor_id
  $user->vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : die(); //if there is a query string in the URL then use it, else die()=stop

  //read this one user
  $user->read_single();

  if($user->vendor_id!=null){ // if user exists
    //Create array and send it as JSON
    $user_arr = array(
      'vendor_id' => $user->vendor_id,
      'blocked' => $user->blocked
    );
    http_response_code(200);
    //send JSON
    echo json_encode($user_arr);
  }
  else {//if user dose NOT exist
    echo json_encode(array('message'=>"User dose NOT exist"));
  }
?>
