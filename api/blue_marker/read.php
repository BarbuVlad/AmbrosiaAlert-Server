<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // anyone can read
  header('Content-Type: application/json'); // returns/accepts a json format file

  include_once '../../config/Database.php';
  include_once '../../objects/Blue_marker.php';
  // Instantiate DB & connect //cine face request?? un blue_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $blue_marker = new Blue_marker($db);

  // blue_marker query
  $result = $blue_marker->read();
  // Get row count
  $num = $result->rowCount();
  // Check if any blue_markers
  if($num > 0) {
    // blue_marker array
    $blue_marker_arr = array();
    $blue_marker_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);//so we can refer to row['data'] just as $data

      //for this item make a new associative array with all data red
      $blue_marker_item = array(
        //'uid' => $uid,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'uid_user' => $uid_user,
        'time' => $time
      );

      // Push to "data"
      array_push($blue_marker_arr['data'], $blue_marker_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($blue_marker_arr);

  } else {
    // No Blue Markers
    echo json_encode(
      array('message' => 'No Blue Markers Found')
    );
  }
