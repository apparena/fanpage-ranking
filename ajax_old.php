<?php
 /**
 * ajax.php
 *
 * handle all requests from "ajax/restfull"
 */

define('DS', DIRECTORY_SEPARATOR);
//define('ROOT', dirname(__FILE__));

/*
echo '<pre>';
print_r($_GET);
echo '</pre>';

echo '<pre>';
print_r($_POST);
echo '</pre>';

echo '<pre>';
print_r($_SERVER['REQUEST_METHOD']);
echo '</pre>';

echo '<pre>';
print_r($_SERVER['REQUEST_URI']);
echo '</pre>';
*/

if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
{
    // Request has not been made by Ajax.
 #   die('Direct Access is not allowed.');
}

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

try
{
    $path = ROOT_PATH;

    if(!empty($_REQUEST['module']))
    {
        $path .= DS . 'modules' . DS . $_REQUEST['module'] . DS . 'libs';
    }

    if(empty($_REQUEST['action']))
    {
        throw new Exception('action is not defined in call');
    }

    $path .= DS . $_REQUEST['action'] . '.php';

    if (!file_exists($path))
    {
        throw new Exception($path . ' not exist');
    }
    include_once($path);
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