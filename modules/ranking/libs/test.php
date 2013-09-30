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
$values = array();

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

    $all = array_merge($arrayIdDescriptionName, $arrayTodayLikesTalkingAboutCount);
    var_dump($all);echo '<br><br>';
}







echo 'lina karam'; echo'<br>';
echo 'sandra karam';echo'<br>';
echo 'bernard';