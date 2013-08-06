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

// create door classes from given date variables
$current_date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
for($counter = 1; $counter <= __c('door_amount'); $counter++)
{
    // ToDo[maXus]: currently faked!!!! - 01.08.13
    if(!empty($aa['config']['door_'. $counter .'_validity_period_start']['value']))
    {
        $start_date = new DateTime(__c('door_' . $counter . '_validity_period_start'), new DateTimeZone('Europe/Berlin'));
        $end_date = new DateTime(__c('door_' . $counter . '_validity_period_end'), new DateTimeZone('Europe/Berlin'));
    }
    else
    {
        #$plus_one_day = '+' . $counter . ' days';
        #$plus_two_day = '+' . ($counter + 1) . ' days';

        $start_date = new DateTime('2013-8-' . $counter, new DateTimeZone('Europe/Berlin'));
        //$start_date->modify($plus_one_day);

        $end_date   = new DateTime('2013-8-' . $counter, new DateTimeZone('Europe/Berlin'));
        //$end_date->modify($plus_two_day);
        $end_date->modify('+1 days');
    }

    $door_class = 'door-past';
    if($end_date > $current_date)
    {
        $door_class = 'door-post';
    }
    if($start_date == $current_date || ($start_date <= $current_date && $end_date >= $current_date))
    {
        $door_class = 'door-active';
    }
    $aaForJs['config']['door_' . $counter . '_class']['value'] = $door_class;
    //pr($counter . ' - ' . $start_date->format("d-m-Y") . ' - ' . $end_date->format("d-m-Y") . ' - ' . $class);
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
    <title>Adventskalender App</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="css/style-ie7.css">
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
                <li><a href="#" data-toggle="modal" data-target="#fangateModal">Open fangate</a></li>
                <li><a href="#/page/demo">Demomodul laden</a></li>
            </ul>
        </div>
    </div>
</div>
<div id="msg-container"></div>
<div id="fb-root"></div>

<div class="container">
    <header>
        <?php __pc('header_custom')?>
    </header>
    <div id="content">
        <figure id="page-background">
            <?php if ($aa['env']['device']['type'] === 'desktop' || $aa['env']['device']['type'] === 'tablet'){ ?>
                <img src="<?php __pc('overview_background_desktop'); ?>" title="" alt="background" />
            <?php } else{ ?>
                <img src="<?php __pc('overview_background_mobile'); ?>" title="" alt="background" />
            <?php } ?>
        </figure>
        <div id="content-wrapper" class="clearfix">
            <p>loading app</p>
        </div>
    </div>
    <footer>
        <?php __pc('footer_custom') ?>
    </footer>
</div>

<?php
unset($aa['config']['css_bootstrap']);
unset($aa['config']['css_app']);
unset($aa['config']['tac']);

echo '<pre class="debug hide">';
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