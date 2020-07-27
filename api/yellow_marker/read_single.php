<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: GET");

  include_once '../../config/Database.php';
  include_once '../../objects/Yellow_marker.php';
  // Instantiate DB & connect //cine face request?? un yellow_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $yellow_marker = new Yellow_marker($db);

  // Get PK
  $yellow_marker->latitude = isset($_GET['latitude']) ? floatval($_GET['latitude']) : die(); //if there is a query string in the URL then use it, else die()=stop
  $yellow_marker->longitude = isset($_GET['longitude']) ? floatval($_GET['longitude']) : die();
  //pass data again
  //$yellow_marker->latitude = floatval($_GET['latitude']);
  //$yellow_marker->longitude = floatval($_GET['longitude']);

  //read this one yellow_marker
  $yellow_marker->read_single();


  if($yellow_marker->latitude!=null and $yellow_marker->longitude!=null){ // if yellow_marker exists
    //Create array and send it as JSON
    $yellow_marker_arr = array(
      'latitude' => $yellow_marker->latitude,
      'longitude' => $yellow_marker->longitude,
      'uid_volunteer' => $yellow_marker->uid_volunteer,
      'time' => $yellow_marker->time
    );
    http_response_code(200);
    //send JSON
    echo json_encode($yellow_marker_arr);
  }
  else {//if yellow_marker dose NOT exist
    echo json_encode(array('message'=>"yellow_marker dose NOT exist"));
  }
?>
