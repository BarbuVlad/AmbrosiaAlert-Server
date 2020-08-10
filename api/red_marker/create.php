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

  include_once '../../objects/Volunteer.php';
  include_once '../../config/_distance.php';
  // Instantiate DB & connect

  $database = new Database();
  $db = $database->connect();

  // Instantiate a table object
  $volunteer = new Volunteer($db);
  $red_marker = new Red_marker($db);

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);//data from body of the request

  //pass data to volunteer

  $voulteer->uid = $data['uid_volunteer'];

  //read this one volunteer
  $volunteer->read_single();
  //read does not happen , result is null
//echo json_encode(array('message' => $volunteer->phone));
  if(strcmp($volunteer->blocked, "bs") == 0){
    //if user is blocked throw error code 403 Forbidden
    http_response_code(403);
    echo json_encode(array('message' => 'ERROR occurred. Volunteer no longer has rights'));
    exit(0);
  }

  //-- Create or increment marker --
  //better: ->read_area
  //optimisation in DB: partitions and indexes
  $markers = $red_marker->_read_intersecting_markers($data['latitude'])->fetchAll();
  //for every marker check the distance
  foreach($markers as $marker){
    //if the markers intersect, then do not create red marker
    //ideal: increment for the closest red_marker
    if(distance($data['latitude'], $data['longitude'], $marker['latitude'], $marker['longitude']) < $marker['radius']+40){//intersect more than 10 meters
      try{
        //increment the confirmations of that marker
          $red_marker->_increment_confrimations($marker['confirmations']+1,$marker['latitude']);
          http_response_code(200);
          echo json_encode(array('message' => 'red marker NOT created. In area of another marker'));
          exit(0);//incrementation succesful, skip
      }catch(Exception $e){
          echo "ERROR create: " . $e;
          http_response_code(400);
        }
    }
  }


  //pass data to red marker

  $red_marker->latitude = $data['latitude'];
  $red_marker->longitude = $data['longitude'];
  $red_marker->uid_volunteer = $data['uid_volunteer'];//if exists
  $red_marker->time = date('Y-m-d-H-i-s');
  if($red_marker->create()){
      http_response_code(200);
      echo json_encode(array('message' => 'red marker created'));

  } else {
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Red marker NOT created'));
  }

?>
