<?php
//function to create a file and add information to it
function writeFile($name, $string) {
  //Create a path
  $path = "/../../../../log/log_ambrosia/";
  //Initialize a variable with current date and time
  $time = date('Y-m-d');
  //Initialize the name of the file
  $filename = $path.$name.$time.".txt";
  //Create or open the file
  $fp = fopen(__DIR__.$filename,"a+");
  //put information into file
  fputs($fp,$string);
  //close file
  fclose($fp);
}

?>
