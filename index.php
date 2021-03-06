<?php
  session_start();
  if (!$_POST['Login']) { // the user did not click the Login button, ie. it's their first visit to the website
      include("LoginPage.php");
  }
  
  if ($_POST['Login'])   // the user clicked the Login button
  {
      // connect to database - Ignore this bit, it's just setting up the database.
      $connection = mysqli_connect("localhost", "root", "root") or die ("Couldn't connect to server.");
      $db = mysqli_select_db($connection, "CustomerDirectory") or die ("Couldn't select database.");
      $cxn = $connection;

      // Is there a user called username ?
      $sql = "SELECT user_name FROM Customer WHERE user_name='$_POST[username]'";
      $result = mysqli_query($cxn,$sql) or die("Couldn't execute query 1");
      $num = mysqli_num_rows($result);
      if($num == 1)
      {
         // Does the password match the password stored in the database ?
         $sql = "SELECT user_name FROM Customer WHERE user_name='$_POST[username]' AND password='$_POST[password]'";
         $result2 = mysqli_query($cxn,$sql) or die("Couldn't execute query 2");  
         $row = mysqli_fetch_assoc($result2);
         if($row)  // username exists and password correct
         {
           $_SESSION['WhoLoggedOn'] = $_POST[username];
           $_SESSION['auth'] = "yes";
           header("Location: Auctions.php");
         }
         else
         {
           $message = "You have entered an incorrect password. Please try again";
           include("LoginPage.php");
         }
      }
      elseif ($num == 0)  // username not found in database
      {
          $message = "The username you entered does not exist. Please try again";
          include("LoginPage.php");
      }
  }   
?>
