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
  if (!isset($data['body'])){
    http_response_code(428);//Precondition Required
    echo json_encode(array('ERROR' => 'JSON data has no body.'));
    return;
  }
  //----------Request can be processed from here---------------------
    //send information response
    http_response_code(102); // Processing...
    echo json_encode(array('info' => 'processing...'));


  //arange data 
  if(!isset($data['name'])){
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $data['name'] = 'Anonim-';
    for($i=0;$i<10;$i++){
        $data['name'] .= $chars[rand(0, strlen($chars) - 1)];
    }
  }
  
  $data['title'] = isset($data['title']) ? $data['title'] : "no_title"; 

  $data['name']=strip_tags($data['name']);
  $data['title']=strip_tags($data['title']);
  $data['body']=strip_tags($data['body']);

  $text = "<p><b>USER REVIEW</b><br>" .
  "<b>User name: </b>" . $data['name'] . 
  "<br><b>Title: </b>" . $data['title'] . 
  "<br><b>Body of review:</b><br>" . "  " . $data['body'] .
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
    
    http_response_code(200);
    echo json_encode(array('message' => 'Review sent successfully!'));
    
  }catch (Exception $e){
    http_response_code(503);
    echo json_encode(array('message' => 'ERROR occurred. Request failed at storing data.'));
  }
?>
