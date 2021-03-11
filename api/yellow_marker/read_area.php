<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: GET");

  include_once '../../config/Database.php';
  include_once '../../objects/Yellow_marker.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $yellow_marker = new Yellow_marker($db);

  // The following coordinates represent the starting location for which the markers in the area are read
  $yellow_marker->latitude = isset($_GET['latitude']) ? floatval($_GET['latitude']) : exit(1); //if there is a query string in the URL then use it, else exit(1)=stop
  $yellow_marker->longitude = isset($_GET['longitude']) ? floatval($_GET['longitude']) : exit(1);
  //pass data again
  //$yellow_marker->latitude = floatval($_GET['latitude']);
  //$yellow_marker->longitude = floatval($_GET['longitude']);

  //read this one yellow_marker
  $result = $yellow_marker->read_area();

  // Get row count
  $num = $result->rowCount();

  // Check if any yellow_markers
  if($num > 0) {
    // yellow_marker array
    $yellow_marker_arr = array();
    $yellow_marker_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);//so we can refer to row['data'] just as $data

      //for this item make a new associative array with all data yellow
      $yellow_marker_item = array(
        //'uid' => $uid,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'email_volunteer' => $email_volunteer,
        'time' => $time
      );

      // Push to "data"
      array_push($yellow_marker_arr['data'], $yellow_marker_item);
    }
    // Turn to JSON & output
    http_response_code(200);
    echo json_encode($yellow_marker_arr);

  } else {
    // No Yellow Markers
    http_response_code(404);
    echo json_encode(
      array('message' => 'No yellow Markers Found')
    );
  }
?>
