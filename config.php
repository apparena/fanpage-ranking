<?php
/**
 * Setup your app-model access credentials here
 */
$aa_activated = true;
$aa_app_id = 280;
$aa_app_secret = "d91ad9e97695b1518800626eda0d7958";
$aa_default_locale = "en_US";

/**
 * Setup your database access data
 */
$db_activated = true;
$db_host      = "localhost";
$db_name      = "fanpageranking";
$db_user      = "root";
$db_pass      = "";
$db_option    = array(
    'port' => '3306',   // default port
    'type' => 'mysql',  // database driver
    'pdo' => array(     // default driver attributes
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    )
);

/*
$fb_app_id = "161596157315481";
$fb_app_secret = "cb11ea19d870420d6df37795cec44606";
*/

/* auth module config */
// client id for web applications, needed by the sign in button
$gplus_client_id = "990596349199.apps.googleusercontent.com";

// twitter oauth settings
$twitter_consumer_key = "2WnBPqfOf0vaGPJsMFG6fw";
$twitter_consumer_secret = "mygq0bS2LQfUn6jLiujZ8VWOqXCBKtxHtXgnGnrMc";

// this url will be called after the user accepts or declines the login
$twitter_callback_url = "https://www.app-arena.com/app/aa_template/dev/modules/auth/twitter_auth_callback.php";
