<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: GET");

  include_once '../../config/Database.php';
  include_once '../../objects/Red_marker.php';
  // Instantiate DB & connect //cine face request?? un red_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $red_marker = new Red_marker($db);

  // Get PK
  $red_marker->latitude = isset($_GET['latitude']) ? floatval($_GET['latitude']) : die(); //if there is a query string in the URL then use it, else die()=stop
  $red_marker->longitude = isset($_GET['longitude']) ? floatval($_GET['longitude']) : die();
  //pass data again
  //$red_marker->latitude = floatval($_GET['latitude']);
  //$red_marker->longitude = floatval($_GET['longitude']);

  //read this one red_marker
  $red_marker->read_single();


  if($red_marker->latitude!=null and $red_marker->longitude!=null){ // if red_marker exists
    //Create array and send it as JSON
    $red_marker_arr = array(
      'latitude' => $red_marker->latitude,
      'longitude' => $red_marker->longitude,
      'email_volunteer' => $red_marker->email_volunteer,
      'time' => $red_marker->time,
      'likes' => $red_marker->likes,
      'dislikes' => $red_marker->dislikes
    );
    http_response_code(200);
    //send JSON
    echo json_encode($red_marker_arr);
  }
  else {//if red_marker dose NOT exist
    http_response_code(404);
    echo json_encode(array('message'=>"red_marker dose NOT exist"));
  }
?>
