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

// to test    http://localhost/fanpageranking0.2_backbone1/modules/ranking/libs/updateDB_V1.php?aa_inst_id=5618














/* Now sebastian ask me to do this update file for all fan page ids of all instance ids, that's why i should use
the new API by doing ajax requests.
1st ajax request to get all inst ids of the model 280:
http://api.app-arena.com/v1/instances.json?m_id=280
2nd ajax request to be looped on the list of instance ids, in order to obtain th list of fanpage ids
http://api.app-arena.com/v1/instances/5618/config/fanpage_ids/value
*/





/*  volksbanks  new list   >> updated in instance 5471
122291887866667;147569921929474;237186913054562;222427437841362;156042634458231;567554803262901;193949100761265;149264651805333;573253042686156;165744103490537;436153619738331;329949600431238;108351005868113;106019524909;151057418271409;363426510774;168453103191750;167437763289606;103409483034598;678461585514059;598258503540739;431178773593780;132096816830271;136599336411918;188309382830;126094167459962;
*/
/*  sparkasse new list   >> to update later in instance 5618
121187937953676;131127266969760;153040184763732;154803498004803;164521426971046;493060504117327;berlinersparkasse;foerdesparkasse;FrankfurterSparkasse;haspa;KasselerSparkasse;kreissparkasse.gotha;kreissparkasseahrweiler;KreissparkasseBoeblingen;kreissparkassediepholz;KreissparkasseKoeln;KreisSparkasseNortheim;KreissparkassePeine;kreissparkasseravensburg;KreissparkasseStade;KreissparkasseWeilburg;ksk.altenkirchen;ksk.bautzen;ksk.hoechstadt;ksk.soltau;KSK.Walsrode;KSKGeschaeftsstelleGrossGerauMarktplatz;kskgg;ksklb;kskmayen;kskmse;kskostalb;kskratzeburg;kskreutlingen;kskrw;kskse;kskvulkaneifel;kskwn;Landessparkasse.zu.Oldenburg;meineKSK;muensterlandgiro;OstsaechsischeSparkasseDresden;s.kukc;sparkasse;Sparkasse.Berchtesgadener.Land;sparkasse.bochum;Sparkasse.Dachau;sparkasse.erlangen;sparkasse.garmisch;sparkasse.guenzburgkrumbach;sparkasse.herford;sparkasse.niederbayern.mitte;sparkasse.nordhorn;sparkasse.nuernberg;sparkasse.re;sparkasse.RosenheimBadAibling;Sparkasse.Saalfeld;sparkasse.schongau;sparkasse.schweinfurt;Sparkasse.Solingen.Gut;sparkasse.suedholstein;sparkasse.suew;Sparkasse.Swp;sparkasse.tauberfranken;sparkasse.vogtland;sparkasse.witten;SparkasseAachen;SparkasseBielefeld;SparkasseBludenz;SparkasseBremen;SparkasseDarmstadt;sparkassedortmund;SparkasseEssen;sparkassegoettingen;sparkassehanau;SparkasseHeidelberg;sparkassehochfranken;sparkasseholstein;SparkasseIngolstadt;sparkassekoelnbonn;sparkassemiltenbergobernburg;SparkasseMittelthueringen;SparkasseMSLO;SparkasseNeuss;SparkasseOberhessen;sparkasseoffenburg;sparkassepassau;sparkassepfcw;sparkasseregensburg;SparkasseSchaumburg;sparkassesha;sparkassetrier;sparkassevorderpfalz;spk.bamberg;SpkAS;spkaschaffenburg;spkcham;spkdob;SpkWM;sskbo;sskbocholt;sskduesseldorf.de;SSKRemscheid;Stadtsparkasse.Muenchen;steiermaerkische;TaunusSparkasse
*/


$m_id = 280;

$json = file_get_contents('http://api.app-arena.com/v1/instances.json?m_id='.$m_id);
if($json != FALSE){   //if V1 request succeed   -- file_get_contents() returns FALSE
    if(strlen($json) > 0){
        var_dump('this model contains some instance ids');echo '<br><br><br><br>';
        //transform to array
        $array = json_decode($json, true);
        var_dump($array); echo '<br><br><br><br>';
    }
    else {
        var_dump('model does not contain any instance');echo '<br><br><br><br>';
    }
}

$list_i_id = array();
foreach ($array['data'] as $inside_Array){
    array_push($list_i_id, $inside_Array['i_id']);
}
var_dump($list_i_id); echo '<br><br><br><br>';



$all = array();
foreach($list_i_id as $i_id){
    $json = file_get_contents('http://api.app-arena.com/v1/instances/' . $i_id .'/config/fanpage_ids/value');
    if($json != FALSE){   //if V1 request succeed   -- file_get_contents() returns FALSE
        if(strlen($json) > 0){
            var_dump($json); echo '<br><br><br><br>';
            var_dump('this instance contains some fanpage ids'); echo '<br><br><br><br>';
            $array = explode(';',$json);
            $all = array_merge($all, $array);
            var_dump($array); echo '<br><br><br><br>';
        }
        else {
            var_dump('this instance does not contain any fanpage id');echo '<br><br><br><br>';
        }
    }
}
var_dump($all); echo '<br><br><br><br>';
var_dump(count($all)); echo '<br><br><br><br>';




// set today
$today = date('Y-m-d', time());




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


//test
var_dump($today);
echo '<br><br>';
var_dump($aa_inst_id);
echo '<br><br>';
var_dump($all);
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




