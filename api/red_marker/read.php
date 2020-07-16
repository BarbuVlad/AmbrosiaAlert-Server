<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // anyone can read
  header('Content-Type: application/json'); // returns/accepts a json format file

  include_once '../../config/Database.php';
  include_once '../../objects/Red_marker.php';
  // Instantiate DB & connect //cine face request?? un red_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $red_marker = new Red_marker($db);

  // red_marker query
  $result = $red_marker->read();
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
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($red_marker_arr);

  } else {
    // No Blue Markers
    echo json_encode(
      array('message' => 'No Red Markers Found')
    );
  }
