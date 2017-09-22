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
$table = "Records";
$table_Ri = "record_items";
$inv_table = "Inventory";
$item_table = "Item";
@mysqli_select_db($connection, $records_table);
@mysqli_select_db($connection, $table_ri);
@mysqli_select_db($connection, $inventory_table);
@mysqli_select_db($connection, $item_table);
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if ($_SESSION["username"] == "") {
    echo "<p>You must login to use the system.</p>";
} else {
    $errMsg = "";

    $year = mysqli_real_escape_string($connection, $_POST["select_year"]);
    $month = mysqli_real_escape_string($connection, $_POST["select_month"]);
    $option = mysqli_real_escape_string($connection, $_POST["display_option"]);

    if ($year == "" || $month == "") {
        $errMsg .= "<p>You must select month and year of the report.</p>";
    }

    if ($option == "") {
        $errMsg .= "<p>You must select an option to display the reports.</p>";
    }

    if ($errMsg != "") {
        echo $errMsg;
    } else {

        $query = "SELECT rec.saleID AS saleID, rec.date AS date, ri.revenue AS revenue, ri.profit AS profit, 
                  rec.username AS username, ri.itemID as itemID, ri.sold_quantity AS sold_quantity, 
                  itm.item_name AS item_name, inv.selling_price AS selling_price 
                  FROM $table rec, $inv_table inv, $item_table itm, $table_Ri ri 
                  WHERE YEAR(rec.date)='$year' AND MONTH(rec.date)='$month' 
                  AND ri.itemID = inv.itemID AND inv.itemID = itm.itemID 
                  AND ri.saleID = rec.saleID ORDER BY saleID ASC";

        $result = mysqli_query($connection, $query);

        $report_query = "SELECT ri.itemID AS itemID, itm.item_name AS item_name, inv.selling_price AS selling_price, 
                          SUM(ri.revenue) AS total_revenue, SUM(ri.profit) AS total_profit, 
                          SUM(ri.sold_quantity) AS total_sold, inv.total_cost AS total_cost, inv.quantity AS remaining, 
                          COUNT(ri.saleID) AS in_sales FROM $table r, $table_Ri ri, $inv_table inv, $item_table itm 
                          WHERE r.saleID = ri.saleID AND ri.itemID = inv.itemID AND itm.itemID = inv.itemID 
                          AND YEAR(r.date)='$year' AND MONTH(r.date)='$month' GROUP BY ri.itemID ORDER BY ri.itemID ASC";
        $result_report = mysqli_query($connection, $report_query);

        $general_report_query = "SELECT COUNT(DISTINCT ri.saleID) AS total_sales, SUM(ri.revenue) AS total_revenue, 
                                  SUM(ri.profit) AS total_profit, SUM(DISTINCT inv.total_cost) AS total_cost, 
                                  COUNT(DISTINCT ri.itemID) AS total_items, SUM(ri.sold_quantity) AS total_items_sold 
                                  FROM $table r, $table_Ri ri, $inv_table inv WHERE ri.saleID = r.saleID AND ri.itemID = inv.itemID 
                                  AND YEAR(r.date)='$year' AND MONTH(r.date)='$month' GROUP BY ri.saleID AND YEAR(r.date) AND MONTH(r.date)";
        $general_report = mysqli_query($connection, $general_report_query);

        $month_name = "";
        switch ($month) {
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

        if ($result_report->num_rows > 0 && $general_report->num_rows > 0 && $result->num_rows > 0) {
            if ($option == "by_item") {
                echo "<h1>Detailed Report of $month_name $year</h1>\n";
                echo "<table border=\"1\">";
                echo "<tr>"
                    . "<th scope=\"col\">itemID</th>"
                    . "<th scope=\"col\">Item Description</th>"
                    . "<th scope=\"col\">Selling Prices</th>"
                    . "<th scope=\"col\">In Sales</th>"
                    . "<th scope=\"col\">Total Sold Quantity</th>"
                    . "<th scope=\"col\">Total Revenue</th>"
                    . "<th scope=\"col\">Total Profit</th>"
                    . "<th scope=\"col\">Total Cost</th>"
                    . "<th scope=\"col\">Remaining in Stock</th>"
                    . "</tr>";
                while ($row = mysqli_fetch_assoc($result_report)) {
                    echo "<tr>";
                    echo "<td>", $row["itemID"], "</td>";
                    echo "<td>", $row["item_name"], "</td>";
                    echo "<td>", $row["selling_price"], "</td>";
                    echo "<td>", $row["in_sales"], "</td>";
                    echo "<td>", $row["total_sold"], "</td>";
                    echo "<td>", $row["total_revenue"], "</td>";
                    echo "<td>", $row["total_profit"], "</td>";
                    echo "<td>", $row["total_cost"], "</td>";
                    echo "<td>", $row["remaining"], "</td>";
                    echo "</tr>";
                }
                echo "</table><br/>";
            } else {

                echo "<h1>General of $month_name $year</h1>\n";
                echo "<table border=\"1\">";
                echo "<tr>"
                    . "<th scope=\"col\">Total Sales</th>"
                    . "<th scope=\"col\">Total Revenue</th>"
                    . "<th scope=\"col\">Total Profit</th>"
                    . "<th scope=\"col\">Total Cost of Sold Items</th>"
                    . "<th scope=\"col\">Number of Kinds of Sold Item</th>"
                    . "<th scope=\"col\">Total Sold Items</th>"
                    . "</tr>";
                while ($row = mysqli_fetch_assoc($general_report)) {
                    echo "<tr>";
                    echo "<td>", $row["total_sales"], "</td>";
                    echo "<td>", $row["total_revenue"], "</td>";
                    echo "<td>", $row["total_profit"], "</td>";
                    echo "<td>", $row["total_cost"], "</td>";
                    echo "<td>", $row["total_items"], "</td>";
                    echo "<td>", $row["total_items_sold"], "</td>";
                    echo "</tr>";
                }
                echo "</table><br/>";
            }

            echo "<h1>Transaction Details</h1>\n";
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
                echo "<td>", $row["username"], "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No available information for Reports of $month_name - $year.</p>";
        }
    }
    mysqli_close($connection);
}