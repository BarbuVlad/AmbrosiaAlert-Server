<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // anyone can read
  header('Content-Type: application/json'); // returns/accepts a json format file

  include_once '../../config/Database.php';
  include_once '../../objects/Grey_marker.php';
  // Instantiate DB & connect //cine face request?? un grey_marker minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $grey_marker = new Grey_marker($db);

  // grey_marker query
  $result = $grey_marker->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any grey_markers
  if($num > 0) {
    // grey_marker array
    $grey_marker_arr = array();
    $grey_marker_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);//so we can refer to row['data'] just as $data

      //for this item make a new associative array with all data red
      $grey_marker_item = array(
        //'uid' => $uid,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'uid_volunteer' => $uid_volunteer,
        'time_of_delete' => $time_of_delete
      );

      // Push to "data"
      array_push($grey_marker_arr['data'], $grey_marker_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($grey_marker_arr);

  } else {
    // No grey Markers
    echo json_encode(
      array('message' => 'No grey Markers Found')
    );
  }
