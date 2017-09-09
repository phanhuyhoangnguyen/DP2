<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/8/17
 * Time: 9:50 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$record_table = "Records";
$inv_table = "Inventory";
@mysqli_select_db($connection, $record_table);
@mysqli_select_db($connection, $inv_table);
/*events after button "Show" is clicked*/
date_default_timezone_set('Australia/Melbourne');
$rec_itemID = mysqli_real_escape_string($connection, $_POST["rec_itemID"]);

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["submit_add_sale_record"]))
{

    //Get current selling price of the item
    $inv_query = "SELECT quantity, purchased_price, selling_price FROM Inventory WHERE itemID='$rec_itemID'";
    $inv_query_fetch = $connection->query($inv_query)->fetch_assoc();
    $selling_price = $inv_query_fetch["selling_price"];
    $purchased_price = $inv_query_fetch["purchased_price"];
    $inv_quantity = $inv_query_fetch["quantity"];

    /*defines*/
    $rec_date = date('Y-m-d');
    $inv_latest_update = $rec_date;
    $sold_quantity = mysqli_real_escape_string($connection, $_POST["rec_quantity"]);
    $rec_username = "admin"; //Initialised, code to assign value for this variable will be updated later.

    $revenue = $selling_price * $sold_quantity;
    $profit = $revenue - ($purchased_price * $sold_quantity);
    $new_quantity = $inv_quantity - $sold_quantity;

    $general_update_inv_table_query = "UPDATE $inv_table SET quantity='$new_quantity', latest_update='$rec_date', update_reason='new_order' WHERE itemID='$rec_itemID'";
    $general_update = mysqli_query($connection, $general_update_inv_table_query);

    $query = "INSERT INTO $record_table (itemID, date, sold_quantity, revenue, profit, username) VALUES ('$rec_itemID', '$rec_date', '$sold_quantity', '$revenue', '$profit', '$rec_username')";
    $add_record = mysqli_query($connection, $query);
}