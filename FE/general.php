<?php
/**  
*  Recsys-frontend, Copyright (c) 2013, Simon Dooms
*  http://github.com/sidooms/recsys-frontend
*  MIT License
*/
    //read the variables from the config/settings.ini file
    $settingspath = str_replace('general.php', '', __FILE__) . 'config/';
    $ini_array = parse_ini_file($settingspath . "settings.ini", true);
	
    $DB_username =  $ini_array['database']['db_username'];
	$DB_password = $ini_array['database']['db_password'];
	$DB_DB = $ini_array['database']['db_db'];
	$DB_hostname = $ini_array['database']['db_hostname'];
	$DB_port = $ini_array['database']['db_port'];
    
    $project_title = $ini_array['general']['project_title'];
    $project_subtitle = $ini_array['general']['project_subtitle'];


    /* Take critics from users */
    function takeCritics() {
        $lan='a';
        $mat='a';
        $easeuse='a';
        $tecfor='a';
        $cat='a';
        $tarpol='a';
        $crecom='a';
        $mobcom='a';
        $cosinv='a';
        $comm='a';
        if(isset($_POST['language_critic'])) {
            foreach ($_POST['language_critic'] as $names) {
                if($lan!='')
                    $lan=$lan.','.$names;
                else 
                    $lan=$lan.$names;
            }
        }
        if(isset($_POST['easeofuse_critic'])) {
            foreach ($_POST['easeofuse_critic'] as $names)  {
                if ($easeuse != '')
                    $easeuse=$easeuse.','.$names;
                else
                    $easeuse=$easeuse.$names;
            }
        }
        if(isset($_POST['mattype_critic'])) {
            foreach ($_POST['mattype_critic'] as $names)  {
                if ($mat != '')
                    $mat=$mat.','.$names;
                else
                    $mat=$mat.$names;
            }
        }
        if(isset($_POST['tecfor_critic'])) {
            foreach ($_POST['tecfor_critic'] as $names)  {
                if ($tecfor != '')
                    $tecfor=$tecfor.','.$names;
                else
                    $tecfor=$tecfor.$names;
            }
        }
        if(isset($_POST['cat_critic'])) {
            foreach ($_POST['cat_critic'] as $names)  {
                if ($cat != '')
                    $cat=$cat.','.$names;
                else
                    $cat=$cat.$names;
            }
        }
        if(isset($_POST['tarpol_critic'])) {
            foreach ($_POST['tarpol_critic'] as $names)  {
                if ($tarpol != '')
                    $tarpol=$tarpol.','.$names;
                else
                    $tarpol=$tarpol.$names;
            }
        }
        if(isset($_POST['creacom_critic'])) {
            $crecom=$crecom.$_POST['creacom_critic'];
        }
        if(isset($_POST['mobcom_critic'])) {
            $mobcom=$mobcom.$_POST['mobcom_critic'];
        }
        if(isset($_POST['costinv_critic'])) {
            $cosinv=$cosinv.$_POST['costinv_critic'];
        }
        if(isset($_POST['txtsugerencias'])) {
            $comm=$comm.$_POST['txtsugerencias'];
        }
        return $lan.'-'.$easeuse.'-'.$mat.'-'.$tecfor.'-'.$cat.'-'.$tarpol.'-'.$crecom.'-'.$mobcom.'-'.$cosinv.'-'.$comm.'comment';
    
        // return $mat;
    }

	/*
	* Prepares the given SQL instruction and returns a $statement variable
	* bind_param() can be used to pass on some other parameters. Like
	* 	$statement->bindParam(1, $id);
	*	$statement->execute();
	*/
	function prepareStatement($sql)
	{
		global $DB_hostname;
		global $DB_DB;
		global $DB_username;
		global $DB_password;
		try {
			$dbh = new PDO("mysql:host=$DB_hostname;dbname=$DB_DB", $DB_username,$DB_password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$stat = $dbh->prepare($sql);
			return $stat;
		}catch(PDOException $e){
			echo "Could not connect to database.";
            return false;
		}
	}
	
	function execStatement($sql)
	{
		global $DB_hostname;
		global $DB_DB;
		global $DB_username;
		global $DB_password;
		try {
			$dbh = new PDO("mysql:host=$DB_hostname;dbname=$DB_DB", $DB_username,$DB_password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$stat = $dbh->prepare($sql);
			$stat->execute();
			return true;
		}catch(PDOException $e){
			echo'Could not connect to database.';
			return false;
		}

	}
	
	function calc($userid,$lis){
        $id_USR = $userid;
        
        //chdir('/home/ubuntu/workspace/dist');
        //$com='echo java -jar RecsGenerator.jar '.$id_USR.' \"'.$lis.'\"'.' > a.txt';
        //shell_exec($com);
        
        
        //if (strpos($lis, '----------') !== false ){
        //    $command = 'java -jar RecsGenerator.jar '.$id_USR;  
        //}
        //else { 
        //$command2 = 'rm recommendations-'.$id_USR.'.json';
        //chdir('/home/ubuntu/workspace/dist');
        //shell_exec($command2);
        
        $command = 'java -jar RecsGenerator.jar '.$id_USR.' '.$lis;
        //}
        chdir('/home/ubuntu/workspace/dist');
        shell_exec($command);
        chdir('/home/ubuntu/workspace');
        $command1 = 'cat dist/recommendations-'.$id_USR.'.json';
        $json = shell_exec($command1);
        $array = json_decode($json);
        return $array;
    }
    

    function print_critics($userid) {
    
        //echo '<script>parent.window.location.reload(true);</script>';

        //print "You may like this reources!!!";
        
        //echo "<p> <font color='black' size=6>You may like this reources!!!</font></p>";
        
        $fh = fopen('dist/k1.txt', 'w');
        
        $sql = 'SELECT * FROM ratings';
        $stat = prepareStatement($sql);
        $stat->execute();
        $rows = $stat->fetchAll();
        foreach ($rows as $row) {
        //fwrite($fh, end($row));
        //mysql_connect('$DB_hostname', '$DB_username', 'password');
        //$result = mysql_query("SELECT * FROM ratings;");
        //while ($row = mysql_fetch_array($stat)) {
            //$last = end($row);
            //foreach ($row as $item) {
                //fwrite($fh, $item);
                fwrite($fh, "$row[0],$row[1],$row[2]");
                //if ($item != $last)
                //fwrite($fh, ",");
            //}
            fwrite($fh, "\n");
        }
        fclose($fh);

        $id_USR = $userid;
        //$aa = takeCritics();
        //$command = 'java -jar RecsGenerator.jar '.$id_USR.' \"'.$aa.'\"';
        //chdir('/home/ubuntu/workspace/dist');
        //shell_exec($command);
        // $com='mkdir a'.takeCritics();
        // shell_exec($com);
        //echo "<pre>$salida</pre>";
        //sleep(10);
        chdir('/home/ubuntu/workspace');
        $command1 = 'cat dist/recommendations-'.$id_USR.'.json';
        $json = shell_exec($command1);
        $array = json_decode($json);
        //$array = calc($id_USR,takeCritics());
        // VERIFICAR QUE EL ARCHIVO recommendations.json CONTENGA DATOS

        if (sizeof($array) > 0) {
            $r = $array[0]->critic; //las criticas que no se tuvieron en cuenta porque generaban un espacio de busqueda vacip
            $arr = $array[0]->general->recommendations;
            if (sizeof($arr) > 0) {
            echo "<p> <font color='#FF8000' size=5>We have the following: </font></p>";    
            $ind = 0;
            foreach($arr as $obj) {
                if ($ind < 4) {
                //print $obj->object;
                $ind++;
                $id_LO = $obj->object;
                $sql = 'SELECT ID_LO, Title, Material_Type, Description, Language, Merlot_Classic, ID_Author FROM lodata WHERE ID_LO='.$id_LO;
                $stat = prepareStatement($sql);
                $stat->execute();
                $rows = $stat->fetchAll();
                foreach ($rows as $row) {
                    $movieid = $row['ID_LO'];
                    $title = $row['Title'];
                    $year = $row['Material_Type'];
                    $desc = $row['Description'];
                    $language = $row['Language'];
                    $merlot_classic = $row['Merlot_Classic'];
                    $id_author = $row['ID_Author'];
                    $sql1 = 'SELECT Name FROM loauthors WHERE ID_Author='.$id_author;
                    $stat1 = prepareStatement($sql1);
                    $stat1->execute();
                    $rows1 = $stat1->fetchAll();
                    foreach ($rows1 as $row1) {
                        $name_author = $row1['Name'];
                    }
                    $sql2 = 'SELECT ID_Cat FROM locat_dat WHERE ID_LO='.$movieid;
                    $stat2 = prepareStatement($sql2);
                    $stat2->execute();
                    $rows2 = $stat2->fetchAll();
                    $i = 0;
                    $categs = '';
                    foreach ($rows2 as $row2) {
                        $categories[$i] = $row2['ID_Cat'];
                        $sql3 = 'SELECT Categorie FROM locategories WHERE ID_Cat='.$categories[$i];
                        $stat3 = prepareStatement($sql3);
                        $stat3->execute();
                        $rows3 = $stat3->fetchAll();
                        foreach ($rows3 as $row3) {
                            $categs .= ', ' . $row3['Categorie'];
                        }
                        $todascategories = substr($categs,2);
                        $i++;
                    }
                print_movie($movieid, $title, $year, $desc, $mat_type, $language, $merlot_classic, $name_author, $todascategories, $data, $userid,$r);
                }
                }
            }
            echo "<p> <font color=#FF8000 size=5>Or would you like to improve some value(s) by yourself?</font></p>    ";
            echo "<div class='nuevo1 well well-small' id='movie-<?php print $movieid; ?>'; align=center>
                    <form method='post' action='index-1.php'>
                    <table>
							<tr>
                                <td> <strong> Language: </strong> </td>
                                <td>
                                    <select name='language_critic[]' style='width: 180px' multiple='multiple' size='3'>
	                                    <option value='any' selected> </option>
	                                    <option value='English'>English</option>
	                                    <option value='Spanish'>Spanish</option>
	                                    <option value='Portuguese'>Portuguese</option>
	                                </select>
								</td>
								<td> &nbsp;&nbsp;&nbsp; </td>
								<td> <strong>Ease of use:</strong> </td>
                                <td> <select name='easeofuse_critic[]' style='width: 180px' multiple='multiple' size='3'>
                                    	<option value='any' selected> </option>
                                    	<option value='HighSchool'>Students - High School</option>
                                    	<option value='University'>Students - University</option>
                                    	<option value='Professors'>Professors</option>
                                	</select>
								</td>
                        	</tr>
                        	<tr>
								<td> <strong> Mat. Type:</strong> </td>
                                <td> <select name='mattype_critic[]' style='width: 180px' multiple='multiple' size='3'>
                                    	<option value='any' selected> </option>
                                    	<option value='Tutorial'>Tutorial</option>
                                    	<option value='Presentation'>Presentation</option>
                                    	<option value='Article'>Article</option>
                                    	<option value='Book'>Book</option>
                                    	<option value='Simulation'>Simulation</option>
                                    	<option value='Animation'>Animation</option>
                                    	<option value='Collection'>Collection</option>
                                    	<option value='ReferenceMaterial'>Reference Material</option>
                                    	<option value='OpenTextbook'>Open Textbook</option>
                                    	<option value='OnlineCourse'>Online Course</option>
                                    	<option value='CaseStudy'>Case Study</option>
                                    	<option value='DrillandPractice'>Drill and Practice</option>
                                    	<option value='Quiz'>Quiz/Test</option>
                                    	<option value='Workshop'>Workshop</option>
                                	</select>
								</td>
								<td> &nbsp;&nbsp;&nbsp; </td>
								<td> <strong> Technical Format:</strong> </td>
                                <td> <select name='tecfor_critic[]' style='width: 180px' multiple='multiple' size='3'>
                                    	<option value='any' selected> </option>
                                    	<option value='PDF'>PDF</option>
                                    	<option value='HTML'>HTML</option>
                                    	<option value='Video'>Video</option>
                                    	<option value='Audio'>Audio</option>
                                    	<option value='PowerPoint'>PowerPoint</option>
                                	</select>
								</td>
                            </tr>
            				<tr>
								<td> <strong> Category:</strong> </td>
                            	<td> <select name='cat_critic[]' style='width: 180px' multiple='multiple' size='3'>
                                	    <option value='any' selected> </option>
                                	    <option value='Eductional'>Eductional</option>
                                	    <option value='ComputerScience'>Computer Science</option>
                                	    <option value='ArtificialIntelligence'>Artificial Intelligence</option>
                                	    <option value='Statistics'>Statistics</option>
                                	    <option value='History'>History</option>
                                	    <option value='Network'>Network</option>
                                	    <option value='Genetics'>Genetics</option>
                                	    <option value='Biology'>Biology</option>
                                	    <option value='InformationTechnology'>Information Technology</option>
                                	    <option value='ScienceandTechnology'>Science and Technology</option>
                                	    <option value='Ethics'>Ethics</option>
                                	    <option value='Sociology'>Sociology</option>
                                	    <option value='Meteorology'>Meteorology</option>
                                	    <option value='Paleontology'>Paleontology</option>
                                	    <option value='Business'>Business</option>
                                	</select>
                            	</td>
                            	<td> &nbsp;&nbsp;&nbsp; </td>
								<td> <strong> Target population:</strong> </td>
                                <td> <select name='tarpol_critic[]' style='width: 180px' multiple='multiple' size='3'>
                                    	<option value='any' selected> </option>
                                    	<option value='HighSchool'>Students - High School</option>
                                    	<option value='University'>Students - University</option>
                                    	<option value='Professors'>Professors</option>
                                	</select>
								</td>
							</tr>
							</table>
							<table>
							<tr> 
							    <td> <hr style='color: #FF8000; width:500px'/> </td>
							</tr>
							</table>
							<table>
								<td> <strong> Cost Involved:</strong> </td>
                            	<td> <select name='costinv_critic' style='width: 180px'>
                                	    <option value='any' selected> </option>
                                	    <option value='Yes'>Yes</option>
                                	    <option value='No'>No</option>
                                	</select>
                            	</td>
                            	<td> &nbsp;&nbsp;&nbsp; </td>
								<td> <strong> Mobile Compatibility:</strong> </td>
                                <td> <select name='mobcom_critic' style='width: 180px'>
                                    	<option value='any' selected> </option>
                                    	<option value='Android'>Android</option>
                                    	<option value='iOs'>iOS</option>
                                	</select>
								</td>
							</tr>
							<tr>
								<td> <strong> Creative Commons:</strong> </td>
                                <td> <select name='creacom_critic' style='width: 180px'>
                                    	<option value='any' selected> </option>
                                    	<option value='Yes'>Yes</option>
                                    	<option value='No'>No</option>
                                     </select>
								</td>
							</tr>
					</table>
					<table>
							<tr> 
							    <td> <hr style='color: #FF8000; width:500px'/> </td>
							</tr>
							</table>
					<table>
							<tr>
								<td> <strong> Comments:</strong> </td>
                                <td>  <textarea rows='2' cols='300' style='width:525px' name='txtsugerencias'></textarea> </td>
							</tr>
					</table>
					<table>		
							<tr>
							<td></td>
                                <!-- <button class='btn btn-block pull-right btn-danger' onclick='rate_movie(<?php print $movieid; ?>, 2, event)'>Show Results</button> -->
                                <td> <button class='btn btn-block pull-right btn-danger' style='width:300px' onclick='location.reload(true);show_tabs('hybrid');location.reload(true);show_tabs('hybrid')'><a href='index-1.php'>Refresh</a></button> </td>
							</tr>                    
					</table>
					</form>
    </div>";
        }
        else { echo "<p> <font color=#FF8000 size=5>No Recommendations for you! Please rank some resources to obtain Recommendations</font></p>    ";}
        }
        else { echo "<p> <font color=#FF8000 size=5>No Recommendations for you! Please rank some resources to obtain Recommendations</font></p>    ";}


        ?>
        
    <?php
    

    }
    
    function print_movie($movieid, $title, $year, $desc, $mat_type, $language, $merlot_classic, $name_author, $categories, $data, $idusr,$r)
    {

        $searchstring = 'Searching moviedata...';
        
        $tab = $data['tab']; 
        
        $cleanmovieid = $movieid;
        $movieid = $tab . $movieid;
        if (!empty($data['explanation']))
            $explanationtrigger = "<a href='#'  onclick='toggle_explanation(this, event,\"$movieid\")'>Show explanation</a>";
        else
            $explanationtrigger = '';
       
        
    ?>
        <script>
            $(function() {
                toggle_extra_movie_data("<?php print $movieid; ?>","<?php print $title; ?>","<?php print $year; ?>");
            });
        </script>
    <div class='nuevo1 well well-small' id='movie-<?php print $movieid; ?>' onclick='toggle_extra_movie_data("<?php print $movieid; ?>","<?php print $title; ?>","<?php print $year; ?>")'>
   
        
                <article>
                    <header>
                        <h4><?php print $title; ?> 
                        </h4> 
                          
                    </header>
                     
                   <div id='extramoviedata-<?php print $movieid; ?>'>
                         <div class='hidden searched'>false</div>
                         <div class='row-fluid'>
                         <div class="span12">
                            <div class='span2'>
                                <!--'http://placehold.it/113x150'-->
                                <img id='poster-<?php print $movieid; ?>' src='/aaa.png' class='img-polaroid poster'>
                            </div>
                            <div class='span8'>
     <?php if (!empty ($data['recvalue'])){ ?>
                                <div class='row-fluid'>
                                    <div class='span2 ' >
                                       <strong>Rec value:</strong>
                                    </div>
                                    <div class='span10'><?php print $data['recvalue']; ?> (<?php print $data['algo']; ?>) <?php print $explanationtrigger; ?>    </div>
                                 </div>
    <?php if (!empty ($data['explanation'])){ ?>
                                 <div class='row-fluid hidden' id='explanation-<?php print $movieid; ?>'>
                                    <div class='span2 ' >
                                       <strong></strong>
                                    </div>
                                    <div class='span10'><?php print str_replace("\n",'<br>',$data['explanation']); ?></div>
                                 </div>
    <?php } ?> 
    <?php } ?>                             
                                 <div class='row-fluid'>
                                    <div class='span2'>
                                        <strong> Language:</strong>
                                    </div>
                                    <div class='span10'><?php print $language ?></div>
                                 </div>
                                 <div class='row-fluid'>
                                    <div class='span2'>
                                        <strong> Mat. Type:</strong>
                                    </div>
                                    <div class='span10'><?php print $year ?></div>
                                 </div>
                                 <div class='row-fluid'>
                                    <div class='span2'>
                                        <strong> Author:</strong>
                                    </div>
                                    <div class='span10'><?php print $name_author ?></div>
                                 </div>
                                 <div class='row-fluid'>
                                    <div class='span2'>
                                        <strong> Categories:</strong>
                                    </div>
                                    <div class='span10'><?php if (strlen($categories) > 300){
                                                                print substr($categories,0,300).'...';
                                                              }
                                                              else {
                                                                print $categories;
                                                              }?></div>
                                 </div>
                                 <div class='row-fluid'>
                                    <div class='span2'>
                                       <strong>Description:</strong>
                                    </div>
                                    <div class='span10'><?php if (strlen($desc) > 300){
                                                                $descripcion = substr($desc,0,300).'... ';
                                                              }
                                                              else {
                                                                $descripcion = $desc.' ';
                                                              }
                                                              print $descripcion."<a href='https://www.merlot.org/merlot/viewMaterial.htm?id=$movieid' target='_blank'>Go to material!</a>"?></div>
                                 </div>
                                 <div class='row-fluid'>
                                    <div class='span10'><?php if (strlen($r)>0)
                                                                print "</br> <strong>Exclude criticisms: </strong>";
                                                              $cri = explode(",", $r);
                                                              for ($i = 1; $i <= 10; $i++) {
                                                                  if($cri[$i]!="a")
                                                                    print "<strike>$cri[$i]</strike> &nbsp;&nbsp;";
                                                              }           
                                                              ?></div>
                                 </div>
                            </div>
                            <div class='span2'>
                            <?php 
                                $hide_not_liked_status = '';
                                $hide_rating_buttons = '';
                                $hide_liked_status = '';
                                $hide_relevance_buttons = '';
                                $hide_goodrec_status = '';
                                $hide_badrec_status = '';
                                if (!$data['rated']){  
                                    //if movie has NOT been rated yet
                                    $hide_not_liked_status = 'hide';
                                    $hide_liked_status = 'hide';
                                }else{ 
                                    //if movie has been rated
                                    $hide_rating_buttons = 'hide';
                                    if ($data['rating'] == 10){ 
                                        //positive rating!
                                        $hide_not_liked_status = 'hide';
                                    }elseif ($data['rating'] == 1){    
                                        //negative rating
                                        $hide_liked_status = 'hide';
                                    }
                                }
                                if ($data['type'] == 'rec' || $data['type'] == 'hybrid' ){
                                    if (!$data['relevancefeedback']){
                                        //if NO relevance feedback has been given
                                        $hide_goodrec_status = 'hide';
                                        $hide_badrec_status = 'hide';
                                    }else{
                                        //relevance feedback has been given
                                        $hide_relevance_buttons = 'hide';
                                        if ($data['feedback'] == '5'){
                                            //good feedback
                                             $hide_badrec_status = 'hide';
                                        }else if ($data['feedback'] == '1'){
                                            //bad feedback
                                            $hide_goodrec_status = 'hide';
                                        }
                                    }
                                }else{
                                    //don't show relevance feedback buttons
                                    $hide_relevance_buttons = 'hide';
                                    $hide_goodrec_status = 'hide';
                                    $hide_badrec_status = 'hide';
                                }
                            ?>
                                <!-- movie rating buttons -->
                                
<!--<center id='liked-status-<?php print $movieid; ?>' class='<?php print $hide_liked_status; ?>'>
      <span>Liked </span>  
      <a href='#' onclick='cancel_rating("<?php print $movieid; ?>",event)'>(Cancel)</a>
    </center>
                            
    <center id='not-liked-status-<?php print $movieid; ?>' class='<?php print $hide_not_liked_status; ?>'>
      <span>Didn't Like</span> 
      <a href='#' onclick='cancel_rating("<?php print $movieid; ?>", event)'>(Cancel)</a>
    </center>      
                             
    <button class='btn btn-block pull-right btn-success <?php print $hide_rating_buttons; ?>' id='likebtn-<?php print $movieid; ?>' onclick='rate_movie("<?php print $movieid; ?>", "10", event)'>Like</button>
    <button class='btn btn-block pull-right btn-danger <?php print $hide_rating_buttons; ?>' id='dislikebtn-<?php print $movieid; ?>' onclick='rate_movie("<?php print $movieid; ?>", "1", event)'> Don't like</button> 
-->
<center>
  <!-- <form name="form'.$movieid'" method="post">  -->
    <select id='rank<?php print $movieid; ?>' style="width: 100px">
        <option value="0"> </option>
        <option value="1">1 - No good</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5 - Very good</option>
    </select> 
   
  <button class='btn btn-block pull-right btn-danger' onclick='rate_movie("<?php print $idusr; ?>", "<?php print $movieid; ?>", "2")'>Rank</button> 
 <!-- </form> -->
</center>
                            </div>
                            </div>
                    </div> <!-- extra movie data div -->
                </article>
               </div>               
       <?php
               
               
    }
    

    function print_paging($unique, $page, $movies_per_page,$tot_movies, $dive)
    {
        $num_buttons = 5;
        $disabled_prev = "";
        $disabled_next = "";
        $max_page = ceil($tot_movies / $movies_per_page) ;
        
        if ($page <= 1){
            $disabled_prev = "disabled";
        }
        if ($page >= ceil($tot_movies / $movies_per_page)){
            $disabled_next = "disabled";
        }
        ?>
        <div class="pagination  pagination-centered">
          <ul>          
            <!-- <li class='<?php echo $unique; ?> pager-button <?php echo $disabled_prev;?>'><a href="#" >First</a></li> -->
            <li class='<?php echo $unique; ?> pager-button <?php echo $disabled_prev;?>'><a href="#" >Prev</a></li>
            <?php
                //generate a number of buttons
                
                $stop = $page ;                
                while ($stop % $num_buttons != 0)
                    $stop += 1;
                $start = $stop - $num_buttons + 1;
                
                
                for ($i = $start; $i <= $stop ; $i++){
                    if ($i <= 0)
                        continue;
                    if ($i > $max_page)
                        continue;
                    if ($i != $page)
                        print "<li class='" . $unique . " pager-button'><a href='#'>$i</a></li>";
                    else
                        print "<li class='" . $unique . " active pager-button'><a href='#'>$i</a></li>";
                }
                
                
                $selects = array(5,10,50,100);
            ?>
            <li class='<?php echo $unique; ?> pager-button <?php echo $disabled_next;?>'><a href="#" >Next</a></li>
            <!-- <li class='<?php echo $unique; ?> pager-button <?php echo $disabled_next;?>'><a href="#" >Last</a></li> -->
          </ul>
          
            <span class="input-append">
                <select id='results-per-<?php echo $unique; ?>' class='resultsperpage' onchange="change_results_per_page(this,'<?php echo $dive; ?>')">
                <?php
                    foreach ($selects as $key)
                    {
                        if ($movies_per_page == $key)
                            $selected = 'selected="selected"';
                        else
                            $selected = '';
                        echo '<option '.$selected.' >' . $key . '</option>';
                    }
                ?>
                </select>
                <span class="add-on">results per page</span>
            </span>
        </div>
        
        
        <script>
            $(document).ready(function() { 
                //remove previously added events
                $("li.pager-button.<?php echo $unique; ?>").unbind();
                //add pager button events
                $("li.pager-button.<?php echo $unique; ?>").click(function(e) {
                    if (!$(this).hasClass('disabled')){
                        //get the page from clicked button                    
                        var goal_page = $(e.target).text();
                        //get the movies per page from button group
                        var goal_movies_per_page = get_results_per_page('<?php echo $unique; ?>');
                        var current_page = parseInt($("li.pager-button.<?php echo $unique; ?>.active:first").text());
                        if (goal_page.toLowerCase() == 'prev'){
                            goal_page = parseInt(current_page - 1);
                        }else if (goal_page.toLowerCase() == 'next'){
                            goal_page = parseInt(current_page + 1);
                        }else if (goal_page.toLowerCase() == 'home' ){
                            goal_page = 1;
                        }else if (goal_page.toLowerCase() == "end" ){
                            goal_page = <?php echo $max_page; ?>;
                        }
                        if (current_page != goal_page){   
                            get_movies(goal_page, goal_movies_per_page, '<?php echo $dive; ?>');
                        }
                    }
                    });
            });
         </script>
    
        
        <?php
        
        
        
    }
	
    function get_number_of_movies($search)
    {
        $SQL_FILTER = '';
        if ($search != ''){
             $SQL_FILTER = "WHERE  `Title` LIKE '%" . $search . "%' ";
        }
        
        $sql = "SELECT COUNT(*) from lodata " . $SQL_FILTER;
        $stat = prepareStatement($sql);
        $stat->execute();
        $res = $stat->fetchAll();
    
        return $res[0][0] ;
    }
    
    function get_number_of_recommendations($algo)
    {
        //$sql = "SELECT COUNT(*) FROM movies as m INNER JOIN recommendations as r ON m.movieid=r.movieid WHERE userid=999999 AND r.algorithm=?";
        //$stat = prepareStatement($sql);
        //$stat->bindParam(1, $algo);
        //$stat->execute();
        //$res = $stat->fetchAll();
    
        //return $res[0][0] ;
    }
    
    function get_number_of_hybridrecommendations($user)
    {
        $sql = "SELECT COUNT(*) FROM h_recommendations WHERE userid=?";
        $stat = prepareStatement($sql);
        $stat->bindParam(1, $user);
        $stat->execute();
        $res = $stat->fetchAll();
    
        return $res[0][0] ;
    }
    
     function valid_page($page, $movies_per_page, $tot_movies)
    {
        if ($movies_per_page <= 0)
            return false;
            
        if ($page > 0 and $page <= ceil($tot_movies / $movies_per_page))
            return true;
        else
            return false;
    }
    
	
?>
