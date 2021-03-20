<html>
 <head>
 <style>


body {background-color: #1f1f1f;}
h1   {color: #e8e8e8; padding-left: 40px; letter-spacing: 3px;}

a:link {
    color: #87b6e8;
}
a:visited {
    color: #749bc4;
}
a:hover {
    color: #6689ad;
}

ul {padding-left: 95px; color: wheat}
li {font-size: 20px; padding-bottom: 8px;}

</style>


 </head>
 <body>
   <h1>User reviews</h1>
<ul>
<?php
if ($handle = opendir('.')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {

            echo "<li>" . "<a href= \"http://92.87.91.16/backend_code/reviews/$entry\">". "$entry" . "</li>";
        }
    }

    closedir($handle);
}
?>
</ul>
 </body>
</html>
