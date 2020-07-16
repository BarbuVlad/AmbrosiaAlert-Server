<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: GET");

  include_once '../../config/Database.php';
  include_once '../../objects/Grey_marker.php';
  // Instantiate DB & connect //cine face request?? un grey_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $grey_marker = new Grey_marker($db);

  // Get PK
  $grey_marker->latitude = isset($_GET['latitude']) ? floatval($_GET['latitude']) : die(); //if there is a query string in the URL then use it, else die()=stop
  $grey_marker->longitude = isset($_GET['longitude']) ? floatval($_GET['longitude']) : die();
  //read this one grey_marker
  $grey_marker->read_single();
//pass data again
  $grey_marker->latitude = floatval($_GET['latitude']);
  $grey_marker->longitude = floatval($_GET['longitude']);

  if($grey_marker->latitude!=null and $grey_marker->longitude!=null){ // if grey_marker exists
    //Create array and send it as JSON
    $grey_marker_arr = array(
      'latitude' => $grey_marker->latitude,
      'longitude' => $grey_marker->longitude,
      'uid_volunteer' => $grey_marker->uid_volunteer,
      'time_of_delete' => $grey_marker->time_of_delete
    );
    http_response_code(200);
    //send JSON
    echo json_encode($grey_marker_arr);
  }
  else {//if grey_marker dose NOT exist
    echo json_encode(array('message'=>"grey_marker dose NOT exist"));
  }
?>
