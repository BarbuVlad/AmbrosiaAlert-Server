<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: GET");

  include_once '../../config/Database.php';
  include_once '../../objects/Red_marker.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $red_marker = new Red_marker($db);

  // The following coordinates represent the starting location for which the markers in the area are read
  $red_marker->latitude = isset($_GET['latitude']) ? floatval($_GET['latitude']) : exit(1); //if there is a query string in the URL then use it, else exit(1)=stop
  $red_marker->longitude = isset($_GET['longitude']) ? floatval($_GET['longitude']) : exit(1);
  //pass data again
  //$red_marker->latitude = floatval($_GET['latitude']);
  //$red_marker->longitude = floatval($_GET['longitude']);

  //read this one red_marker
  $result = $red_marker->read_area();

  // Get row count
  $num = $result->rowCount();

  // Check if any red_markers
  if($num > 0) {
    // red_marker array
    $red_marker_arr = array();
    $red_marker_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);//so we can refer to row['data'] just as $data

      //for this item make a new associative array with all data red
      $red_marker_item = array(
        //'uid' => $uid,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'uid_volunteer' => $uid_volunteer,
        'time' => $time
      );

      // Push to "data"
      array_push($red_marker_arr['data'], $red_marker_item);
    }
    // Turn to JSON & output
    echo json_encode($red_marker_arr);
    
  } else {
    // No Blue Markers
    echo json_encode(
      array('message' => 'No Red Markers Found')
    );
  }
?>
