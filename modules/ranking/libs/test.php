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
var_dump($today);echo '<br><br>';
var_dump($aa_inst_id);echo '<br><br>';
var_dump($aaFansPagesIdsAsArray);echo '<br><br>';




//querying the min info for all rows

//outer array to hold inner arrays containing each bank information

$keys = array();
$values = array();


$i = 0;
foreach($aaFansPagesIdsAsArray as $id){


    echo 'lina karam';
    echo 'sandra karam';

}

//var_dump($values);echo '<br><br>';echo '<br><br>';
//var_dump($keys);echo '<br><br>';echo '<br><br>';


echo 'bernard';