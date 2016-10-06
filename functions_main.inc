<?php
 /*  Function:  Connect_to_db
  *  Desc:      Connects to a MySQL database. The name of
  *             a file containing the database variables
  *             is passed to the function.
*/
  function Connect_to_db($filename)
  {
      include($filename);
      $connection = mysqli_connect($host, $user,$passwd) 
               or die ("Couldn't connect to server.");
      $db = mysqli_select_db($connection,$database)
               or die ("Couldn't select database.");
      return $connection;     
  }
?>
