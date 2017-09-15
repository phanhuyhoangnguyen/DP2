<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/14/17
 * Time: 12:39 AM
 */

session_start();

//print_r($_SESSION["returned_item"]);

$selected = array();
$num_returned = array();
echo $selected_order = $_SESSION["selected_order"];

foreach ($_SESSION["returned_item"] as $itm) {
    if (isset($_POST["checked_$itm"])) {
        $selected[] = $itm;
    }
}

for ($i = 0; $i < count($selected); $i++) {
    $returned_quantity = $_POST["num_$selected[$i]"];
    $num_returned[] = $returned_quantity;
}

print_r($selected);
print_r($num_returned);
