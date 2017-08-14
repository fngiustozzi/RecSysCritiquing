<?php
   require_once "general.php";
   include("config.php");
   //session_start();
   
?>
<html>
   
   <head>
      <title>Login Page</title>
      <link href="css/style-original.css" rel="stylesheet" media="screen">
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         
         .box {
            border:#666666 solid 1px;
         }
      </style>
      
   </head>
   
   <body class='thebackground'>
	
      <div align = "center">
         <div class="page-header">
            <h1 style="color:#FF8000"><?php echo $project_title; ?> <br> <small> <?php echo 'of '.$project_subtitle; ?> <br> <br></small></h1>
         </div>
         <div style = "width:300px; border: solid 1px #FF8000; " align = "left">
            <div style = "background-color:#FF8000; color:#FFFFFF; padding:3px;"><b>Welcome!</b></div>
            <div style = "margin:30px">
               <form action = "valid-usr.php" method = "post">
                  <label>Name: &nbsp;&nbsp;&nbsp;</label><input type = "text" name='username' class = "box"/><br /><br />
                  <label>User ID: &nbsp;</label><input type = "text" name="userid" class = "box" /><br/><br />
                  <!--<label>User ID  :</label><input type = "password" name = "password" class = "box" /><br/><br />-->
                  <center>
                  <table>
                     <tr>
                        <td> <center> <input type = "submit" name = "login" value = "Log in"/> </center> </td>
                        <td> <center> <input type = "submit" name = "signup" value = "Sign up"/> </center> </td>
                     </tr>
                  </table>
                  </center>
               </form>
               <center> <div style = "font-size:11px; color:#000000; margin-top:10px"><?php echo "<b>Log in</b> if you already have an account.</br> <b>Sign up</b> if you do not have an account yet. "?></div> </center>
               <center> <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php if (isset($_GET['error'])) { echo "Your Name or User ID is invalid"; }
                                                                                            if (isset($_GET['error1'])) { echo "User ID already exists. Please choose another."; }
                                                                                            if (isset($_GET['error2'])) { echo "Account created successfully. Please Log in."; }?></div> </center>
					</div>
				</div>				
         </div>
			<!-- <div style = "font-size:11px; color:#cc0000; margin-top:10px"> Critiquing based Recomender System of Educational Resources </div> -->
      </div>

   </body>
</html>