<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // anyone can read
  header('Content-Type: application/json'); // returns/accepts a json format file

  include_once '../../config/Database.php';
  include_once '../../objects/Admin.php';
  // Instantiate DB & connect //cine face request?? un admin minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $admin = new Admin($db);

  // admin query
  $result = $admin->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any admins
  if($num > 0) {
    // admin array
    $admin_arr = array();
    $admin_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);//so we can refer to row['data'] just as $data

      //for this item make a new associative array with all data red
      $admin_item = array(
        'name' => $name
      );

      // Push to "data"
      array_push($admin_arr['data'], $admin_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($admin_arr);

  } else {
    // No Posts
    echo json_encode(
      array('message' => 'No Admin Found')
    );
  }
