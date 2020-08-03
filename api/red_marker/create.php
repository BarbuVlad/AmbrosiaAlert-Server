<?php
/*
This function attemts to create a new red marker.
Condtion: this new marker must be within 100 meters from any other red marker.
If it is not, then the confirmation factor of that other red marker will increment by 1.
*/
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for user data

  include_once '../../config/Database.php';
  include_once '../../objects/Red_marker.php';
  include_once '../../config/_distance.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $red_marker = new Red_marker($db);
  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request
  //better: ->read_area
  //optimisation in DB: partitions and indexes
  $markers = $red_marker->read()->fetchAll();
  //for every marker check the distance
  foreach($markers as $marker){
    /*
    if(abs($data['latitude']-$marker['latitude']) > 0.001){// 0.001 represents a distance of 0.11 km ~ 110m
      continue;
    }
    */
    //if the markers intersect, then do not create red marker
    //ideal: increment for the closest red_marker
    if(distance($data['latitude'], $data['longitude'], $marker['latitude'], $marker['longitude']) < 200){//< marker['radius']+100
      try{
        //increment the confirmations of that marker
          $red_marker->_increment_confrimations($marker['confirmations']+1,$marker['latitude']);
          http_response_code(200);
          echo json_encode(array('message' => 'red marker NOT created. In area of another marker'));
          exit();//incrementation succesful, skip
      }catch(Exception $e){
          echo "ERROR create: " . $e;
          http_response_code(400);
        }
    }
  }


  //pass data to red marker
  $red_marker->latitude = $data['latitude'];
  $red_marker->longitude = $data['longitude'];
  $red_marker->uid_volunteer = isset($data['uid_volunteer']) ? $data['uid_volunteer'] : null;//if exists
  $red_marker->time = date('Y-m-d-H-i-s');
  if($red_marker->create()){
      http_response_code(200);
      echo json_encode(array('message' => 'red marker created'));

  } else {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Red marker NOT created'));
  }
?>
