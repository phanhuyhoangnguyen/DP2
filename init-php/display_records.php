<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/8/17
 * Time: 10:23 PM
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
} else {
    if ($_SESSION["username"] == "")
    {
        echo "<p>You must login to view sales history.</p>";
    } else {
        $query = "SELECT rec.saleID AS saleID, rec.date AS date, ri.revenue AS revenue, ri.profit AS profit, rec.username AS username, ri.itemID as itemID, ri.sold_quantity AS sold_quantity, itm.item_name AS item_name, inv.selling_price AS selling_price, ri.returned AS returned FROM $table rec, $inv_table inv, $item_table itm, $table_Ri ri WHERE ri.itemID = inv.itemID AND inv.itemID = itm.itemID AND ri.saleID = rec.saleID ORDER BY saleID ASC";
        $result = mysqli_query($connection, $query);

        echo "<h1>Results</h1>\n";
        echo "<table border=\"1\">";
        echo "<tr>"
            . "<th scope=\"col\">Sale ID</th>"
            . "<th scope=\"col\">Date</th>"
            . "<th scope=\"col\">Item ID</th>"
            . "<th scope=\"col\">Item Description</th>"
            . "<th scope=\"col\">Selling Price</th>"
            . "<th scope=\"col\">Sold Quantity</th>"
            . "<th scope=\"col\">Revenue</th>"
            . "<th scope=\"col\">Profit</th>"
            . "<th scope=\"col\">Returned</th>"
            . "<th scope=\"col\">Cashier</th>"
            . "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>", $row["saleID"], "</td>";
            echo "<td>", $row["date"], "</td>";
            echo "<td>", $row["itemID"], "</td>";
            echo "<td>", $row["item_name"], "</td>";
            echo "<td>", $row["selling_price"], "</td>";
            echo "<td>", $row["sold_quantity"], "</td>";
            echo "<td>", $row["revenue"], "</td>";
            echo "<td>", $row["profit"], "</td>";
            echo "<td>", $row["returned"], "</td>";
            echo "<td>", $row["username"], "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}