<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/14/17
 * Time: 12:39 AM
 */
/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$record_table = "Records";
$inv_table = "Inventory";
$ri_table = "record_items";
@mysqli_select_db($connection, $record_table);
@mysqli_select_db($connection, $inv_table);
@mysqli_select_db($connection, $ri_table);
date_default_timezone_set('Australia/Melbourne');
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["submit_return"])) {

    $errMsg = "";

    $selected = array();
    $num_returned = array();
    $selected_order = $_SESSION["selected_order"];
    $update_person = $_SESSION["username"];

    foreach ($_SESSION["returned_item"] as $itm) {
        if (isset($_POST["checked_$itm"])) {
            $selected[] = $itm;
        }
    }

    $new_sold_a = array();
    $new_rev_a = array();
    $new_pro_a = array();
    $returned_quantity_a = array();

    for ($i = 0; $i < count($selected); $i++) {

        $selected[$i];
        $returned_quantity_a[] = $returned_quantity = $_POST["num_$selected[$i]"];

        $fetch_rev_pro_query = "SELECT revenue, profit, sold_quantity FROM $ri_table WHERE (itemID = '$selected[$i]') AND (saleID = '$selected_order')";
        $fetched_rev_pro = $connection->query($fetch_rev_pro_query)->fetch_assoc();

        $rev = $fetched_rev_pro["revenue"];
        $pro = $fetched_rev_pro["profit"];
        $sold = $fetched_rev_pro["sold_quantity"];

        if ($returned_quantity > $sold) {
            $errMsg .= "<p>Maximum allowed items can be returned is $sold. Please try again!</p>";
        } else if (!preg_match("/^[0-9]*$/", $returned_quantity)) {
            $errMsg .= "<p>Please provide a valid number.</p>";
        } else if ($returned_quantity == "") {
            $errMsg .= "<p>Please provide a value for Total Returned for item: $selected[$i].</p>";
        }


        $new_sold = $sold - $returned_quantity;
        $new_rev = ($rev / $sold) * $new_sold;
        $new_pro = ($pro / $sold) * $new_sold;

        $new_sold_a[] = $new_sold;
        $new_rev_a[] = $new_rev;
        $new_pro_a[] = $new_pro;
    }

    if ($errMsg != "") {
        echo $errMsg;
    } else {

        for ($t = 0; $t < count($selected); $t++) {

            $rt_query = "SELECT returned FROM $ri_table WHERE itemID='$selected[$t]' AND saleID='$selected_order'";
            $rt_stock = $connection->query($rt_query)->fetch_assoc();

            $push_rt = $rt_stock["returned"] + $returned_quantity_a[$t];

            $update_order_query = "UPDATE $ri_table SET sold_quantity = '$new_sold_a[$t]', revenue='$new_rev_a[$t]', profit='$new_pro_a[$t]', returned='$push_rt' WHERE saleID='$selected_order' AND itemID='$selected[$t]'";
            $update = mysqli_query($connection, $update_order_query);

            $stock_query = "SELECT returned_stock FROM $inv_table WHERE itemID='$selected[$t]'";
            $returned_stock = $connection->query($stock_query)->fetch_assoc();

            $update_stock = $returned_quantity_a[$t] + $returned_stock["returned_stock"];

            $update_inv_query = "UPDATE $inv_table SET returned_stock='$update_stock', username='$update_person', update_reason='returned_item' WHERE itemID='$selected[$t]'";
            $update_inv = mysqli_query($connection, $update_inv_query);
        }
        header("Location: manage.php");
    }
}