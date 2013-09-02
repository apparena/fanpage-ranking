<?php

try
{
    require_once("init.php");
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
} // erase when finish testing



$action = $_REQUEST['action'];
$module = $_REQUEST['module'];
$fb_page_id = $_REQUEST['fb_page_id'];

$today = date('Y-m-d', time());
$aa_inst_id = $aa['instance']['aa_inst_id'];


// to test http://localhost/fanpageranking0.2_backbone1/?aa_inst_id=5618&fb_page_id=163721403669672&action=maxIndividualRow&module=ranking



//$startingDate = date('Y-m-d', (time() - 120days));
//$finishingDate = date('Y-m-d', (time() - 1day));


$query1 = "SELECT date, likes, talking_about_count FROM fanpage_data
            WHERE aa_inst_id = '$aa_inst_id'
            AND date > '$today'
            AND fb_page_id = '$fb_page_id'";
$query2 = $db->query($query1);
$maxResults = $query2->fetchAll(PDO::FETCH_COLUMN);





$result = array();

if(!empty($aa_inst_id) && !empty($fb_page_id) && !empty($action) && !empty($data_size)) {
    if($data_size === 'max'){
        // define query statement
        $var1 = "SELECT * FROM fanpage_metrics_data";
        // put the statement into the database object
        $statement = $db->query($var1);
        // fetch all data from database
        $result = $statement->fetchAll();
    }
    if($data_size === 'min'){
        // define query statement
        $var1 = "SELECT * FROM fanpage_metrics_data";
        // put the statement into the database object
        $statement = $db->query($var1);
        // fetch all data from database
        $result = $statement->fetchAll();
    }
}


// turn the array into a json string
echo json_encode($result);