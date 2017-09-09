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
@mysqli_select_db($connection, $table);
/*events after button "Show" is clicked*/

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["display_sale_record"]))
{
    $query = "SELECT * FROM $table ORDER BY saleID ASC";
    $result = mysqli_query($connection, $query);

    echo "<h1>Results</h1>\n";
    echo "<table border=\"1\">";
    echo "<tr>"
        ."<th scope=\"col\">SaleID</th>"
        ."<th scope=\"col\">itemID</th>"
        ."<th scope=\"col\">Date</th>"
        ."<th scope=\"col\">Sold Quantity</th>"
        ."<th scope=\"col\">Revenue</th>"
        ."<th scope=\"col\">Profit</th>"
        ."<th scope=\"col\">Cashier</th>"
        ."</tr>";
    while ($row = mysqli_fetch_assoc($result))
    {
        echo "<tr>";
        echo "<td>",$row["saleID"],"</td>";
        echo "<td>",$row["itemID"],"</td>";
        echo "<td>",$row["date"],"</td>";
        echo "<td>",$row["sold_quantity"],"</td>";
        echo "<td>",$row["revenue"],"</td>";
        echo "<td>",$row["profit"],"</td>";
        echo "<td>",$row["username"],"</td>";
        echo "</tr>";
    }
    echo "</table>";

}