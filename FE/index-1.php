  <?php 

  require_once "general.php";
      
  session_start(); 
  $id_usr= $_SESSION['username'];
  $username= $_SESSION['username'];
  $userid= $_SESSION['userid'];

  ?>
  
  <?php
    //calc($userid,takeCritics());
    $link = mysql_connect("$DB_hostname", "$DB_username", "$DB_password") or die("Unable to connect to MySQL");
    $conn = mysql_select_db("$DB_DB",$link);
    
    //if( isset($id_lo1) and isset($id_usr1)){
      $criticas=takeCritics();
      $sql1 = "INSERT INTO `critics` (id_lo,critica) 
               VALUES ('$userid','$criticas');";
      $result1 = mysql_query($sql1);
    //}
    mysql_close($link);
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
  <meta charset="UTF-8">
    <title><?php echo $project_title; ?></title>
      <!-- JQuery -->
  <script src="js/jquery-1.10.2.min.js"></script>
      <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="css/style-original.css" rel="stylesheet" media="screen">
  <script src="js/bootstrap.min.js"></script>    
      <!-- for the dragging of the weights on the stats page -->
  <link href="css/dragdealer.css" rel="stylesheet" media="screen">
  <script type="text/javascript" src="js/dragdealer.js"></script>
      <!-- for the nice math formula visualization on the hybrid explanation-->
  <script type="text/javascript"  src="js/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> </script>   
      <!-- for the fancy circle drawn by D3 -->
  <script src="js/d3.v3.min.js" charset="utf-8"></script>  
      <!-- include custom javascript code -->
  <script src="js/index.js"></script>    
  </head>
  <body class='thebackground'>
      
       
  <div class="container">
  
  <div class='row-fluid'>
  <div class='span11 offset1'>
  <div class="page-header">
      <h1 style="color:#FF8000"; align=center ><?php echo $project_title; ?> </br> <small> <?php echo $project_subtitle; ?></small>
  <small> <br><?php   //$sql= 'SELECT Name FROM users WHERE ID_User='.$id_usr;
  //$stat= prepareStatement($sql);
                              //$stat->execute();
  //$rows= $stat->fetchAll();
                              //foreach ($rows as $row)
                              //{
  //    $name= $row['Name'];
                              //}
                              //echo "Hi, ".$name; 
                              calc($userid,takeCritics());
                              echo 'Hi, '. $username . '!';?></small>
      </h1>
      </div>
      </div>
    </div>
  <div class="row"> 
  <div class="span11">
        <!--Body content-->
  <div class="navbar">
  <div class="navbar-inner">
                
  <!--  <a class="brand" href='?'><?php //echo $project_title; ?></a> -->
             
          <!-- -->
  <ul class="nav">
  <li class='tab-header active algo-header' id='tab-hybrid'><a href="index-1.php" onclick='location.reload(true);show_tabs("hybrid")'>Get Recommnedations!</a></li>
  <li class='tab-header algo-header' id='tab-movies'><a href="#" onclick='show_tabs("movies")'>Educational Resources</a></li>
              <!--<li> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li> -->
  <li class='tab-header algo-header' id='tab-movies'>
  <div class="input-append movies_search_button">
  <input id='input_movies_search' type="text" value='<?php echo $search; ?>'>
  <button id='input_movies_search_btn' class="btn" type="button" onclick='show_tabs("movies");search_movies()'>Search</button> 
  <button class="btn" type="button" onclick='clear_search()'>Clear</button>
                </div>
                 </li>
              <li>&nbsp;&nbsp;</li>
  <li class='tab-header algo-header' id='tab-hybrid'><a href="logout.php">Log out</a></li>
              </li>
              
          </ul>
          
        </div>
      </div>
      
        </div>
    </div>
  <div class="container">
  <div class="row">
  <div class="span11">
          
                     
          
  <div id='hybrid' class='tab'>    
              
          </div>
  <div id='movies' class='tab hidden'>    
          
          </div>
  <div id='stats' class='tab hidden'>    
              Loading stats...
          </div>        
            
      </div>
     </div>
  </div>
  </div>
  </body>
  </html>
  
  

