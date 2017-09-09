<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/8/17
 * Time: 9:26 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "Inventory";
@mysqli_select_db($connection, $table);
/*events after button "Show" is clicked*/

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["submit_edit_inventory"]))
{
    /*defines*/
    $inv_itemID = mysqli_real_escape_string($connection, $_POST["inv_itemID"]);
    $inv_quantity = mysqli_real_escape_string($connection, $_POST["inv_quantity"]);
    $inv_purchased_price = mysqli_real_escape_string($connection, $_POST["inv_purchased_price"]);
    $inv_selling_price = mysqli_real_escape_string($connection, $_POST["inv_selling_price"]);
    $inv_total_cost = $inv_purchased_price * $inv_quantity;

    date_default_timezone_set('Australia/Melbourne');
    $date = date('Y-m-d');
    //echo $date;
    $inv_latest_update = $date;

    $inv_update_reason = mysqli_real_escape_string($connection, $_POST["inv_update_reason"]);
    $inv_username = "admin"; //Initialised, code to assign value for this variable will be updated later.

    $query = "INSERT INTO $table (itemID, quantity, purchased_price, selling_price, total_cost, latest_update, update_reason, username) VALUES ('$inv_itemID', '$inv_quantity', '$inv_purchased_price', '$inv_selling_price', '$inv_total_cost', '$inv_latest_update', '$inv_update_reason', '$inv_username')";
    $edit_inventory = mysqli_query($connection, $query);
}