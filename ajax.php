<?php
 /**
 * ajax.php
 *
 * handle all requests from "ajax/restfull"
 */
ini_set('display_errors', 1);
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
    die('Direct Access is not allowed.');
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
    // create default return statement
    $return = array(
        'code' => 0,
         'status' => 'error',
         'message' => ''
    );
    $path = ROOT_PATH;

    if(!empty($_POST['module']))
    {
        $path .= DS . 'modules' . DS . $_POST['module'] . DS . 'libs';
    }

    if(empty($_POST['action']))
    {
        throw new Exception('action is not defined in call');
    }

    $path .= DS . $_POST['action'] . '.php';

    if (!file_exists($path))
    {
        throw new Exception($path . ' not exist');
    }
    include_once($path);

    // set content type to json file
    header('Content-Type: application/json');
    // disable browser caching
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
    // attache json file as download
    header("Content-Disposition:attachment;filename='" . $_POST['action'] . ".json'");

    echo json_encode($return);
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