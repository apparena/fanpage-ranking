<?php
try
{
    require_once("../init.php");
}
catch (Exception $e)
{
    echo '<pre>';
    print_r($e->getMessage());
    echo '</pre>';
    echo '<pre>';
    print_r($e->getTrace());
    echo '</pre>';
}

/*
 * prepare the aa object for js
 */
$aaForJs = array(
    //"locale"   => $aa['locale'][$aa_locale_current],
    "locale"   => $aa['locale'],
    "config"   => $aa['config'],
    "instance" => $aa['instance'],
    "fb"       => false,
    "app_data" => false
);
if (isset($aa['fb']))
{
    $aaForJs["fb"] = $aa['fb'];
}

if (!empty($_GET['app_data']))
{
    $aaForJs["app_data"] = $_GET['app_data'];
}
else
{
    if (!empty($fb_signed_request['app_data']))
    {
        $aaForJs["app_data"] = $fb_signed_request['app_data'];
    }
}

if (isset($aaForJs['instance']['aa_app_secret']))
{
    unset($aaForJs['instance']['aa_app_secret']);
}
if (isset($aaForJs['instance']['fb_app_secret']))
{
    unset($aaForJs['instance']['fb_app_secret']);
}
?>

<!doctype html>
<html lang="de-DE">
<head>
    <meta charset="UTF-8">
    <title>QUnit Test Suite</title>
    <link rel="stylesheet" type="text/css" href="//code.jquery.com/qunit/qunit-git.css" />
</head>
<body>

<div id="qunit"></div>
<div id="qunit-fixture"></div>
<div id="content-wrapper"></div>

<?php
unset($aa['config']['css_bootstrap']);
unset($aa['config']['css_app']);
unset($aa['config']['tac']);
?>

<script>
    aa = <?php echo json_encode($aaForJs); ?>; // copy aa as a global object to js
</script>

<script src="../js/config.js"></script>
<script id="requirejs" data-main="unittestsmain" src="../js/vendor/requirejs/require.js"></script>

<!--script type="text/javascript" src="//code.jquery.com/qunit/qunit-git.js"></script-->
<!--script type="text/javascript" src="//sinonjs.org/releases/sinon-1.7.3.js"></script-->
<!--script type="text/javascript" src="//sinonjs.org/releases/sinon-ie-1.7.3.js"></script-->
<!--script type="text/javascript" src="//sinonjs.org/releases/sinon-qunit-1.0.0.js"></script-->
<!--script id="requirejs" data-main="../js/main" data-devmode="1" src="../js/vendor/requirejs/require.js"></script-->
<!--script type="text/javascript" src="units/myTests.js"></script-->
<!--script type="text/javascript" src="units/myTests2.js"></script-->
</body>
</html>