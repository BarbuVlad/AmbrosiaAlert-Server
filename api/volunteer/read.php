<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // anyone can read
  header('Content-Type: application/json'); // returns/accepts a json format file

  include_once '../../config/Database.php';
  include_once '../../objects/Volunteer.php';
  // Instantiate DB & connect //cine face request?? un volunteer minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $volunteer = new Volunteer($db);

  // volunteer query
  $result = $volunteer->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any volunteers
  if($num > 0) {
    // volunteer array
    $volunteer_arr = array();
    $volunteer_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);//so we can refer to row['data'] just as $data

      //for this item make a new associative array with all data red
      $volunteer_item = array(
        'uid' => $uid,
        'phone' => $phone,
        'email' => $email,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'address' => $address
      );

      // Push to "data"
      array_push($volunteer_arr['data'], $volunteer_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($volunteer_arr);

  } else {
    // No Blue Markers
    echo json_encode(
      array('message' => 'No volunteers Found')
    );
  }
