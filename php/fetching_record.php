<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/15/17
 * Time: 8:32 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "Records";
$table_Ri = "record_items";
$inv_table = "Inventory";
$item_table = "Item";

@mysqli_select_db($connection, $table);
@mysqli_select_db($connection, $inv_table);
@mysqli_select_db($connection, $item_table);
@mysqli_select_db($connection, $table_Ri);
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if ($_SESSION["username"] == "") {
    echo "<p>Please login to use the system.</p>";
} else {
    $errMsg = "";
    $saleID = $_POST["saleid"];
    $_SESSION["selected_order"] = $saleID;

    if (!preg_match("/^[0-9]*$/", $saleID)) {
        $errMsg .= "<p>Please enter a valid sale ID.</p>";
    } else if ($saleID == "") {
        $errMsg .= "<p>Please provide a saleID to process return.</p>";
    }

    if ($errMsg != "") {
        echo $errMsg;
    } else {

        $returned_item = array();

        $query = "SELECT rec.saleID AS saleID, rec.date AS date, ri.revenue AS revenue, ri.profit AS profit, 
                  rec.username AS username, ri.itemID as itemID, ri.sold_quantity AS sold_quantity,
                  itm.item_name AS item_name, inv.selling_price AS selling_price 
                  FROM $table rec, $inv_table inv, $item_table itm, $table_Ri ri 
                  WHERE ri.itemID = inv.itemID AND rec.saleID = '$saleID' AND inv.itemID = itm.itemID AND ri.saleID = rec.saleID";

        $result = mysqli_query($connection, $query);

        if ($result->num_rows > 0) {
            echo "<h4>Order Details:</h4>";
            echo "<table border=\"1\">";
            echo "<tr>"
                . "<th scope=\"col\">Return Select</th>"
                . "<th scope=\"col\">Sale ID</th>"
                . "<th scope=\"col\">Date</th>"
                . "<th scope=\"col\">Item ID</th>"
                . "<th scope=\"col\">Item Description</th>"
                . "<th scope=\"col\">Selling Price</th>"
                . "<th scope=\"col\">Sold Quantity</th>"
                . "<th scope=\"col\">Revenue</th>"
                . "<th scope=\"col\">Profit</th>"
                . "<th scope=\"col\">Cashier</th>"
                . "<th scope=\"col\">Total Returned</th>"
                . "</tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td align='center'>"."<input type='checkbox' name='checked_$row[itemID]' name='checked_$row[itemID]' value='$row[itemID]'/>"."</td>";
                echo "<td>", $row["saleID"], "</td>";
                echo "<td>", $row["date"], "</td>";
                echo "<td>", $row["itemID"], "</td>";
                echo "<td>", $row["item_name"], "</td>";
                echo "<td>", $row["selling_price"], "</td>";
                echo "<td>", $row["sold_quantity"], "</td>";
                echo "<td>", $row["revenue"], "</td>";
                echo "<td>", $row["profit"], "</td>";
                echo "<td>", $row["username"], "</td>";
                echo "<td align='center'>"."<input type='text' name='num_$row[itemID]' id='num_$row[itemID]'>"."</td>";
                $returned_item[] = $row["itemID"];
                echo "</tr>";
            }
            echo "</table><br/>";
            $_SESSION["returned_item"] = $returned_item;
            echo "<input type='submit' id='submit_return' name='submit_return' value='Process Return'/>";
        } else {
            echo "<p>Order #$saleID not found.</p>";
        }
    }
    mysqli_close($connection);
}