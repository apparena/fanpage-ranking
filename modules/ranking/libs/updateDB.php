<?php

try
{
    require_once("../../../init.php");
}
catch (Exception $e)
{
    echo '<pre>';
    print_r($e->getMessage());
    echo '</pre>';
    echo '<pre>';
    print_r($e->getTrace());
    echo '</pre>';
    exit();
}

// to test    http://localhost/fanpageranking0.2_backbone1/modules/ranking/libs/updateDB.php?aa_inst_id=5618

$aa_inst_id = $aa['instance']['aa_inst_id'];


$fansPagesIdsAsStr = $aa['config']['fanpage_ids']['value'];
$aaFansPagesIdsAsArray = explode(';',$fansPagesIdsAsStr);
//'163721403669672;106479362709892;212285335468828;160805495421;173099949403228;263416953165;332236356800801;241806709206264;168513559842620;329596519636;218266734578;153048968094202';


// set today
$today = date('Y-m-d', time());




//create array of all ids in DB that match the aa_inst_id  (database used to store many aa_inst_id )
$query1 = "SELECT fb_page_id FROM fanpage_basic_data";
$query2 = $db->query($query1);
$dbFansPagesIdsArray = $query2->fetchAll(PDO::FETCH_COLUMN);



// getting the array of new ids entered in aa for the app_inst_id   // these ids will be requested from fb and inserted in db
$differenceArray = array_diff($aaFansPagesIdsAsArray, $dbFansPagesIdsArray);
$differenceArrayNumber = count($differenceArray);

// getting the array of existing ids in the database for the app_inst_id  // these ids will be requested from fb and updated in db
$intersectionArray = array_intersect($aaFansPagesIdsAsArray, $dbFansPagesIdsArray );
$intersectionArrayNumber = count($intersectionArray);


//test
var_dump($today);
echo '<br><br>';
var_dump($aa_inst_id);
echo '<br><br>';
var_dump($aaFansPagesIdsAsArray);
echo '<br><br>';
var_dump($dbFansPagesIdsArray);
echo '<br><br>';
var_dump($differenceArray);
echo '<br><br>';
var_dump($differenceArrayNumber);
echo '<br><br>';
var_dump($intersectionArray);
echo '<br><br>';
var_dump($intersectionArrayNumber);
echo '<br><br>';







if(!empty($differenceArray)){  //insert new rows into metric DB and into basic DB
    foreach($differenceArray as $id){
        //get string Object of fb
        $json = file_get_contents('https://graph.facebook.com/'.$id);

        if($json != FALSE){   //if facebook request succeed   -- file_get_contents() returns FALSE
            //transform to array
            $json_array = json_decode($json, true);

            //set name
            if($json_array['name'] != NULL){ $name = $json_array['name'];    }
            else {$name = 'no name';}
            var_dump($name);
            echo '<br><br>';

            //set likes
            if($json_array['likes'] != NULL){ $likes = $json_array['likes'];    }
            else {$likes = 'no likes';}
            var_dump($likes);
            echo '<br><br>';

            //set talking_about_count
            if($json_array['talking_about_count'] != NULL){ $talking_about_count = $json_array['talking_about_count'];    }
            else {$talking_about_count = 'no talking_about_count';}
            var_dump($talking_about_count);
            echo '<br><br>';

            //set description
            if($json_array['description'] != NULL){ $description = $json_array['description'];    }
            else {$description = 'no description';}
            var_dump($description);
            echo '<br><br>';

            //insert into basic DB
            $query3 = "INSERT INTO
                           fanpage_basic_data
                        SET
                            fb_page_id = :fb_page_id,
                            name = :name,
                            description = :description
                      ";
                $query4 = $db->prepare($query3);
                $query4->bindParam(':fb_page_id', $id, PDO::PARAM_INT);
                $query4->bindParam(':name', $name, PDO::PARAM_STR);
                $query4->bindParam(':description', $description, PDO::PARAM_STR);

                $query4->execute();
            //insert into metric DB
            $query5 = "INSERT INTO
                           fanpage_metric_data
                        SET
                            date = :date,
                            fb_page_id = :fb_page_id,
                            likes = :likes,
                            talking_about_count = :talking_about_count
                      ";
                $query6 = $db->prepare($query5);
                $query6->bindParam(':fb_page_id', $id, PDO::PARAM_INT);
                $query6->bindParam(':date', $today);
                $query6->bindParam(':likes', $likes, PDO::PARAM_INT);
                $query6->bindParam(':talking_about_count', $talking_about_count, PDO::PARAM_INT);

                $query6->execute();
        }
        else {var_dump('request to facebook API did not succeed');}
    }
}
else{ var_dump('no new fan page ids to insert in metric DB and in basic DB'); echo '<br><br>';}









// insert rows in metric DB and update rows in basic DB
$toUpdateNotTodayInserted = array_diff($dbFansPagesIdsArray, $differenceArray);  //this will exclude the same day inserted new rows
if(!empty($toUpdateNotTodayInserted)){
    foreach($toUpdateNotTodayInserted as $id){
         //get string Object of fb
         $json = file_get_contents('https://graph.facebook.com/'.$id);

         if($json != FALSE){   //if facebook request succeed   -- file_get_contents() returns FALSE
             //transform to array
             $json_array = json_decode($json, true);

             //set name
             if($json_array['name'] != NULL){ $name = $json_array['name'];}
             else {$name = 'no name';}
             var_dump($name);
             echo '<br><br>';

             //set likes
             if($json_array['likes'] != NULL){ $likes = $json_array['likes'];}
             else {$likes = 'no likes';}
             var_dump($likes);
             echo '<br><br>';

             //set talking_about_count
             if($json_array['talking_about_count'] != NULL){ $talking_about_count = $json_array['talking_about_count'];    }
             else {$talking_about_count = 'no talking_about_count';}
             var_dump($talking_about_count);
             echo '<br><br>';

             //set description
             if($json_array['description'] != NULL){ $description = $json_array['description'];    }
             else {$description = 'no description';}
             var_dump($description);
             echo '<br><br>';

             // update basic DB
             $query7 = "UPDATE
                            fanpage_basic_data
                          SET
                             name = :name,
                             description = :description
                          WHERE fb_page_id = '$id'
                        ";
                 $query8 = $db->prepare($query7);
                 $query8->bindParam(':name', $name, PDO::PARAM_STR);
                 $query8->bindParam(':description', $description, PDO::PARAM_STR);

                 $query8->execute();


             //insert into metric DB
             $query9 = "INSERT INTO
                            fanpage_metric_data
                         SET
                             date = :date,
                             fb_page_id = :fb_page_id,
                             likes = :likes,
                             talking_about_count = :talking_about_count
                       ";
                 $query10 = $db->prepare($query9);
                 $query10->bindParam(':fb_page_id', $id, PDO::PARAM_INT);
                 $query10->bindParam(':date', $today);
                 $query10->bindParam(':likes', $likes, PDO::PARAM_INT);
                 $query10->bindParam(':talking_about_count', $talking_about_count, PDO::PARAM_INT);

                 $query10->execute();
                 var_dump('all existing fanpages in DB are updated'); echo '<br><br>';
         }
         else {var_dump('request to facebook API did not succeed');}
    }
}
else{ var_dump('no new fan page ids to update basic DB and insert into metric DB'); echo '<br><br>';}








?>




