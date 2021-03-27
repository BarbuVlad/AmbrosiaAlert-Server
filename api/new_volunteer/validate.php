<?php
/*
    Operations done:
        ->  get all yellow markers
        ->  delete / confirm new_volunteer
        ->  create volunteer
        ->  yellow_marker -> red_marker (with volunteer email)

*/


header('Access-Control-Allow-Origin: *'); // can be read by anyone
header('Content-Type: application/json'); // returns a json format file
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
// json header can be passed for user data

include_once '../../config/Database.php';
include_once '../../objects/Yellow_marker.php';
include_once '../../objects/Red_marker.php';
include_once '../../objects/New_volunteer.php';
include_once '../../objects/Volunteer.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a table object
$yellow_marker = new Yellow_marker($db);
$new_volunteer = new New_volunteer($db);
$red_marker = new Red_marker($db);
$volunteer = new Volunteer($db);

//get data from request
$data = json_decode(file_get_contents("php://input"), true);

$new_volunteer->email = $data['email_volunteer'];
//can be either "confirm" or "delete"
$markers_action = $data['markers_action'];
//date('Y-m-d-H-i-s');

// volunteer query
$new_volunteer->_read_all_data();

if(strcmp($new_volunteer->blocked, "bs") == 0){
    //if user is blocked throw error code 403 Forbidden
    http_response_code(403);
    echo json_encode(array('message' => 'ERROR_occurred. Volunteer no longer has rights'));
    exit(0);
} else {
    //get all yellow_marker 
    $yellow_marker->email_volunteer=$data['email_volunteer'];
    $yellow_list = $yellow_marker->read_of_volunteer();

    //give volunteer all data from new_volunteer 
    $volunteer->email=$new_volunteer->email;
    $volunteer->phone=$new_volunteer->phone;
    $volunteer->first_name=$new_volunteer->first_name;
    $volunteer->last_name=$new_volunteer->last_name;
    $volunteer->address=$new_volunteer->address;
    $volunteer->password=$new_volunteer->password;

    try{
      $db->beginTransaction();

      $red_markers_list = [];
      $message = "";
      //Solve yellow_markers
      if ($yellow_list->rowCount()>0){
        if($markers_action=="delete"){
            while($row = $yellow_list->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $yellow_marker->latitude=$latitude;
                $yellow_marker->longitude=$longitude;
                $yellow_marker->delete();
            }
            $message="All yellow markers of new_volunteer have been deleted!";
         } else { //action either confirm or defaults to confirm 
            while($row = $yellow_list->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $yellow_marker->latitude=$latitude;
                $yellow_marker->longitude=$longitude;
                $yellow_marker->delete();

                $red_marker->latitude=$latitude;
                $red_marker->longitude=$longitude;
                $red_marker->email_volunteer=$email_volunteer;
                $red_marker->time=$time;
                //$red_marker->create(); //Can't create, no volunteer exists to satisfy FK constrain 
                array_push($red_markers_list, $red_marker);
            }
            $message="All yellow markers of new_volunteer have been confirmed(default)!";
        }
      } else {$message="New_volunteer had no yellow_markers";}

      //delete new_volunteer
      $a = $new_volunteer->delete();

      //create volunteer
      
      $b = $volunteer->create();

      //create red markers (if exists)
      foreach($red_markers_list as $red_markers_element){
        $red_markers_element->create();
      }
      
      if($b==false || $a==false) {
        $db->rollBack();
        http_response_code(503);
        echo json_encode(array('message' => 'ERROR occurred. Action cancelled (code:101)!'));
        exit(0);
      } else{
        $db->commit();
      }
      
  }catch(PDOException $e){
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Action cancelled !'));
    $db->rollBack();
    exit(0);
  }
}
http_response_code(200);
echo json_encode(array('message' => "New_volunteer validated successfully!" . $message));
?>
