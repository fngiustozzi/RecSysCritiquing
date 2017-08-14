<?php
    require_once "general.php";

    //echo $_POST['rel1'];
    $id_usr1 = $_POST[usrid];
    $id_lo1 = $_POST[id_lo];
    $rat1 = $_POST[rat];
    
    
    $link = mysql_connect("$DB_hostname", "$DB_username", "$DB_password") or die("Unable to connect to MySQL");
    $conn = mysql_select_db("$DB_DB",$link);
    
    
    if( isset($id_lo1) and isset($id_usr1)){
      $del1 = "DELETE from `ratings` WHERE user_id='$id_usr1' AND lo_id='$id_lo1';";
      $result2 = mysql_query($del1);
      $sql1 = "INSERT INTO `ratings` (user_id,lo_id,rating) 
               VALUES ('$id_usr1','$id_lo1','$rat1');";
      $result1 = mysql_query($sql1);
      //echo $result2."usuario: ".$id_usr1." idlo:".$id_lo1."rating:". $rat1;


      //$file = fopen("dist/k1.txt", "a");
      //fwrite($file, PHP_EOL . "$id_usr1,$id_lo1,$rat1");
      //fclose($file);

      /*
      if(!$result1){
        echo mysql_error();
      }
      */
    }
    
    echo "Thank you for your Rank!";
    
    mysql_close($link);
    
?>