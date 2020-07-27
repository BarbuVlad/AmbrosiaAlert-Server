<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // anyone can read
  header('Content-Type: application/json'); // returns/accepts a json format file

  include_once '../../config/Database.php';
  include_once '../../objects/Yellow_marker.php';
  // Instantiate DB & connect //cine face request?? un yellow_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $yellow_marker = new Yellow_marker($db);

  // yellow_marker query
  $result = $yellow_marker->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any yellow_markers
  if($num > 0) {
    // yellow_marker array
    $yellow_marker_arr = array();
    $yellow_marker_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);//so we can refer to row['data'] just as $data

      //for this item make a new associative array with all data red
      $yellow_marker_item = array(
        //'uid' => $uid,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'uid_volunteer' => $uid_volunteer,
        'time' => $time
      );

      // Push to "data"
      array_push($yellow_marker_arr['data'], $yellow_marker_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($yellow_marker_arr);

  } else {
    // No Blue Markers
    echo json_encode(
      array('message' => 'No Yellow Markers Found')
    );
  }
