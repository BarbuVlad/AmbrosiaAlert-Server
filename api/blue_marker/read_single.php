<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: GET");

  include_once '../../config/Database.php';
  include_once '../../objects/Blue_marker.php';
  // Instantiate DB & connect //cine face request?? un blue_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $blue_marker = new Blue_marker($db);

  // Get PK
  $blue_marker->latitude = isset($_GET['latitude']) ? floatval($_GET['latitude']) : die(); //if there is a query string in the URL then use it, else die()=stop
  $blue_marker->longitude = isset($_GET['longitude']) ? floatval($_GET['longitude']) : die();

  //$blue_marker->latitude = floatval($_GET['latitude']);
  //$blue_marker->longitude = floatval($_GET['longitude']);
  //read this one blue_marker
  $blue_marker->read_single();

  if($blue_marker->latitude!=null and $blue_marker->longitude!=null){ // if blue_marker exists
    //Create array and send it as JSON
    $blue_marker_arr = array(
      'latitude' => $blue_marker->latitude,
      'longitude' => $blue_marker->longitude,
      'vendor_id' => $blue_marker->vendor_id,
      'time' => $blue_marker->time
    );
    http_response_code(200);
    //send JSON
    echo json_encode($blue_marker_arr);
  }
  else {//if blue_marker dose NOT exist
    http_response_code(404);
    echo json_encode(array('message'=>"blue_marker dose NOT exist"));
  }
?>
