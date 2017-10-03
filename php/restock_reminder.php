<?php
/*
/---------------------------------------------------------/
    Task: Restock Reminder
    Date Created: 26 - Sep - 2017
    Author: Don Dave (Duy The Nguyen)
    Last Modified: 17:48  27 - Sep -2017
 /---------------------------------------------------------/
 */
error_reporting(0);
$connection = @mysqli_connect("localhost","westudyi_pharma","pharmacy", "westudyi_pharmacy");
$iven_table = "Inventory";
$itm_table = "Item";
$cat_table = "Category";
@mysqli_select_db($connection, $iven_table);
@mysqli_select_db($connection, $itm_table);
@mysqli_select_db($connection, $cat_table);

session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
}
else if($_SESSION["username"] == "")
{
    echo "<p>You must login to view the Restock reminder.</p>";
}
else 
{
    $query = "SELECT inv.*, cat.category_name AS category, itm.item_name AS item_name 
              FROM $iven_table inv, $itm_table itm, $cat_table cat 
              WHERE inv.itemID = itm.itemID 
              AND itm.categoryID = cat.categoryID 
              AND inv.quantity <=5
              ORDER BY inv.quantity ASC";
    $result = mysqli_query($connection, $query);

    echo "<h1>Notification</h1>\n";
    echo "<table border=\"1\">";
    echo "<tr>"
        . "<th scope=\"col\">Available Quantity</th>"
        . "<th scope=\"col\">Item ID</th>"
        . "<th scope=\"col\">Item Description</th>"
        . "<th scope=\"col\">Category of Item</th>"
        . "<th scope=\"col\">Purchased Price</th>"
        . "<th scope=\"col\">Selling Price</th>"
        . "<th scope=\"col\">Latest Update</th>"
        
        . "</tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>", $row["quantity"], "</td>";
        echo "<td>", $row["itemID"], "</td>";
        echo "<td>", $row["item_name"], "</td>";
        echo "<td>", $row["category"], "</td>";
        echo "<td>", $row["purchased_price"], "</td>";
        echo "<td>", $row["selling_price"], "</td>";
        echo "<td>", $row["latest_update"], "</td>";
        echo "</tr>";
    }
    echo "</table>";

    mysqli_close($connection);
}