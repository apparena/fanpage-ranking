<?php



//get posted data from javascript
$type_of_time = $_REQUEST['typeOfTime'];
$fb_page_id = $_REQUEST['id'];
//$type_of_time = 'months';
//$fb_page_id = '163721403669672';  // must be string




// set today
$today = date('Y-m-d', time());


switch ($type_of_time) {
    //query all last 30 days before today, including today
    case '30days':
        $where_time = "date <= '$today' AND date > DATE_ADD('$today', INTERVAL -30 DAY)";
        break;
    //query 10 days same day as today in each week, during the last 10 weeks
    case '10weeks':
        $where_time = "date = '$today' ";
        for($i=1;$i<11;$i++){
            $where_time = $where_time . " OR date = DATE_ADD('$today', INTERVAL - '$i' WEEK)";
        }
        break;
    //query 24 days same day as today, periodically each week, since now until 6 months ago
    case '6months':
        $where_time = "date = '$today' ";
        for($i=1;$i<25;$i++){
            $where_time = $where_time . " OR date = DATE_ADD('$today', INTERVAL - '$i' WEEK)";
        }
        break;
    //query 12 days same day as today, in each month, during the last 12 months
    case '12months':
        $where_time = "date = '$today' ";
        for($i=1;$i<13;$i++){
            $where_time = $where_time . " OR date = DATE_ADD('$today', INTERVAL - '$i' MONTH)";
        }
        break;

}


$query1 = "SELECT date, likes, talking_about_count FROM fanpage_metric_data " .
          "WHERE fb_page_id = '$fb_page_id' ".
          "AND ".
          "$where_time" .
          " ORDER BY date ASC" ;
$query2 = $db->query($query1);
$maxResults = $query2->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($maxResults);

//foreach ($maxResults as $key => $value){
    //var_dump($maxResults[$key]);echo '<br>';
//}




// return[]  is already created in line 53 of ajax.php
$return['message'] = $json;              // look line 53  ajax.php
$return['status'] = 'success';           // look line 53  ajax.php