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
      
      $sql = "SELECT ID_User FROM users WHERE ID_User='$myuserid' AND Name='$myusername'"; // and passcode = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
         //session_register("myusername");
         
         //$_SESSION['login_user'] = $myusername;
         //sesion_usr($myusername);
         header("location: index.php?error=true");
      }else {
         if ($myuserid<100500 || $myuserid>100550)
            header("location: index.php?error=true");
         //$error = "Your Login Name or Password is invalid";
         //echo $error;
         else {
            $sql1 = "INSERT INTO users (ID_User,Name) VALUES ('$myuserid', '$myusername')";
            $result = mysqli_query($db,$sql1);
            header("location: index.php");
         }
      }
?>