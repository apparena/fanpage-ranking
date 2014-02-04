<?php
define("ZDAPIKEY", "2OOS7IpRN78M46uCazT1GNkeFGbU8sopaCY3qQjJ");
define("ZDUSER", "t.storch@iconsultants.eu");
define("ZDURL", "https://apparena.zendesk.com/api/v2");
define("CUSTOM", true);

function curlWrap($url, $json)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
    curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);
    $decoded = json_decode($output);
    return $decoded;
}
$arr = array();

foreach($_POST as $key => $value){
    if(preg_match('/^z_/i',$key)){
        $arr[strip_tags($key)] = strip_tags($value);
    }
}
if (isset($arr['z_description']) && $arr['z_description'] == "")
{
    $arr['z_description'] = "Keine Beschreibung angegeben";
}
$ticket = array('ticket' => array('subject' => $arr['z_subject'], 'comment' => array("value"=>$arr['z_description'] ), 'requester' => array('name' => $arr['z_name'], 'email' => $arr['z_requester'])));

if (array_key_exists('z_priority',$arr) && $arr['z_priority'] != "") {
    $ticket['ticket']['priority'] =  $arr['z_priority'];
}
if (array_key_exists('z_type',$arr) && $arr['z_type'] != "") {
    $ticket['ticket']['type'] =  $arr['z_type'];
}

if(CUSTOM){
    foreach($_POST as $key => $value){
        if(preg_match('/^c_/i',$key)){
            $id = str_replace('c_', '', strip_tags($key));
            $value = strip_tags($value);
            $cfield=array('id'=>$id, 'value'=>$value);
            $ticket['ticket']['custom_fields'][]=$cfield;
        }
    }
}
$ticket = json_encode($ticket);

$return = curlWrap("/tickets.json", $ticket);

if (array_key_exists('returnURL',$_POST) && $_POST['returnURL'] != "") {
    $returnURL =  $_POST['returnURL'];
?>
    <script type="text/javascript">
        <!--
        location.href = '<?=$returnURL?>';
        //-->
    </script>
<?}?>
