<?php
echo "blue_to_red SCRIPT RUN";
//This script holds a complexity in Big(O) of n*log(n)
//Fetch data from DB
include_once '../config/Database.php';
include_once '../objects/Blue_marker.php';
include_once '../objects/Red_marker.php';

function distance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return $angle * $earthRadius;
}
//vincentyGreatCircleDistance

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a blue marker object
$blue = new Blue_marker($db);

// blue_marker query - get all data
$result_stmt = $blue->read();
// Get row count
$num = $result_stmt->rowCount();
//for better memory performance fetch is better than fetchAll
//for better CPU performance fetchAll is better
//in this script fetchAll is used
$list = $result_stmt->fetchAll(PDO::FETCH_ASSOC);
/* list is an associative array, it looks like:
$list = array("0"=>array("latitude"=>"10.123400", "longitude"=>"12.002340", "uid_user"=> , "time"=>...),
         "1"=>array("latitude"=>13.123400, "longitude"=>16.002340, "uid_user"=> , "time"=>...)
          "2"=>....
        ........);
*/
if($num <= 0) {
  die();
}
$index = 0; // index of current blue_marker in $list
foreach($list as $blue_master){
  $counter_found = 0; // number of blue markers close to  $blue_master
  $list_found = array(); //holds data of blue markers close to $blue_master
  //echo print_r($list_found);
  $index = array_search($blue_master,$list);//next index

  $up_index=$index-1; // search index for up markers
  $down_index=$index+1;// search index for down markers

  $up = True; // false = don't search up in the list anymore
  $down = True;// false = don't search down in the list anymore

  $search = True;

  //look for close blue markers
  while($search){

    //Update up and down truth value
    if ($up_index==True){
      if($up_index<0){//out of index
        $up=False;
      }else if($blue_master["latitude"]-$list[$up_index]["latitude"]>0.0001){//distance greater than 11.12m
        $up=False;
      }
  }

    if ($down_index==True){
      if($down_index>=$num){//out of index
        $down=False;
      }else if($list[$down_index]["latitude"]-$blue_master["latitude"]>0.0001){//distance greater than 11.12m
        $down=False;
      }
  }

  //Test for up marker
  if($up==True){
    if(distance($blue_master["latitude"], $blue_master["longitude"],
     $list[$up_index]["latitude"], $list[$up_index]["longitude"]) < 10.0){
       //Marker found!
       array_push($list_found, array("index"=>$up_index,
                              "latitude"=>$list[$up_index]["latitude"],
                              "longitude"=>$list[$up_index]["longitude"]));
      $counter_found++;
     }
  }

  //Test for down marker
  if($down==True){
    if(distance($blue_master["latitude"], $blue_master["longitude"],
     $list[$down_index]["latitude"], $list[$down_index]["longitude"]) < 10.0){
       //Marker found!
       array_push($list_found, array("index"=>$down_index,
                              "latitude"=>$list[$down_index]["latitude"],
                              "longitude"=>$list[$down_index]["longitude"]));
      $counter_found++;
     }
  }


  //update index
  //increment or decrement up/down index
  // php has only associative arrays, indexes cannnot be trusted
  $up_index--;
  while(!(array_key_exists($up_index, $list)) and $up_index>=0){//search for new corect  index
    $up_index--;
  }

  $down_index++;
  while(!(array_key_exists($down_index, $list)) and $down_index<$num){//search for new corect  index
    $down_index++;
  }

//when to stop searching:
if ($up==False and $down==False){//cannot look up or down
  $search=False;
}else if($counter_found==5){//enough markers for red
  $search=False;
}

}//while
//Can a red markers be created from this iteration?
if($counter_found==5){ //best way: transation -> create red marker; if (blue.delete)->FALSE -> rollback!
  //Create red_marker
  // Instantiate a red marker object
  $red = new Red_marker($db);
  $red->latitude = $blue_master["latitude"];
  $red->longitude = $blue_master["longitude"];
  $red->time = date('Y-m-d-H-i-s');

  if($red->create()){echo "Red marker created from blue markers \n ";}
  else {echo "Red marker NOT created from blue markers. ERROR \n ";}

  foreach($list_found as $b){//for every blue marker found
    //delete blue Markers
    $blue->latitude = $b["latitude"];
    $blue->longitude = $b["longitude"];
    if($blue->delete()){echo "Blue marker deleted \n ";}
    else{echo "Blue marker NOT deleted \n ";}

      //update $list
    unset($list[$b["index"]]);
  }
  //delete $blue_master
  $blue->latitude = $blue_master["latitude"];
  $blue->longitude = $blue_master["longitude"];
  if($blue->delete()){echo "Blue master marker deleted \n ";}
  else{echo "Blue master marker NOT deleted \n ";}
}
}//foreach

?>
