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
}

//database used to store many aa_inst_id

var_dump($aa);

$fansPagesIdsAsStr = $aa['config']['fanpage_ids']['value'];
var_dump($fansPagesIdsAsStr);


$fansPagesIdsAsStr = '163721403669672;106479362709892;212285335468828;160805495421;173099949403228;263416953165;332236356800801;241806709206264;168513559842620;329596519636;218266734578;153048968094202';
$fansPagesIdsAsArray = explode(';',$fansPagesIdsAsStr);
$fansPagesNumber = count($fansPagesIdsAsArray);



//detect new Ids added to aa  // so to INSERT in that case
$newIds = [];
$query1 = "SELECT fb_page_id FROM fanpage_metrics_data WHERE aa_inst_id = '$aa_inst_id' ";
$query2 = $db->query($query1);
$resultArray = $query2->fetchAll();
//var_dump($resultArray);

// getting the array of new ids entered in aa for the app_inst_id   // these ids will be requested from fb and inserted in db
//$diffArray = array_diff($fansPagesIdsAsArray, $resultArray);

// getting the array of existing ids in the database for the app_inst_id  // these ids will be requested from fb and updated in db
//$intersectionArray = array_intersect($fansPagesIdsAsArray, $resultArray );





for($i=0;$i<$fansPagesNumber;$i++){
    //get string Object of fb
    $content = file_get_contents('https://graph.facebook.com/'.$fansPagesIdsAsArray[$i]);

    if($content != FALSE){
        //transform to array
        $content_decoded_array = json_decode($content, true);

        if($content_decoded_array['name'] != NULL){ $name = $content_decoded_array['name'];    }
        else {$name = 'no name';}

        if($content_decoded_array['likes'] != NULL){ $likes = $content_decoded_array['likes'];    }
        else {$likes = 'no likes';}

        if($content_decoded_array['talking_about_count'] != NULL){ $talking_about_count = $content_decoded_array['talking_about_count'];    }
        else {$talking_about_count = 'no talking_about_count';}

        if($content_decoded_array['description'] != NULL){ $description = $content_decoded_array['description'];    }
        else {$description = 'no description';}

        //test
        var_dump($name);
        var_dump($likes);
        var_dump($talking_about_count);
        var_dump($description);
    }
    else {var_dump('request to facebook API did not succeed');}
}



?>




