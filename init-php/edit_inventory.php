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
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["submit_edit_inventory"]))
{
    if ($_SESSION["username"] == "")
    {
        echo "<p>You must login to edit the inventory.</p>";
    } else {
        /* Error message */
        $errMsg = "";

        $itemID = mysqli_real_escape_string($connection, $_POST["inv_itemID"]);

        if ($itemID == "") {
            $errMsg .= "<p>You must select an item to edit from Inventory.</p>";
        }

        $update_reason = mysqli_real_escape_string($connection, $_POST["inv_update_reason"]);

        if ($update_reason == "") {
            $errMsg .= "<p>You must select a reason for this Inventory update.";
        }

        $new_quantity = mysqli_real_escape_string($connection, $_POST["inv_quantity"]);
        $new_purchased_price = mysqli_real_escape_string($connection, $_POST["inv_purchased_price"]);
        $new_selling_price = mysqli_real_escape_string($connection, $_POST["inv_selling_price"]);

        date_default_timezone_set('Australia/Melbourne');
        $date = date('Y-m-d H:i:s');
        //echo $date;
        $latest_update_date = $date;

        $who_updated = $_SESSION["username"];

        $new_update_cost = round(($new_quantity * $new_purchased_price), 2);

        $previous_data_query = "SELECT quantity, purchased_price, selling_price, total_cost FROM $table WHERE itemID = '$itemID'";
        $previous_data_fetch = $connection->query($previous_data_query)->fetch_assoc();

        $previous_quantity = $previous_data_fetch["quantity"];
        $previous_total_cost = $previous_data_fetch["total_cost"];
        $previous_purchased_price = $previous_data_fetch["purchased_price"];
        $previous_selling_price = $previous_data_fetch["selling_price"];

        $new_total_update = $previous_total_cost + $new_update_cost;
        $new_quantity_available = $previous_quantity + (int)$new_quantity;

        if ($update_reason == "update_quantity") {
            if ($new_quantity == "") {
                $errMsg .= "<p>You must provide new quantity.</p>";
            } else if (!preg_match("/^[0-9]*$/", $new_quantity)) {
                $errMsg .= "<p>Invalid value for new quantity.</p>";
            }

            if ($new_purchased_price == "") {
                $errMsg .= "<p>You must provide purchased price of recent item's quantity update.</p>";
            } else if (!preg_match("/^[0-9.]*/", $new_purchased_price)) {
                $errMsg .= "<p>Invalid value for purchased price.</p>";
            }
        }

        if ($update_reason == "update_selling_price") {
            if ($new_selling_price == "") {
                $errMsg .= "<p>You must provide new selling price of the item.</p>";
            } else if (!preg_match("/^[0-9.]*/", $new_purchased_price)) {
                $errMsg .= "<p>Invalid value for new selling price.</p>";
            }
        }

        if ($update_reason == "update_both") {
            if (($new_quantity == "") || ($new_purchased_price == "") || ($new_selling_price == ""))
            {
                $errMsg .= "<p>You must provide all required information for this update reason.</p>";
            }
        }

        if ($errMsg != "") {
            echo $errMsg;
        } else {

            $update_query = "";
            if ($update_reason == "update_quantity") {
                $update_query = "UPDATE $table SET quantity='$new_quantity_available', purchased_price='$new_purchased_price', total_cost='$new_total_update', latest_update = '$latest_update_date', update_reason = '$update_reason', username = '$who_updated', previous_purchased_price = '$previous_purchased_price', stock_with_old_prices = '$previous_quantity' WHERE itemID = '$itemID'";
            } else if ($update_reason == "update_selling_price") {
                $update_query = "UPDATE $table SET selling_price='$new_selling_price', latest_update = '$latest_update_date', update_reason = '$update_reason', username = '$who_updated', previous_selling_price = '$previous_selling_price' WHERE itemID = '$itemID'";
            } else {
                $update_query = "UPDATE $table SET quantity='$new_quantity_available', purchased_price='$new_purchased_price', total_cost='$new_total_update', latest_update = '$latest_update_date', update_reason = '$update_reason', username = '$who_updated', previous_purchased_price = '$previous_purchased_price', stock_with_old_prices = '$previous_quantity', previous_selling_price = '$previous_selling_price' WHERE itemID = '$itemID'";
            }
            $update_quantity = mysqli_query($connection, $update_query);
            header("Location: manage.php");
        }
    }
    mysqli_close($connection);
}