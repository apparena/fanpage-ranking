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
    <meta name="viewport" content="width=1170, maximum-scale=1.0" />
    <title>Volksbanks fan page ranking App</title>

    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/ranking.css" />


    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="css/style-ie7.css">


    <script type="text/javascript" src="js/vendor/tinysort/jquery.tinysort.js"></script>


    <![endif]-->
    <?php if(__c('css_bootstrap', 'src') !== false): ?><link type="text/css" rel="stylesheet" href="<?php __pc('css_bootstrap', 'src')?>" /><?php ENDIF; ?>
    <?php if (__c('css_app', 'src') !== false): ?><link type="text/css" rel="stylesheet" href="<?php __pc('css_app', 'src')?>" /><?php ENDIF; ?>

    <link type="text/css" rel="stylesheet" href="css/main-layout.css" />

</head>


<body class="<?=$classbody?>">

  <div class="customer_entry_container">
     <?php __p( 'customer_entry_msg' ); ?>
     <a href="#page/contactForm" id="customer_form_btn" class="btn btn-primary"><?php __p( 'customer_entry_btn' ); ?></a>
  </div>

  <div id="custom_header">
	   <div class="main_container">
	   	<img src="img/sparkasse_logo.png" alt="Sparkasse" class="left" />
	   	<div>
		   	<h1>RANKING SPARKASSE BANKS</h1>
			   <h2>SEO</h2>
			   <p>
			   	Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,
			   </p>
	   	</div>
	   </div>
	</div>

<?php
/*
<div id="custom_header">
    <?php echo (isset( $aa[ 'config' ][ 'custom_header' ][ 'value' ] ) ) ? $aa[ 'config' ][ 'custom_header' ][ 'value' ] : ''; ?>
</div>

<div class="navbar navbar-inverse navbar-fixed-top hide">
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
*/
?>

<div class="container">

<br>
<div class='list-group-item bold'>
    <div class="alert">
        <div>
            <h3><?php __p( 'title' ); ?></h3>
            <?php __p( 'overall_stats', '<h12 class=\"num-elements\"></h12>', '<h12 class=\"date\"></h12>', '<h12 class=\"time\"></h12>' ); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <p><?php __p( 'overall_time_chooser' ); ?></p>
                    <ul class="nav nav-pills">
                        <li class="typeOfTime active" id="30days"><a href="#"><?php __p( 'chooser_30_days' ); ?></a></li>
                        <li class="typeOfTime" id="10weeks"><a href="#"><?php __p( 'chooser_10_weeks' ); ?></a></li>
                        <li class="typeOfTime" id="6months"><a href="#"><?php __p( 'chooser_6_months' ); ?></a></li>
                        <li class="typeOfTime" id="12months"><a href="#"><?php __p( 'chooser_12_months' ); ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="collapsed" id="spinner-typeOfTime"><i class='icon-spinner icon-spin icon-large'></i></div>
        </div>

        <div class="col-6">

            <div class="row">
                <div class="col-8 top-bottom" class="pull-left">
                    <p class="pull-left">
                    <?php
                        $dropdown = '&nbsp;</p>
                                <select id=\"max-rows\">
                                    <option value=\"all\">' . __t( 'all' ) . '</option>
                                    <option value=\"1\">1</option>
                                    <option value=\"2\">2</option>
                                    <option value=\"3\">3</option>
                                    <option value=\"4\">4</option>
                                    <option value=\"5\">5</option>
                                    <option value=\"10\">10</option>
                                    <option value=\"15\">15</option>
                                    <option value=\"20\">20</option>
                                    <option value=\"25\">25</option>
                                </select>
                            <p>&nbsp;';
                        __p( 'chooser_limit', $dropdown );
                    ?>
                    </p>
                </div>

                <div class="col-4">
                    <button type="button" id="collapse-all" class="btn btn-info btn-xs"><?php __p( 'collapse_all' ); ?></button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12">
                    <p><?php __p( 'banks_to_rank' ); ?></p>
                        <div class="select2-container select2-container-multi populate" id="s2id_e9">
                            <ul class="select2-choices">
                                <li class="select2-search-field">
                                <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" id="s2id_autogen1" tabindex="-1">
                                </li>
                            </ul>
                        </div>
                         <select multiple="" name="e9" id="e9" class="populate select2-offscreen" tabindex="-1">
                         </select>
                </div>
            </div>

        </div>
    </div>







</div>



<br>



    <!-------------------    title of the table ------------------------------------------------->
    <div class='list-group-item bold-italic-custom'>
        <div class="row show-grid">
            <div class="col-1">
                <!-- <?php __p( 'image' ); ?> -->
            </div>
            <div class="col-4">
                <?php __p( 'fanpage_name' ); ?> &nbsp;
                <a href="#" class="icons fans-pages-names-desc"><i class="icon-arrow-down-white"></i></a>
                <a href="#" class="icons fans-pages-names-asc"><i class="icon-arrow-up-white"></i></a>
            </div>
            <div class="col-3">
                <?php __p( 'likes' ); ?> &nbsp;
                <a href="#" class="icons likes-desc"><i class="icon-arrow-down-white"></i></a>
                <a href="#" class="icons likes-asc"><i class="icon-arrow-up-white"></i></a>
            </div>
            <div class="col-3">
                <?php __p( 'talking_about' ); ?> &nbsp;
                <a href="#" class="icons talks-about-desc"><i class="icon-arrow-down-white"></i></a>
                <a href="#" class="icons talks-about-asc"><i class="icon-arrow-up-white"></i></a>
            </div>
            <div class="col-1">
                <?php __p( 'expand' ); ?>
            </div>
        </div>
    </div>
    <div class='page-spinner'><i class='icon-spinner icon-spin icon-large'></i></div>



    <!-------------------    banks ------------------------------------------------->
    <div class="banks" id="content-wrapper">

    </div>

<br><br>

</div>

<?php
/*
<div id="custom_footer">
    < ?php echo (isset( $aa[ 'config' ][ 'custom_footer' ][ 'value' ] ) ) ? $aa[ 'config' ][ 'custom_footer' ][ 'value' ] : ''; ? >
</div>
*/
?>
<div id="custom_footer">
 	<div class="main_container">
	    <div class="left">made with love in Cologne © <?php echo date('Y') ?> <a href="http://app-arena.com/" target="_blank" title="App-Arena.com">App-Arena.com</a></div>
	    <div class="right"><a href="#" title="Impressum">Impressum</a> | <a href="#" title="AGB">AGB</a></div>
 	</div>
</div>

<div id="form-wrapper" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

</div>

<?php
//unset($aa['config']['css_bootstrap']);
//unset($aa['config']['css_app']);
//unset($aa['config']['tac']);

//echo '<pre class="debug">';
//print_r($aa);
//echo '</pre>';
?>

<script>
    var aa = <?php echo json_encode($aaForJs); ?>; // copy aa as a global object to js
</script>
<script src="js/vendor/jquery/jquery.js"></script>
<script src="js/vendor/bootstrap-assets/js/select.min.js"></script>
<script src="js/config.js"></script>
<script id="requirejs" data-main="js/main" data-devmode="1" src="js/vendor/requirejs/require.js"></script>
<script>

    jQuery( 'body' ).ready(
    	function()
    	{

	    	jQuery('select#max-rows').selectpicker();

    	}
    );

</script>
</body>
</html>
