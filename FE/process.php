<?php
    require_once "general.php";
    $i=0;
    
    foreach ($_POST['language_critic'] as $names)
    {
            $language[$i]=$names;
            $i++;
            //print "You are selected $names<br/>";
    }

    	function calc($userid){
                                $id_USR = $userid;
                                $command = 'java -jar RecsGenerator.jar '.$id_USR; 
                                chdir('/home/ubuntu/workspace/dist');
                                shell_exec($command);
                                chdir('/home/ubuntu/workspace');
                                $command1 = 'cat dist/recommendations-'.$id_USR.'.json';
                                $json = shell_exec($command1);
                                $array = json_decode($json);
                                return $array;
                              }
    //$link = mysql_connect("$DB_hostname", "$DB_username", "$DB_password") or die("Unable to connect to MySQL");
    //$conn = mysql_select_db("$DB_DB",$link);
    
    
    //if( isset($id_lo1) and isset($id_usr1)){
    //  $del1 = "DELETE from `ratings` WHERE user_id='$id_usr1' AND lo_id='$id_lo1';";
    //  $result2 = mysql_query($del1);
    //  $sql1 = "INSERT INTO `ratings` (user_id,lo_id,rating) 
    //           VALUES ('$id_usr1','$id_lo1','$rat1');";
    //  $result1 = mysql_query($sql1);
      //echo $result2."usuario: ".$id_usr1." idlo:".$id_lo1."rating:". $rat1;


      //$file = fopen("dist/k1.txt", "a");
      //fwrite($file, PHP_EOL . "$id_usr1,$id_lo1,$rat1");
      //fclose($file);

      /*
      if(!$result1){
        echo mysql_error();
      }
      */
    //}
    
    //print_r("Thank you for your Rank!".$id_usr2);
    foreach ($language as $lang)
    {
            //echo "You selected $lang<br/>";
    }
    //mysql_close($link);
    
?>