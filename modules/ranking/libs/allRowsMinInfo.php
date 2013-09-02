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

$action = $_REQUEST['action'];
$module = $_REQUEST['module'];
$fb_page_id = $_REQUEST['fb_page_id'];



//set today
$today = date('Y-m-d', time());

$aa_inst_id = $aa['instance']['aa_inst_id'];
$fansPagesIdsAsStr = $aa['config']['fanpage_ids']['value'];
$aaFansPagesIdsAsArray = explode(';',$fansPagesIdsAsStr);




// to test http://localhost/fanpageranking0.2_backbone1/?aa_inst_id=5618&fb_page_id=163721403669672&action=allRowsMinInfo&module=ranking
// to test http://localhost/fanpageranking0.2_backbone1/modules/ranking/libs/allRowsMinInfo.php?aa_inst_id=5618&fb_page_id=163721403669672&action=allRowsMinInfo&module=ranking




//test
var_dump($today);
echo '<br><br>';
var_dump($aa_inst_id);
echo '<br><br>';
var_dump($aaFansPagesIdsAsArray);
echo '<br><br>';




//querying the min info for all rows
$outerArray = array();
foreach($aaFansPagesIdsAsArray as $id){
    $innerArray = array();

    //query fanpage_basic_data
    $query1 = "SELECT name, description FROM fanpage_basic_data
            WHERE fb_page_id = '$id'";
    $query2 = $db->query($query1);
    $arrayDescriptionName = $query2->fetchAll(PDO::FETCH_ASSOC);

    //query fanpage_metric_data
    $query3 = "SELECT likes, talking_about_count FROM fanpage_metric_data
                WHERE date = '$today' AND fb_page_id = '$id'";
    $query4 = $db->query($query3);
    $arrayTodayLikesTalkingAboutCount = $query4->fetchAll(PDO::FETCH_ASSOC);

    //create arrays
    array_push($innerArray, $id, $arrayDescriptionName[0]['name'], $arrayDescriptionName[0]['description'], $arrayTodayLikesTalkingAboutCount[0]['likes'], $arrayTodayLikesTalkingAboutCount[0]['talking_about_count']);
    //var_dump($innerArray);echo '<br><br>';
    array_push($outerArray, $innerArray);
    //var_dump($outerArray);echo '<br><br>';
}

//encode to json
$result = json_encode($outerArray);
echo $result;
