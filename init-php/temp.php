<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/14/17
 * Time: 4:19 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$record_table = "Records";
$inv_table = "Inventory";
@mysqli_select_db($connection, $record_table);
@mysqli_select_db($connection, $inv_table);
date_default_timezone_set('Australia/Melbourne');
$rec_itemID = mysqli_real_escape_string($connection, $_POST["rec_itemID"]);
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["submit_add_sale_record"])) {
    if ($_SESSION["username"] == "") {
        echo "<p>You must login to edit the inventory.</p>";
    } else {
        /* Error message */

        $checkbox = array();

        foreach ($_SESSION["listing"] as $itm)
        {
            if (isset($_POST["cart_$itm"]))
            {
                $checkbox[] = "$itm";
            }
        }

        print_r($checkbox);
    }
}