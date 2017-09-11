<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/11/17
 * Time: 7:16 PM
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
} else if (isset($_POST["submit_add_item_to_inventory"]))
{
    if ($_SESSION["username"] == "")
    {
        echo "<p>You must login to edit the inventory.</p>";
    } else {
        /* Error message */
        $errMsg = "";

        /*defines*/
        $inv_itemID = mysqli_real_escape_string($connection, $_POST["inv_itemID"]);

        /* validates item selection */
        if ($inv_itemID == "") {
            $errMsg .= "<p>You must select an item to add.</p>";
        }

        $inv_quantity = mysqli_real_escape_string($connection, $_POST["inv_quantity"]);

        /* validates item quantity */
        if ($inv_quantity == "") {
            $errMsg .= "<p>You must provide quantity amount of the item.</p>";
        } else if (!preg_match("/^[0-9]*$/", $inv_quantity)) {
            $errMsg .= "<p>You must enter a valid number for item quantity.</p>";
        }

        $inv_purchased_price = mysqli_real_escape_string($connection, $_POST["inv_purchased_price"]);

        /* validates item purchased price */
        if ($inv_purchased_price == "") {
            $errMsg .= "<p>You must provide purchased price of the item.</p>";
        } else if (!preg_match("/^[0-9.]*$/", $inv_purchased_price)) {
            $errMsg .= "<p>Only dot and digits allowed for purchased price.</p>";
        } else if ($inv_purchased_price <= 0) {
            $errMsg .= "<p>Purchased price must be larger than 0.</p>";
        }

        $inv_selling_price = mysqli_real_escape_string($connection, $_POST["inv_selling_price"]);

        /* validates item selling price */
        if ($inv_selling_price == "") {
            $errMsg .= "<p>You must provide selling price of the item.</p>";
        } else if (!preg_match("/^[0-9.]*$/", $inv_selling_price)) {
            $errMsg .= "<p>Only dot and digits allowed for selling price.</p>";
        } else if ($inv_selling_price <= 0) {
            $errMsg .= "<p>Selling price must be larger than 0.</p>";
        } else if ($inv_selling_price <= $inv_purchased_price) {
            $errMsg .= "<p>Selling price must be larger purchased price.</p>";
        }

        $inv_total_cost = round(($inv_purchased_price * $inv_quantity), 2);

        date_default_timezone_set('Australia/Melbourne');
        $date = date('Y-m-d H:i:s');
        //echo $date;
        $inv_latest_update = $date;

        $inv_update_reason = mysqli_real_escape_string($connection, $_POST["inv_update_reason"]);

        $inv_username = $_SESSION["username"];

        if ($errMsg != "") {
            echo $errMsg;
        } else {
            $query = "INSERT INTO $table (itemID, quantity, purchased_price, selling_price, total_cost, latest_update, update_reason, username) VALUES ('$inv_itemID', '$inv_quantity', '$inv_purchased_price', '$inv_selling_price', '$inv_total_cost', '$inv_latest_update', '$inv_update_reason', '$inv_username')";
            $edit_inventory = mysqli_query($connection, $query);
        }
    }
}