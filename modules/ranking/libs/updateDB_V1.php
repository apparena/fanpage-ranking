<?php

// //////////////////////////////////////////
$m_id = 280;
$today = date('Y-m-d', time());  //today
$error = '<br>';
$num_fanpages = 0;
/////////////////////////////////////////////

echo '-------------------------------------------<br>';
echo 'Date:' . $today . '<br>';
echo 'Errors:';




//now sebastian needs to implement this file without passing a parameter of aa_inst_id
// also in fanpage_metric_data i must add a condition to not allow insert of rows having the same date and the same fanpage_id,
//but allow insert of rows having same dates, not a problem

// use like this:
//WHERE   NOT EXISTS(   SELECT  0FROM    matable WHERE   maclefprimaire = 1);
// or this:
//WHERE NOT EXISTS (SELECT * FROM CHANGE WHERE CHANGE.currency = 'dollar');
//WHERE NOT EXISTS (SELECT * FROM fanpage_metric_data WHERE date = TODAY  AND fanpage_id = $fp_id);    // if argument inside NOT EXIST() is empty, then my condition will work

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









$json = file_get_contents('http://api.app-arena.com/v1/instances.json?m_id='.$m_id);
if($json != FALSE){   //if V1 request succeed   -- file_get_contents() returns FALSE
    if(strlen($json) > 0){
        //transform to array
        $array = json_decode($json, true);
    }
    else { $error = $error . 'model 280 does not contain any instance <br>'; }
}

$list_i_id = array();
foreach ($array['data'] as $inside_Array){
    array_push($list_i_id, $inside_Array['i_id']);
}



$all = array();
foreach($list_i_id as $i_id){
    $json = file_get_contents('http://api.app-arena.com/v1/instances/' . $i_id .'/config/fanpage_ids/value');
    if($json != FALSE){   //if V1 request succeed   -- file_get_contents() returns FALSE
        if(strlen($json) > 0){
            $array = explode(';',$json);
            $all = array_merge($all, $array);
        }
        else { $error = $error . 'the instance:'. $i_id. ' does not contain any fanpage id <br>'; }
    }
}

$num_fanpages = count($all);





//create array of all ids in DB that match the aa_inst_id  (database used to store many aa_inst_id )
$query1 = "SELECT fb_page_id FROM fanpage_basic_data";
$query2 = $db->query($query1);
$dbFansPagesIdsArray = $query2->fetchAll(PDO::FETCH_COLUMN);


// getting the array of new ids entered in aa for the app_inst_id   // these ids will be requested from fb and inserted in db
$differenceArray = array_diff($all, $dbFansPagesIdsArray);
$differenceArrayNumber = count($differenceArray);

// getting the array of existing ids in the database for the app_inst_id  // these ids will be requested from fb and updated in db
$intersectionArray = array_intersect($all, $dbFansPagesIdsArray );
$intersectionArrayNumber = count($intersectionArray);





if(!empty($differenceArray)){  //insert new rows into metric DB and into basic DB
    foreach($differenceArray as $id){
        //get string Object of fb
        $json = file_get_contents('https://graph.facebook.com/'.$id);

        if($json != FALSE){   //if facebook request succeed   -- file_get_contents() returns FALSE
            //transform to array
            $json_array = json_decode($json, true);

             //set name
             if(isset($json_array['name']) && $json_array['name'] != NULL){ $name = $json_array['name'];}
             else {$name = 'no name';}

             //set likes
             if(isset($json_array['likes']) && $json_array['likes'] != NULL){ $likes = $json_array['likes'];}
             else {$likes = 0;}

             //set talking_about_count
             if(isset($json_array['talking_about_count']) && $json_array['talking_about_count'] != NULL){ $talking_about_count = $json_array['talking_about_count'];    }
             else {$talking_about_count = 0;}

             //set description
             if(isset($json_array['description']) && $json_array['description'] != NULL){ $description = $json_array['description'];    }
             else {$description = 'no description';}

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
        else {$error = $error . 'request to facebook API did not succeed for fanpage id:' . $id . '<br>';}
    }
}






if(!empty($differenceArray)){
    $toUpdateNotTodayInserted = array_diff($dbFansPagesIdsArray, $differenceArray);  //this will exclude the same day inserted new rows
}
else {
    $toUpdateNotTodayInserted = $dbFansPagesIdsArray;
}

// insert rows in metric DB and update rows in basic DB
if(!empty($toUpdateNotTodayInserted)){
    foreach($toUpdateNotTodayInserted as $id){
        //get string Object of fb
        $json = file_get_contents('https://graph.facebook.com/'.$id);

        if($json != FALSE){   //if facebook request succeed   -- file_get_contents() returns FALSE
            //transform to array
            $json_array = json_decode($json, true);

            //set name
            if(isset($json_array['name']) && $json_array['name'] != NULL){ $name = $json_array['name'];}
            else {$name = 'no name';}

            //set likes
            if(isset($json_array['likes']) && $json_array['likes'] != NULL){ $likes = $json_array['likes'];}
            else {$likes = 0;}

            //set talking_about_count
            if(isset($json_array['talking_about_count']) && $json_array['talking_about_count'] != NULL){ $talking_about_count = $json_array['talking_about_count'];    }
            else {$talking_about_count = 0;}

            //set description
            if(isset($json_array['description']) && $json_array['description'] != NULL){ $description = $json_array['description'];    }
            else {$description = 'no description';}

            // update basic DB
            $query7 = "UPDATE fanpage_basic_data ".
                      "SET ".
                        "name = :name, " .
                        "description = :description " .
                        "WHERE fb_page_id = '$id'";
            $query8 = $db->prepare($query7);
            $query8->bindParam(':name', $name, PDO::PARAM_STR);
            $query8->bindParam(':description', $description, PDO::PARAM_STR);

            $query8->execute();


            //insert into metric DB
            $query9 = "INSERT INTO fanpage_metric_data " .
                      "SET " .
                        "date = :date, " .
                        "fb_page_id = :fb_page_id, " .
                        "likes = :likes, " .
                        "talking_about_count = :talking_about_count";
            $query10 = $db->prepare($query9);
            $query10->bindParam(':fb_page_id', $id, PDO::PARAM_INT);
            $query10->bindParam(':date', $today);
            $query10->bindParam(':likes', $likes, PDO::PARAM_INT);
            $query10->bindParam(':talking_about_count', $talking_about_count, PDO::PARAM_INT);
            $query10->execute();
        }
        else { $error = $error . 'request to facebook API did not succeed for fanpage id:' . $id . '<br>';}
    }
}










echo $error . '<br>';
echo 'Num Of Fanpages:' . $num_fanpages . '<br>';
echo '-------------------------------------------<br><br>';



