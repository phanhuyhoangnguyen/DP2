<?php
/**
 * Created by PhpStorm.
 * User: phanNguyen
 * Date: 20/9/17
 * Time: 14:20 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "Records";
$table_ri = "record_items";
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

    $date = mysqli_real_escape_string($connection, $_POST["select_date"]);
    $year = mysqli_real_escape_string($connection, $_POST["select_year"]);
    $month = mysqli_real_escape_string($connection, $_POST["select_month"]);

    if ($year == "" || $month == "" || $date=="") {
        $errMsg .= "<p>You must select month and year of the report.</p>";
    } else {
        $errMsg = "";
        $all_transaction = "SELECT REC.saleID AS saleID, REC.date AS date, RIT.revenue AS revenue, RIT.profit AS profit,
                  REC.username AS username, RIT.itemID as itemID, RIT.sold_quantity AS sold_quantity,
                  ITM.item_name AS item_name, INV.selling_price AS selling_price 
                  FROM $table REC INNER JOIN $table_ri RIT ON REC.saleID = RIT.saleID
                  INNER JOIN $inv_table INV ON RIT.itemID = INV.itemID
                  INNER JOIN $item_table ITM ON INV.itemID = ITM.itemID
                  WHERE DAY(REC.date) ='$date' AND YEAR(REC.date)='$year' AND MONTH(REC.date)='$month' 
                  ORDER BY REC.saleID ASC";
        $all_transaction_result = mysqli_query($connection, $all_transaction);

        $summary_sale_report_query = "SELECT COUNT(DISTINCT REC.saleID) AS total_sales, SUM(RIT.revenue) AS total_revenue,
                                  SUM(RIT.profit) AS total_profit, SUM(DISTINCT INV.total_cost) AS total_cost, 
                                  COUNT(DISTINCT RIT.itemID) AS total_items, SUM(RIT.sold_quantity) AS total_items_sold 
                                  FROM $table REC INNER JOIN $table_ri RIT ON REC.saleID = RIT.saleID
                                  INNER JOIN $inv_table INV ON RIT.itemID = INV.itemID
                                  WHERE YEAR(REC.date)='$year' AND MONTH(REC.date)='$month' AND DAY(REC.date)='$date' GROUP BY RIT.saleID AND YEAR(REC.date) AND MONTH(REC.date)";
        $summary_sale_report = mysqli_query($connection, $summary_sale_report_query);

        $summary_item_report_query = "SELECT RIT.itemID AS itemID, ITM.item_name AS item_name,
                                  SUM(RIT.sold_quantity) AS total_quantity, SUM(INV.quantity) as available, SUM(RIT.profit) as total_profit,
                                  SUM(RIT.revenue) as total_revenue, SUM(INV.total_cost) AS total_cost                  
                                  FROM $table REC INNER JOIN $table_ri RIT ON REC.saleID = RIT.saleID
                                  INNER JOIN $inv_table INV ON RIT.itemID = INV.itemID
                                  INNER JOIN $item_table ITM ON INV.itemID = ITM.itemID
                                  WHERE DAY(REC.date) ='$date' AND YEAR(REC.date)='$year' AND MONTH(REC.date)='$month' GROUP BY RIT.itemID";
        $summary_item_report = mysqli_query($connection, $summary_item_report_query);

        $report_staff_query = "SELECT REC.username AS username, COUNT(DISTINCT REC.saleID) as total_sales, SUM(RIT.revenue) AS total_revenue,
                              SUM(RIT.profit) AS total_profit, SUM(RIT.sold_quantity) AS total_sold
                          FROM $table REC INNER JOIN $table_ri RIT ON REC.saleID = RIT.saleID
                          INNER JOIN $inv_table INV ON RIT.itemID = INV.itemID
                          INNER JOIN $item_table ITM ON INV.itemID = ITM.itemID
                          WHERE DAY(REC.date) ='$date' AND YEAR(REC.date)='$year' AND MONTH(REC.date)='$month' 
                          GROUP BY username";
        $result_report_staff = mysqli_query($connection, $report_staff_query);



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

        if ($summary_item_report->num_rows > 0 || $summary_sale_report->num_rows > 0
            || $all_transaction_result->num_rows > 0 || $result_report_staff>0) {

                echo "<h1>Summary of $date, $month_name $year</h1>\n";
                echo "<table border=\"1\">";
                echo "<tr>"
                    . "<th scope=\"col\">Total Sales</th>"
                    . "<th scope=\"col\">Total Revenue</th>"
                    . "<th scope=\"col\">Total Profit</th>"
                    . "<th scope=\"col\">Total Cost of Sold Items</th>"
                    . "<th scope=\"col\">Number of Kinds of Sold Item</th>"
                    . "<th scope=\"col\">Total Sold Items</th>"
                    . "</tr>";
                while ($row = mysqli_fetch_assoc($summary_sale_report)) {
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

            echo "<h1>Summary Sale By Staffs</h1>\n";
            echo "<table border=\"1\">";
            echo "<tr>"
                . "<th scope=\"col\">Staff ID</th>"
                . "<th scope=\"col\">Number of Sale</th>"
                . "<th scope=\"col\">Total Revenue</th>"
                . "<th scope=\"col\">Total Profit</th>"
                . "<th scope=\"col\">Total Sold Item</th>"
                . "</tr>";
            while ($row = mysqli_fetch_assoc($result_report_staff)) {
                echo "<tr>";
                echo "<td>", $row["username"], "</td>";
                echo "<td>", $row["total_sales"], "</td>";
                echo "<td>", $row["total_revenue"], "</td>";
                echo "<td>", $row["total_profit"], "</td>";
                echo "<td>", $row["total_sold"], "</td>";
                echo "</tr>";
            }
            echo "</table>";

            echo "<h1>Summary Sale By Item</h1>\n";
            echo "<table border=\"1\">";
            echo "<tr>"
                . "<th scope=\"col\">Product ID</th>"
                . "<th scope=\"col\">Product Name</th>"
                . "<th scope=\"col\">Product Sales</th>"
                . "<th scope=\"col\">Profit</th>"
                . "<th scope=\"col\">Revenue</th>"
                . "<th scope=\"col\">Cost</th>"
                . "<th scope=\"col\">Available Stock</th>"
                . "</tr>";
            while ($row = mysqli_fetch_assoc($summary_item_report)) {
                echo "<tr>";
                echo "<td>", $row["itemID"], "</td>";
                echo "<td>", $row["item_name"], "</td>";
                echo "<td>", $row["total_quantity"], "</td>";
                echo "<td>", $row["total_profit"], "</td>";
                echo "<td>", $row["total_revenue"], "</td>";
                echo "<td>", $row["total_cost"], "</td>";
                echo "<td>", $row["available"], "</td>";
                echo "</tr>";
            }
            echo "</table>";

            echo "<h1>Transaction Records</h1>\n";
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
            while ($row = mysqli_fetch_assoc($all_transaction_result)) {
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