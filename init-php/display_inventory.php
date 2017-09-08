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
@mysqli_select_db($connection, $table);
/*events after button "Show" is clicked*/

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["display_inv"]))
{
    $query = "SELECT * FROM $table";
    $result = mysqli_query($connection, $query);

    echo "<h1>Results</h1>\n";
    echo "<table border=\"1\">";
    echo "<tr>"
        ."<th scope=\"col\">itemID</th>"
        ."<th scope=\"col\">Quantity</th>"
        ."<th scope=\"col\">Purchased Price</th>"
        ."<th scope=\"col\">Selling Price</th>"
        ."<th scope=\"col\">Total Cost</th>"
        ."<th scope=\"col\">Latest Update</th>"
        ."<th scope=\"col\">Update Reason</th>"
        ."<th scope=\"col\">Cashier</th>"
        ."</tr>";
    while ($row = mysqli_fetch_assoc($result))
    {
        echo "<tr>";
        echo "<td>",$row["itemID"],"</td>";
        echo "<td>",$row["quantity"],"</td>";
        echo "<td>",$row["purchased_price"],"</td>";
        echo "<td>",$row["selling_price"],"</td>";
        echo "<td>",$row["total_cost"],"</td>";
        echo "<td>",$row["latest_update"],"</td>";
        echo "<td>",$row["update_reason"],"</td>";
        echo "<td>",$row["username"],"</td>";
        echo "</tr>";
    }
    echo "</table>";

}