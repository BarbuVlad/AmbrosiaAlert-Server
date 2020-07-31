<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // anyone can read
  header('Content-Type: application/json'); // returns/accepts a json format file

  include_once '../../config/Database.php';
  include_once '../../objects/New_volunteer.php';
  // Instantiate DB & connect //cine face request?? un new_volunteer minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $new_volunteer = new New_volunteer($db);

  // new_volunteer query
  $result = $new_volunteer->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any new_volunteers
  if($num > 0) {
    // new_volunteer array
    $new_volunteer_arr = array();
    $new_volunteer_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);//so we can refer to row['data'] just as $data

      //for this item make a new associative array with all data red
      $new_volunteer_item = array(
        'uid' => $uid,
        'phone' => $phone,
        'email' => $email,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'address' => $address,
        'blocked' => $blocked,
        'confirmations' => $confirmations
      );

      // Push to "data"
      array_push($new_volunteer_arr['data'], $new_volunteer_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($new_volunteer_arr);

  } else {
    // No Blue Markers
    echo json_encode(
      array('message' => 'No new_volunteers Found')
    );
  }
