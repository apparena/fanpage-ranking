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
}

// ToDo[maXus]: create class to extract code - 01.08.13
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


// delete some important variables
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volksbanks fan page ranking App</title>

    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/ranking.css" />


    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="css/style-ie7.css">


    <script type="text/javascript" src="js/vendor/tinysort/jquery.tinysort.js"></script>


    <![endif]-->
    <?php if(__c('css_bootstrap', 'src') !== false): ?><link type="text/css" rel="stylesheet" href="<?php __pc('css_bootstrap', 'src')?>" /><?php ENDIF; ?>
    <?php if (__c('css_app', 'src') !== false): ?><link type="text/css" rel="stylesheet" href="<?php __pc('css_app', 'src')?>" /><?php ENDIF; ?>

</head>

<body class="<?=$classbody?>">
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Logo</a>

        <div class="nav-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#/page/demo">load Demomodule</a></li>
            </ul>
        </div>
    </div>
</div>
<div id="msg-container"></div>
<div id="fb-root"></div>




<div class="container">
    <h3>Ranking Volksbanks</h3>
    <h12 class="num-elements" style="font-weight:bold;"></h12> Banks ranked on : <h12 class="date" style="font-weight:bold;"></h12> at: <h12 class="time" style="font-weight:bold;"></h12>
    <br>
            <ul class="pager">
                <label>Display the Top / Bottom </label>
                <select>
                    <option value="all">all</option>
                    <option value=1>1</option>
                    <option value=2>2</option>
                    <option value=3>3</option>
                    <option value=4>4</option>
                    <option value=5>5</option>
                    <option value=5>10</option>
                    <option value=5>15</option>
                    <option value=5>20</option>
                    <option value=5>25</option>
                </select>
                <label> Banks</label>
                <li class="refresh-button"><a href="#">Refresh</a></li>
            </ul>




    <! -------------------    title of the table ------------------------------------------------->
    <div style= "font-weight:bold; font-style: italic; background-color:#D8D8D8">
        <div class="row show-grid">
            <div class="col-1">
                Rank &nbsp;
                <i class="icon-long-arrow-down icon-3 ranks-desc"></i>&nbsp;
                <i class="icon-long-arrow-up icon-3 ranks-asc"></i>
            </div>
            <div class="col-1">
                Photo &nbsp;
                <i class="icon-long-arrow-down icon-3 photos-desc"></i>&nbsp;
                <i class="icon-long-arrow-up icon-3 photos-asc"></i>
            </div>
            <div class="col-4">
                Fan page name &nbsp;
                <i class="icon-long-arrow-down icon-3 fans-pages-names-desc"></i>&nbsp;
                <i class="icon-long-arrow-up icon-3 fans-pages-names-asc"></i>
            </div>
            <div class="col-2">
                Likes &nbsp;
                <i class="icon-long-arrow-down icon-3 likes-desc"></i>&nbsp;
                <i class="icon-long-arrow-up icon-3 likes-asc"></i>
            </div>
            <div class="col-3">
                Talking about &nbsp;
                <i class="icon-long-arrow-down icon-3 talks-about-desc"></i>&nbsp;
                <i class="icon-long-arrow-up icon-3 talks-about-asc"></i>
            </div>
        </div>
    </div>



    <! -------------------    banks ------------------------------------------------->
    <div class="banks" id="content-wrapper">

    </div>

<br><br>

</div>


<?php
unset($aa['config']['css_bootstrap']);
unset($aa['config']['css_app']);
unset($aa['config']['tac']);

echo '<pre class="debug">';
print_r($aa);
echo '</pre>';
?>

<script>
    var aa = <?php echo json_encode($aaForJs); ?>; // copy aa as a global object to js
</script>
<script src="js/config.js"></script>
<script id="requirejs" data-main="js/main" data-devmode="1" src="js/vendor/requirejs/require.js"></script>
</body>
</html>