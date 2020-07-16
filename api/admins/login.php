<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: GET");

  include_once '../../config/Database.php';
  include_once '../../objects/Admin.php';
  // Instantiate DB & connect //cine face request?? un admin minimal, ...
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $admin = new Admin($db);

  // Get uid
  $admin->name = isset($_GET['name']) ? $_GET['name'] : die(); //if there is a query string in the URL then use it, else die()=stop
  $admin->password = isset($_GET['password']) ? $_GET['password'] : die();

  //read this one admin
  $admin->read_single();

  if($admin->name!=null && $admin->password!=null ){ // if admin exists
    //Create array and send it as JSON
    $admin_arr = array(
      'allow' => 'yes'
    );
    http_response_code(200);
    //send JSON
    echo json_encode($admin_arr);
  }
  else {//if admin dose NOT exist
    echo json_encode(array('allow'=>"no"));
  }
?>
