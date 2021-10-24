<?php
  // Send headers with HTTP
  header('Access-Control-Allow-Origin: *'); // can be read by anyone
  header('Content-Type: application/json'); // returns a json format file
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  // json header can be passed for user data
  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(400);
    echo json_encode(array('ERROR' => 'Bad http verb'));
    return;
}

if((int)$_SERVER['CONTENT_LENGTH']>10000){
    http_response_code(413);//Payload Too Large
    echo json_encode(array('ERROR' => 'Payload Too Large'));
    return;
}

  //get data from request
  $data = json_decode(file_get_contents("php://input"), true);
  if (!isset($data['bug_description'])){
    http_response_code(428);//Precondition Required
    echo json_encode(array('ERROR' => 'JSON data has no body of description.'));
    return;
  }

  function http_request_to_youtrack($url, $data){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
        "Accept: application/json",
        "Cache-Control: no-cache",
        "Authorization: Bearer perm:Z3Vlc3Qy.NTItMQ==.y7YGUXpWS1jNqeTX9CODohudeUn90X",
        "Content-Type: application/json"
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //put data in request
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $resp = curl_exec($curl);
    curl_close($curl);
    
    $return_object = json_decode($resp);
    return $return_object;
}
  //----------Request can be processed from here---------------------
    //send information response
    http_response_code(102); // Processing...
    echo json_encode(array('info' => 'processing...'));


  //arange data 
  if(!isset($data['username'])){
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $data['username'] = 'Anonim-';
    for($i=0;$i<10;$i++){
        $data['username'] .= $chars[rand(0, strlen($chars) - 1)];
    }
  }
  
  $data['bug_title'] = isset($data['bug_title']) ? $data['bug_title'] : "no_title"; 

  $data['username']=strip_tags($data['username']);
  $data['bug_title']=strip_tags($data['bug_title']);
  $data['bug_description']=strip_tags($data['bug_description']);

  $text = "<p><b>USER REVIEW</b><br>" .
  "<b>User name: </b>" . $data['username'] . 
  "<br><b>Title: </b>" . $data['bug_title'] . 
  "<br><b>Body of review:</b><br>" . "  " . $data['bug_description'] .
  "<br>==============================================================================================================================</p><br>";

  $empty_html_template = "<html>
  <head>
  <style>
  body {background-color: #1f1f1f;}
  p {font-size: 18px;color: #e6e6e6; padding-left: 40px}
  </style></head>
  <body>
  ";

  $filename = date("Y-m-d");
  $path_to_file = "/var/www/html/backend_code/reviews/".$filename.".html";
  //write data to disk

  try{
    //does file exist? if not create with template
    if(!file_exists($path_to_file)){
      $nf = fopen($path_to_file, "w");
      fwrite($nf, $empty_html_template);
      fclose($nf);
      echo "File dose not exists! but created!";
    }
    //file_put_contents($filename ,$text , FILE_APPEND | LOCK_EX);
    $f = fopen($path_to_file, "r+");
    fseek($f, -0, SEEK_END);
    fwrite($f, $text);
    fclose($f);
    
    //===Report to YouTrack===
    //convert to object 
    $data_object = json_decode(json_encode($data));
    $bug_title = "";
    $data_object->bug_title ? 
      $bug_title = preg_replace("/\r|\n/", "\\n",$data_object->bug_title)
      :
      $bug_title = "steps not specified";

    $bug_description = "";
    $data_object->bug_description ? 
      $bug_description = preg_replace("/\r|\n/", "\\n",$data_object->bug_description)
      :
      $bug_description = "steps not specified";

    $steps_to_reproduce = "";
    $data_object->steps_to_reproduce ? 
      $steps_to_reproduce = preg_replace("/\r|\n/", "\\n",$data_object->steps_to_reproduce)
      :
      $steps_to_reproduce = "steps not specified";

      $actual_result = "";
      $data_object->actual_result ? 
        $actual_result = preg_replace("/\r|\n/", "\\n",$data_object->actual_result)
        :
        $actual_result = "not specified";

      $expected_result = "";
      $data_object->expected_result ? 
        $expected_result = preg_replace("/\r|\n/", "\\n",$data_object->expected_result)
        :
        $expected_result = "not specified";

      $phone = "";
      $data_object->phone_model ? 
        $phone = preg_replace("/\r|\n/", "\\n",$data_object->phone)
        :
        $phone = "not specified";
    //create the task 
    $url = "https://ambrosia.myjetbrains.com/youtrack/api/issues";
    $data_1 = <<<DATA
    {
        "project":{"id":"0-1"},
        "summary":"$bug_title",
        "description":"Description: $bug_description \\n Steps to reporduce: $steps_to_reproduce \\n Result: $actual_result \\n Expected result: $expected_result \\n Phone details: $phone",
        "customFields": [
            {"name":"Type","\$type":"SingleUserIssueCustomField","value":{"name":"Bug (client)"}}
        ]
    }
DATA;
    $issue_created = http_request_to_youtrack($url, $data_1);
    print_r($issue_created);
    print_r($data_object->bug_title);
    //apply command to change visibility to all
    $url = "https://ambrosia.myjetbrains.com/youtrack/api/commands";
    $data_2 = <<<DATA
    {
        "query":"visible to All Users",
      "issues": [ { "id": "$issue_created->id" } ],
      "silent":true,
      "comment":"Created by automation bot (reported by user: $data_object->username)",
      "visibility":
        {
        "\$type":"CommandLimitedVisibility",
        "permittedGroups":[{"id":"3-1"}]
        }
    }
DATA;
    $command_change_visibility = http_request_to_youtrack($url, $data_2);
    
    //apply command to add in Bugs (client) board
    $url = "https://ambrosia.myjetbrains.com/youtrack/api/commands";
    $data_3 = <<<DATA
    {
        "query":"add Board Bugs (client-reported) ",
      "issues": [ { "id": "$issue_created->id" } ],
      "silent":true,
      "comment":"Moved to client-bugs board",
      "visibility":
        {
        "\$type":"CommandLimitedVisibility",
        "permittedGroups":[{"id":"3-1"}]
        }
    }
DATA;
    $command_add_to_board = http_request_to_youtrack($url, $data_3);
    
    //========================
    http_response_code(200);
    echo json_encode(array('message' => 'Review sent successfully!'));
    
  }catch (Exception $e){
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Request failed at storing data.'));
  }

?>