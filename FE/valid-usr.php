<?php
      require_once "general.php";
      include("config.php");
      
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $myuserid = mysqli_real_escape_string($db,$_POST['userid']);
      //$mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      /* Empezamos la sesión */
      session_start();
 
      /* Creamos la sesión */
      $_SESSION['username'] = $_POST['username'];
      $_SESSION['userid'] = $_POST['userid'];
      
      if ($_POST['login']) {
         $sql = "SELECT ID_User FROM users WHERE ID_User='$myuserid' AND Name='$myusername'"; // and passcode = '$mypassword'";
         $result = mysqli_query($db,$sql);
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
         $active = $row['active'];
      
         $count = mysqli_num_rows($result);
         
         if($count == 1) {
            header("location: index-1.php");
         }
         else {
            header("location: index.php?error=true");
         }
      } 
      else if ($_POST['signup']) {
            $sql = "SELECT ID_User FROM users WHERE ID_User='$myuserid'"; // and passcode = '$mypassword'";
            $result = mysqli_query($db,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $active = $row['active'];
      
            $count = mysqli_num_rows($result);

            if($count == 1) {
               header("location: index.php?error1=true");
            }
            else {
               if ($myuserid<100600 || $myuserid>100651)
                  header("location: index.php?error=true");
               else {
                  $sql1 = "INSERT INTO users (ID_User,Name) VALUES ('$myuserid', '$myusername')";
                  $result = mysqli_query($db,$sql1);
                  //header("location: index.php?error2=true");
                  header("location: index-1.php?");
               }
            }
      }
      
      
?>