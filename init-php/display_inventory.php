<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/8/17
 * Time: 11:25 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "Inventory";
$itm_table = "Item";
$cat_table = "Category";
@mysqli_select_db($connection, $table);
@mysqli_select_db($connection, $itm_table);
@mysqli_select_db($connection, $cat_table);

session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else {
    if ($_SESSION["username"] == "")
    {
        echo "<p>You must login to view the inventory.</p>";
    } else {
        $query = "SELECT inv.*, cat.category_name AS category, itm.item_name AS item_name FROM $table inv, $itm_table itm, $cat_table cat WHERE inv.itemID = itm.itemID AND itm.categoryID = cat.categoryID ORDER BY itemID ASC";
        $result = mysqli_query($connection, $query);

        echo "<h1>Results</h1>\n";
        echo "<table border=\"1\">";
        echo "<tr>"
            . "<th scope=\"col\">Item ID</th>"
            . "<th scope=\"col\">Item Description</th>"
            . "<th scope=\"col\">Category of Item</th>"
            . "<th scope=\"col\">Available Quantity</th>"
            . "<th scope=\"col\">Returned Stock</th>"
            . "<th scope=\"col\">Purchased Price</th>"
            . "<th scope=\"col\">Selling Price</th>"
            . "<th scope=\"col\">Total Cost</th>"
            . "<th scope=\"col\">Latest Update</th>"
            . "<th scope=\"col\">Update Reason</th>"
            . "<th scope=\"col\">Cashier</th>"
            . "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>", $row["itemID"], "</td>";
            echo "<td>", $row["item_name"], "</td>";
            echo "<td>", $row["category"], "</td>";
            echo "<td>", $row["quantity"], "</td>";
            echo "<td>", $row["returned_stock"], "</td>";
            echo "<td>", $row["purchased_price"], "</td>";
            echo "<td>", $row["selling_price"], "</td>";
            echo "<td>", $row["total_cost"], "</td>";
            echo "<td>", $row["latest_update"], "</td>";
            echo "<td>", $row["update_reason"], "</td>";
            echo "<td>", $row["username"], "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}