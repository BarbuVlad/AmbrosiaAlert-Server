<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // anyone can read
  header('Content-Type: application/json'); // returns/accepts a json format file

  include_once '../../config/Database.php';
  include_once '../../objects/User.php';
  // Instantiate DB & connect //cine face request?? un user minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $user = new User($db);

  // user query
  $result = $user->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any users
  if($num > 0) {
    // user array
    $user_arr = array();
    $user_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);//so we can refer to row['data'] just as $data

      //for this item make a new associative array with all data red
      $user_item = array(
        'uid' => $uid,
        'mac_user' => $MAC_user,
        'blocked' => $blocked
      );

      // Push to "data"
      array_push($user_arr['data'], $user_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($user_arr);

  } else {
    // No Posts
    echo json_encode(
      array('message' => 'No User Found')
    );
  }
