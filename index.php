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
    <?// var_dump($aa['config']); ?>
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
<!--    <link type="text/css" rel="stylesheet" href="--><?php //__pc($aa[ 'config' ][ 'design_template' ][ 'value' ], 'src')?><!--" />-->
    <link type="text/css" rel="stylesheet" href="css/main-layout-blue.css" />

</head>


<body class="<?=$classbody?>">

  <div class="customer_entry_container">
     <?php __p( 'customer_entry_msg' ); ?>
     <a href="#page/contactForm" id="customer_form_btn" class="btn btn-primary"><?php __p( 'customer_entry_btn' ); ?></a>
  </div>
  <img src="css/beta.png" id="beta">

  <div id="custom_header">
	   <div class="main_container">
	   	<img src="<?=$aa[ 'config' ][ 'page_logo' ][ 'value' ]?>" alt="Logo" class="left" />
	   	<div>
		   	<h1><?php __p( 'title' ); ?></h1>
			   <h2>SEO</h2>
			   <p>
			   	<?php echo (isset( $aa[ 'config' ][ 'custom_header' ][ 'value' ] ) ) ? $aa[ 'config' ][ 'custom_header' ][ 'value' ] : ''; ?>
			   </p>
	   	</div>
	   </div>
	</div>

 <div id="time_bar">
 	<div class="main_container">
      <ul class="nav nav-pills">
         <li class="typeOfTime active" id="30days"><a href="#"><?php __p( 'chooser_30_days' ); ?></a></li>
         <li class="typeOfTime" id="10weeks"><a href="#"><?php __p( 'chooser_10_weeks' ); ?></a></li>
         <li class="typeOfTime" id="6months"><a href="#"><?php __p( 'chooser_6_months' ); ?></a></li>
         <li class="typeOfTime" id="12months"><a href="#"><?php __p( 'chooser_12_months' ); ?></a></li>
     </ul>
     <?php __p( 'overall_time_chooser' ); ?>
 	</div>
 </div>

 	<div id="choose_bar">
 		<div class="main_container">
 			<button type="button" id="collapse-all" class="right btn btn-info btn-xs"><?php __p( 'collapse_all' ); ?></button>
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
               ob_start();
               __p( 'chooser_limit', $dropdown );
               $dropdown_output = ob_get_contents();
               ob_end_clean();
               echo str_replace( array( '<p>', '</p>' ), '', $dropdown_output );
           ?>
 			<div class="banks_search">
              <select multiple="" name="e9" id="e9" class="populate select2-offscreen" tabindex="-1">
              </select>
 				<?php __p( 'banks_to_rank' ); ?> &nbsp;
 			</div>
     </div>
 </div>

<div class="container">


    <!-------------------    title of the table ------------------------------------------------->
    <div class='list-group-item bold-italic-custom'>
        <div class="row show-grid">
            <div class="col-1 photos-head">
                <?php __p( 'fanpage_name' ); ?> &nbsp;
                <a href="#" class="icons fans-pages-names-desc"><i class="icon-arrow-down-white"></i></a>
                <a href="#" class="icons fans-pages-names-asc"><i class="icon-arrow-up-white"></i></a>
            </div>
            <div class="col-3 likes-head">
                <?php __p( 'likes' ); ?> &nbsp;
                <a href="#" class="icons likes-desc"><i class="icon-arrow-down-white"></i></a>
                <a href="#" class="icons likes-asc"><i class="icon-arrow-up-white"></i></a>
            </div>
            <div class="col-3 talks-about-head">
                <?php __p( 'talking_about' ); ?> &nbsp;
                <a href="#" class="icons talks-about-desc"><i class="icon-arrow-down-white"></i></a>
                <a href="#" class="icons talks-about-asc"><i class="icon-arrow-up-white"></i></a>
            </div>
            <div class="col-1 expand-head">
                <?php __p( 'expand' ); ?>
            </div>
        </div>
    </div>
    <div class='page-spinner'><i class='icon-spinner icon-spin icon-large'></i></div>



    <!-------------------    banks ------------------------------------------------->
    <div class="banks" id="content-wrapper">

    </div>

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

	    	jQuery('#max-rows').selectpicker();

	    	jQuery('#max-rows').change(
	    		function()
	    		{

		    		jQuery( this ).change();

	    		}
	    	);

    	}
    );

</script>
</body>
</html>
