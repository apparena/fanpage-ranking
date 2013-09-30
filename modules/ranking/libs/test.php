<?php



//set today
$today = date('Y-m-d', time());

$aa_inst_id = $aa['instance']['aa_inst_id'];
$fansPagesIdsAsStr = $aa['config']['fanpage_ids']['value'];
//$fansPagesIdsAsStr = '163721403669672;106479362709892;212285335468828;160805495421;173099949403228;263416953165;332236356800801;241806709206264;168513559842620;329596519636;218266734578;153048968094202';
$aaFansPagesIdsAsArray = explode(';',$fansPagesIdsAsStr);
$aaFansPagesIdsAsArray = array_unique($aaFansPagesIdsAsArray); //remove duplicate value


//test
//var_dump($today);echo '<br><br>';
//var_dump($aa_inst_id);echo '<br><br>';
//var_dump($aaFansPagesIdsAsArray);echo '<br><br>';




//querying the min info for all rows

//outer array to hold inner arrays containing each bank information


$keys = array();
$items = array();
$i = 0;

foreach($aaFansPagesIdsAsArray as $id){

    //query fanpage_basic_data
    $query1 = "SELECT fb_page_id, name, description FROM fanpage_basic_data
            WHERE fb_page_id = '$id'";
    $query2 = $db->query($query1);
    $arrayIdDescriptionName = $query2->fetchAll(PDO::FETCH_ASSOC);
    $arrayIdDescriptionName = array_shift($arrayIdDescriptionName);
    //var_dump($arrayIdDescriptionName);echo '<br><br>';

    //query fanpage_metric_data
    $query3 = "SELECT likes, talking_about_count FROM fanpage_metric_data
                WHERE fb_page_id = '$id'
                ORDER BY date DESC
                LIMIT 1";
    $query4 = $db->query($query3);
    $arrayTodayLikesTalkingAboutCount = $query4->fetchAll(PDO::FETCH_ASSOC);
    $arrayTodayLikesTalkingAboutCount = array_shift($arrayTodayLikesTalkingAboutCount);
    //var_dump($arrayTodayLikesTalkingAboutCount);echo '<br><br>';

    $fields = array_merge($arrayIdDescriptionName, $arrayTodayLikesTalkingAboutCount);
    //var_dump($fields);echo '<br><br>';

    array_push($items, $fields);
    array_push($keys, ('item'.$i));

    $i++;
}

$outerArray = array_combine($keys, $items);
//var_dump($outerArray);echo '<br><br>';




// return[]  is already created in line 53 of ajax.php
$return['message'] = $outerArray;              // look line 53  ajax.php
//$return['message'] = $outerArray;      // look line 53  ajax.php // i can put in description an array or a json,  both should normally work because in ajax.php line   86   we use the json_encode()  !!
$return['status'] = 'success';           // look line 53  ajax.php