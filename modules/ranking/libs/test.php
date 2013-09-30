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



foreach($aaFansPagesIdsAsArray as $id){

    $str = "SELECT fanpage_basic_data.fb_page_id, fanpage_basic_data.name, fanpage_basic_data.description, " .
             "fanpage_metric_data.likes, fanpage_metric_data.talking_about_count " .
             "FROM fanpage_basic_data INNER JOIN fanpage_metric_data " .
             "WHERE fanpage_basic_data.fb_page_id = '$id' AND fanpage_metric_data.fb_page_id = '$id' " .
             "ORDER BY fanpage_metric_data.date DESC LIMIT 1 " .
             "ON fanpage_basic_data.fb_page_id = fanpage_metric_data.fb_page_id";
    $query = $db->query($str);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    var_dump($array);echo '<br><br>';
}






echo 'lina karam'; echo'<br>';
echo 'sandra karam';echo'<br>';
echo 'bernard';