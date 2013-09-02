<html>
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

echo "hello marcus";

echo '<pre class="debug">';
print_r($aa);
echo '</pre>';

?>
</html>