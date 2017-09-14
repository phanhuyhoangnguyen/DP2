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

        $checkbox = array();

        foreach ($_SESSION["listing"] as $itm)
        {
            if (isset($_POST["cart_$itm"]))
            {
                $checkbox[] = "$itm";
            }
        }

        $total_revenue = 0;
        $total_profit = 0;
        $rec_date = "";
        $rec_username = "";
        $itm_details = array();

        for ($i = 0; $i < count($checkbox); $i++) {

            $inv_query = "SELECT quantity, purchased_price, selling_price FROM Inventory WHERE itemID='$checkbox[$i]'";
            $inv_query_fetch = $connection->query($inv_query)->fetch_assoc();

            $purchased_price = $inv_query_fetch["purchased_price"]; //
            $selling_price = $inv_query_fetch["selling_price"]; //
            $inv_quantity = $inv_query_fetch["quantity"]; //
            $rec_date = date('Y-m-d H:i:s');
            $inv_latest_update = $rec_date; //
            $sold_quantity = mysqli_real_escape_string($connection, $_POST["quantity_$checkbox[$i]"]); //

            /*if ($sold_quantity == "") {
                $errMsg .= "<p>You must provide sold quantity amount of the item.</p>";
            } else if (!preg_match("/^[0-9]*$/", $sold_quantity)) {
                $errMsg .= "<p>You must enter a valid number for sold quantity.</p>";
            }*/

            $revenue = round(($selling_price * $sold_quantity), 2);

            $previous_data_query = "SELECT previous_purchased_price, stock_with_old_prices FROM $inv_table WHERE itemID = '$rec_itemID'";
            $previous_data_fetch = $connection->query($previous_data_query)->fetch_assoc();
            $stock_with_old_prices = $previous_data_fetch["stock_with_old_prices"];
            $previous_purchased_price = $previous_data_fetch["previous_purchased_price"];

            if ($stock_with_old_prices > 0) {
                if ($stock_with_old_prices >= $sold_quantity) {
                    $profit = round(($revenue - ($previous_purchased_price * (int)$sold_quantity)), 2);
                } else {
                    $profit = round($revenue - (($stock_with_old_prices * $previous_purchased_price) + (((int)$sold_quantity - $stock_with_old_prices) * $purchased_price)), 2);
                }
            } else {
                $profit = round(($revenue - ($purchased_price * (int)$sold_quantity)), 2);
            }

            $total_revenue += $revenue;
            $total_profit += $profit;

            $rec_username = $_SESSION["username"];
            $new_quantity = $inv_quantity - $sold_quantity;

            if ($new_quantity < 0) {
                $errMsg .= "<p>$checkbox[$i] is out of stock. Current item's quantity amount in stock is $inv_quantity. Please reduce your cart.</p>";
            }

            $itm_details[] = $checkbox[$i]."-".$sold_quantity."-".$new_quantity;
        }

        if ($errMsg != "") {
            echo $errMsg;
        } else {
            echo $total_profit;
            print_r($itm_details);
            echo $rec_date;
            echo $rec_username;
            echo $total_revenue;
        }
    }
}