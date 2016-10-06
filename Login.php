<?php
/* Program: Login.php
 * Desc:    Main application script for the User Login
 *          application. It provides two options: (1) login
 *          using an existing User Name and (2) register
 *          a new user name. User Names and passwords are
 *          stored in a MySQL database. 
 */
  session_start();                                        #9
  include("functions_main.inc");                         #10
  $table_name = "Customer";                              #11
  $next_program = "SecretPage.php";

  switch (@$_POST['Button'])                             #14
  {
    case "Login":                                        #16
      $cxn = Connect_to_db("Vars.inc");
      $sql = "SELECT user_name FROM $table_name 
              WHERE user_name='$_POST[fusername]'";      #19
      $result = mysqli_query($cxn,$sql)
                  or die("Couldn't execute query 1");     #21
      $num = mysqli_num_rows($result);
      if($num == 1)                                      #23
      {
         $sql = "SELECT user_name FROM $table_name 
                 WHERE user_name='$_POST[fusername]'
                 AND password=md5('$_POST[fpassword]')";
         $result2 = mysqli_query($cxn,$sql)
                   or die("Couldn't execute query 2.");  
         $row = mysqli_fetch_assoc($result2);            #30
         if($row)                                        #31
         {
           $_SESSION['auth']="yes";                      #33
           $_SESSION['logname'] = $_POST['fusername'];   #34
           header("Location: $next_program");            #35
         }
         else                                            #37
         {
           $message_1="The Login Name, '$_POST[fusername]' 
                   exists, but you have not entered the 
                   correct password! Please try again.<br>";
           extract($_POST);
           include("fields_login.inc");
           include("double_form.inc");
         }                                               #45
      }                                                  #46
      elseif ($num == 0)  // login name not found        #47
      {
         $message_1 = "The User Name you entered does not 
                       exist! Please try again.<br>";
         include("fields_login.inc");
         include("double_form.inc");
      }
    break;                                               #54
    case "Register":                                     #55
      /* Check for blanks */
      foreach($_POST as $field => $value)                #57
      {
        if ($field != "fax")
        {
          if ($value == "")
          {
               $blanks[] = $field;
          }
        }
      }                                                  #66
      if(isset($blanks))                                 #67
      {
          $message_2 = "The following fields are blank. 
                Please enter the required information:  ";
          foreach($blanks as $value)
          {
            $message_2 .="$value, ";
          }
          extract($_POST);
          include("fields_login.inc");
          include("double_form.inc");
          exit();
      }                                                  #79
      /* validate data */
      foreach($_POST as $field => $value)                #81
      {
        if(!empty($value))                               #83
        {
          if(eregi("name",$field) and
             !eregi("user",$field) and !eregi("log",$field))
          {
             if (!ereg("^[A-Za-z' -]{1,50}$",$value)) 
             {
                $errors[] = "$value is not a valid name."; 
             }
          }
          if(eregi("street",$field)or eregi("addr",$field) or
             eregi("city",$field))
          {
             if(!ereg("^[A-Za-z0-9.,' -]{1,50}$",$value))
             {
                $errors[] = "$value is not a valid address
                              or city.";
             }
          }
          if(eregi("state",$field))
          {
             if(!ereg("[A-Za-z]",$value))
             {
                $errors[] = "$value is not a valid state.";
             }
          }
          if(eregi("email",$field))
          {
             if(!ereg("^.+@.+\\..+$",$value))
             {
                $errors[] = "$value is not a valid email
                             address.";
             }
          }
          if(eregi("zip",$field))
          {
             if(!ereg("^[0-9]{5,5}(\-[0-9]{4,4})?$",$value))
             {
                $errors[] = "$value is not a valid zipcode.";
             }
          }
          if(eregi("phone",$field) or eregi("fax",$field))
          {
             if(!ereg("^[0-9)(xX -]{7,20}$",$value))
             {
                $errors[] = "$value is not a valid phone 
                             number. ";
             }
          }
        }                                               #132
      }
      foreach($_POST as $field => $value)               #134
      {
        if($field != "Button")
        {
           if($field == "password")
           {
              $password = strip_tags(trim($value));
           }
           else
           {
              $fields[]=$field;
              $value = strip_tags(trim($value));
              $values[] = addslashes($value);
              $$field = $value;                 
           }
        }
      }
      if(@is_array($errors))                            #150
      {
        $message_2 = "";
        foreach($errors as $value)
        {
           $message_2 .= $value." Please try again<br />";
        }
        include("fields_login.inc");
        include("double_form.inc");
        exit();
      } 
      $user_name = $_POST['user_name'];                                                #139

      /* check to see if user name already exists */
      $cxn = Connect_to_db("Vars.inc");
      $sql = "SELECT user_name FROM $table_name 
                WHERE user_name='$user_name'"; #158
      $result = mysqli_query($cxn,$sql)
                or die("Couldn't execute query.");
      $num = mysqli_num_rows($result);                  #169
      if ($num > 0)                                     #170
      {
        $message_2 = "$user_name already used. Select another
                         User Name.";
        include("fields_login.inc");
        include("double_form.inc");
        exit();
      }
      else                                              #178
      {   
        $today = date("Y-m-d");                         #180
        $fields_str = implode(",",$fields);
        $values_str = implode('","',$values);
        $fields_str .=",create_date";
        $values_str .='"'.",".'"'.$today;
        $fields_str .=",password";
        $values_str .= '"'.","."md5"."('".$password."')";
        $sql = "INSERT INTO $table_name ";
        $sql .= "(".$fields_str.")";
        $sql .= " VALUES ";
        $sql .= "(".'"'.$values_str.")";
        mysqli_query($cxn,$sql) or die(mysqli_error($cxn));                 #165
        $_SESSION['auth']="yes";                        #192
        $_SESSION['logname'] = $user_name;              #193
        /* send email to new Customer */
        $emess = "You have successfully registered. ";
        $emess .= "Your new user name and password are: ";
        $emess .= "\n\n\t$user_name\n\t";
        $emess .= "password\n\n";
        $emess .= "We appreciate your interest. \n\n";
        $emess .= "If you have any questions or problems,";
        $emess .= " email service@ourstore.com";        #201
        $subj = "Your new customer registration";       #202
        #$mailsend=mail("$email","$subj","$emess");     #203
        header("Location: $next_program");              #204
      }
    break;                                              #206

    default:                                            #208
           include("fields_login.inc");
           include("double_form.inc");
  }
?>
