<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/9/17
 * Time: 7:16 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$records_table = "Records";
$inventory_table = "Inventory";
@mysqli_select_db($connection, $table);
/*events after button "Show" is clicked*/

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["display_month"]))
{

    $year = mysqli_real_escape_string($connection, $_POST["select_year"]);
    $month = mysqli_real_escape_string($connection, $_POST["select_month"]);

    $query = "SELECT * FROM $records_table WHERE YEAR(date)='$year' AND MONTH(date)='$month' ORDER BY saleID ASC";
    $result = mysqli_query($connection, $query);

    $report_query = "SELECT inv.itemID, total_cost, COUNT(rec.itemID) AS total_orders, SUM(sold_quantity) AS total_sold_quantity, SUM(revenue) AS total_revenue, SUM(profit) AS total_profit FROM $inventory_table inv, $records_table rec WHERE inv.itemID = rec.itemID AND YEAR(rec.date)='$year' AND MONTH(rec.date)='$month' GROUP BY rec.itemID ORDER BY rec.itemID ASC";


    $result_report = mysqli_query($connection, $report_query);

    $month_name = "";
    switch($month) {
        case 1:
            $month_name = "January";
            break;
        case 2:
            $month_name = "February";
            break;
        case 3:
            $month_name = "March";
            break;
        case 4:
            $month_name = "April";
            break;
        case 5:
            $month_name = "May";
            break;
        case 6:
            $month_name = "June";
            break;
        case 7:
            $month_name = "July";
            break;
        case 8:
            $month_name = "August";
            break;
        case 9:
            $month_name = "September";
            break;
        case 10:
            $month_name = "October";
            break;
        case 11:
            $month_name = "November";
            break;
        case 12:
            $month_name = "December";
            break;
        default:
            $month_name = "";
            break;
    }

    echo "<h1>Report of $month_name</h1>\n";
    echo "<table border=\"1\">";
    echo "<tr>"
        ."<th scope=\"col\">itemID</th>"
        ."<th scope=\"col\">Total Sales</th>"
        ."<th scope=\"col\">Total Sold Quantity</th>"
        ."<th scope=\"col\">Total Revenue</th>"
        ."<th scope=\"col\">Total Profit</th>"
        ."<th scope=\"col\">Total Cost</th>"
        ."</tr>";
    while ($row = mysqli_fetch_assoc($result_report))
    {
        echo "<tr>";
        echo "<td>",$row["itemID"],"</td>";
        echo "<td>",$row["total_orders"],"</td>";
        echo "<td>",$row["total_sold_quantity"],"</td>";
        echo "<td>",$row["total_revenue"],"</td>";
        echo "<td>",$row["total_profit"],"</td>";
        echo "<td>",$row["total_cost"],"</td>";
        echo "</tr>";
    }
    echo "</table><br/>";


    echo "<h1>Transaction Details</h1>\n";
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