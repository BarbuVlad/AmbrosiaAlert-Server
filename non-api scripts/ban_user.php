<?php
	include_once '../config/Database.php';
	include_once '../objects/User.php';
	include_once '../objects/Blue_marker.php';
	// Instantiate DB & connect 
	$database = new Database();
	$db = $database->connect();

	// Instantiate a table object of type user
	$user = new User($db);

	// user query
	$result = $user->read();

	// Instantiate a table object of type blue marker
	$blue_marker = new Blue_marker($db);

	// blue_marker query
	$result2 = $blue_marker->read();
	$blue_arr = array();
  while($rows2 = $result2->fetch(PDO::FETCH_ASSOC)){
		array_push($blue_arr,$rows2);
  }
	//initiate a collection
	$time_span = array();

	//while to read each user
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
		//if a user is blocked , there is no need to check again  TO IMPLEMENT WHEN PROJECT EXTENDED
		echo $row['uid'];
		//give a uid to the instance of table object
		$user->uid=$row['uid'];
		//initiate total time of 5min(3000sec) in which a user may put 3 blue markers
		$total_mark = 1;
		$total_time = 3000;
		$blue_marker_item = array();
		reset($blue_marker_item);
		$time_span = array();
		reset($time_span);


	  //while to read all blue markers for each user
	  foreach($blue_arr as $row2){
			echo "user_id: ".$row2['uid_user'];
		  if($row['uid'] === $row2['uid_user']){
			    //for each user create an array with times for all his blue markers
					//mult de modificat, posibil sa mearga
				//$blue_marker_item[] = array('time' => $row2['time']);
				//suprascrie si ramane doar ultima
				//$blue_marker_item = array($row2['time']);
				array_push($blue_marker_item,$row2['time']);
				//print_r ($blue_marker_item);
		    }
	    }
			print_r ($blue_marker_item);
		foreach ($blue_marker_item as $date){
			//split date into separate components
			list($year, $month, $day, $hour, $minute, $second) = explode("-", $date);
			//add an array of elements to each position of collection
			$time_span[] = array('year' => $year, 'month' => $month, 'day' => $day, 'hour' => $hour, 'minute' => $minute, 'second' => $second);
			//print_r ($time_span);
		}


	//taking elements from collection and comparing them 2 by 2
	$j=count($time_span);
	for($i=0; $i<$j-1; ++$i){
		if($time_span[$i]['year'] == $time_span[$i+1]['year']){
			if($time_span[$i]['month'] == $time_span[$i+1]['month']){
				if($time_span[$i]['day'] == $time_span[$i+1]['day']){
					if($time_span[$i]['hour'] == $time_span[$i+1]['hour']){
						if($time_span[$i]['minute'] == $time_span[$i+1]['minute']){
							//if 2 blue markers are added in the same minute , compute the difference between seconds and subtract it from total time , also add a second marker to total_mark
							$sec = intval($time_span[$i]['second']) - intval($time_span[$i+1]['second']);
							$total_time = $total_time - $sec;
							$total_mark++;

						}if($time_span[$i]['minute'] > $time_span[$i+1]['minute']){
							// if first minute is bigger , do the same as before , but also for minutes
							$min = intval($time_span[$i]['minute']) - intval($time_span[$i+1]['minute']);
							$total_time = $total_time - $min*60;
							$sec = intval($time_span[$i]['second']) - intval($time_span[$i+1]['second']);

							if($sec >= 0){
								$total_time = $total_time - $sec;
							}else{
								$total_time = $total_time - $sec;
							}
							//does it make sense to compare ? if negatine, in same formula will be + as intended
							$total_mark++;

						}else{
							// if first minute is smaller , do the same as before , but also for minutes
							$min = intval($time_span[$i]['minute']) - intval($time_span[$i+1]['minute']);
							//the formula is with + because the time will be negative
							$total_time = $total_time + $min*60;
							$sec = intval($time_span[$i]['second']) - intval($time_span[$i+1]['second']);

							if($sec >= 0){
								$total_time = $total_time - $sec;
							}else{
								$total_time = $total_time - $sec;
							}
							//does it make sense to compare ? if negatine, in same formula will be + as intended
							$total_mark++;

						}
						//check to see if condition to ban user is true
							if($total_mark == 3 && $total_time >=0){
								//block user , metoda noua la user la object
								$user->blocked();
								$total_time = 3000;
								$total_mark = 1;
								//maybe we should stop after a user is blocked and go to next one  TO IMPLEMENT WHEN PROJECT EXTENDED
							}
							//if run out of time, then reinitialize variables
							if($total_time < 0){
								$total_time = 3000;
								$total_mark = 1;
							}
					}
				}
			}
		}

	}
	}
?>
