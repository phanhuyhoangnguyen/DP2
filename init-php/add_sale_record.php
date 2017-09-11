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
date_default_timezone_set('Australia/Melbourne');
$rec_itemID = mysqli_real_escape_string($connection, $_POST["rec_itemID"]);
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["submit_add_sale_record"]))
{
    if ($_SESSION["username"] == "")
    {
        echo "<p>You must login to edit the inventory.</p>";
    } else {
        /* Error message */
        $errMsg = "";

        /* validates item selection */
        if ($rec_itemID == "") {
            $errMsg .= "<p>You must select an item.</p>";
        }

        //Get current selling price of the item
        $inv_query = "SELECT quantity, purchased_price, selling_price FROM Inventory WHERE itemID='$rec_itemID'";
        $inv_query_fetch = $connection->query($inv_query)->fetch_assoc();
        $selling_price = $inv_query_fetch["selling_price"];
        $purchased_price = $inv_query_fetch["purchased_price"];
        $inv_quantity = $inv_query_fetch["quantity"];

        /*defines*/
        $rec_date = date('Y-m-d H:i:s');
        $inv_latest_update = $rec_date;

        $sold_quantity = mysqli_real_escape_string($connection, $_POST["rec_quantity"]);
        /* validates sold quantity */
        if ($sold_quantity == "") {
            $errMsg .= "<p>You must provide sold quantity amount of the item.</p>";
        } else if (!preg_match("/^[0-9]*$/", $sold_quantity)) {
            $errMsg .= "<p>You must enter a valid number for sold quantity.</p>";
        }

        $rec_username = $_SESSION["username"];

        $revenue = round(($selling_price * $sold_quantity), 2);
        $profit = round(($revenue - ($purchased_price * $sold_quantity)), 2);
        $new_quantity = $inv_quantity - $sold_quantity;

        if ($new_quantity < 0) {
            $errMsg .= "<p>Out of stock. Current item's quantity amount in stock is $inv_quantity. Please reduce your cart.</p>";
        }

        if ($errMsg != "") {
            echo $errMsg;
        } else {

            $general_update_inv_table_query = "UPDATE $inv_table SET quantity='$new_quantity', latest_update='$rec_date', update_reason='new_order' WHERE itemID='$rec_itemID'";
            $general_update = mysqli_query($connection, $general_update_inv_table_query);

            $query = "INSERT INTO $record_table (itemID, date, sold_quantity, revenue, profit, username) VALUES ('$rec_itemID', '$rec_date', '$sold_quantity', '$revenue', '$profit', '$rec_username')";
            $add_record = mysqli_query($connection, $query);
        }
    }
}