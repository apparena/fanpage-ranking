<?php
// Create a support ticket in Zoho with a curl POST request

// Replace "_" fields with " ".
foreach ($_POST as $k => $v) {
    unset ($_POST[$k]);
    // Create new key
    $keys_to_replace = array("Due_Date", "Due_Datehour", "Due_Dateminute", "Due_Dateampm","First_Name","Contact_Name");
    if (in_array($k, $keys_to_replace)) {
        $new_key =  str_replace("_", " ", $k);
    } else {
        $new_key = $k;
    }
    $_POST[$new_key] = $v;
}

// All form POST data has to be sent to this file to make the request work
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://support.zoho.com/support/WebToCase");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
var_dump($_POST);
echo "==========================================";
var_dump($output);
echo "==========================================";
var_dump($info);
echo "==========================================";
?>