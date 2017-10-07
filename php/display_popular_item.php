<?php
/**
 * Created by PhpStorm.
 * User: phanNguyen
 * Date: 04/10/17
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

    //Receive value from js
    $date = mysqli_real_escape_string($connection, $_POST["select_date"]);
    $year = mysqli_real_escape_string($connection, $_POST["select_year"]);
    $month = mysqli_real_escape_string($connection, $_POST["select_month"]);
    $option = mysqli_real_escape_string($connection, $_POST["option"]);

    //Convert month's number to name
    if ($option == "month_view" || $option=="day_view") {
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
    }

    //if the view is day
    if ($option == "day_view") {
        //if the year, month or date is empty
        if ($year == "" || $month == "" || $date== "")
            $errMsg = "<p>You must input time, in the following format: dd/mm/yyyy</p>";
        else {
            $daily_popular_item = "SELECT RIT.itemID, ITM.item_name AS item_name,
                              INV.selling_price AS selling_price, SUM(RIT.profit) AS total_profit,
                              SUM(RIT.revenue) AS total_revenue, SUM(RIT.sold_quantity) AS sold_quantity,
                              COUNT(REC.saleID) AS total_sales
                              FROM $table REC INNER JOIN $table_ri RIT ON REC.saleID = RIT.saleID
                              INNER JOIN $inv_table INV ON RIT.itemID = INV.itemID
                              INNER JOIN $item_table ITM ON INV.itemID = ITM.itemID
                              WHERE YEAR(REC.date)='$year' AND MONTH(REC.date)='$month' AND DAY(REC.date) = '$date'
                              GROUP BY itemID ORDER BY sold_quantity DESC";
            $daily_popular_item_result = mysqli_query($connection, $daily_popular_item);

            //print data if the query return results
            if ($daily_popular_item_result->num_rows > 0) {
                echo "<h1>Top Product of $date, $month_name, $year</h1>\n";
                echo "<table border=\"1\">";
                echo "<tr>"
                    . "<th scope=\"col\">Product ID</th>"
                    . "<th scope=\"col\">Product Name</th>"
                    . "<th scope=\"col\">Total Sold Quantity</th>"
                    . "<th scope=\"col\">Total Revenue</th>"
                    . "<th scope=\"col\">Total Profits</th>"
                    . "<th scope=\"col\">Total Number of Sales</th>"
                    . "<th scope=\"col\">Selling Price</th>"
                    . "</tr>";
                while ($row = mysqli_fetch_assoc($daily_popular_item_result)) {
                    echo "<tr>";
                    echo "<td>", $row["itemID"], "</td>";
                    echo "<td>", $row["item_name"], "</td>";
                    echo "<td>", $row["sold_quantity"], "</td>";
                    echo "<td>", $row["total_revenue"], "</td>";
                    echo "<td>", $row["total_profit"], "</td>";
                    echo "<td>", $row["total_sales"], "</td>";
                    echo "<td>", $row["selling_price"], "</td>";
                    echo "</tr>";
                }
                echo "</table><br/>";
            }
            else $errMsg = "No information available for $date, $month, $year";
        }
    }
    //if the view is month
        else if ($option == "month_view") {
            if ($year == "" || $month == "")
                $errMsg = "<p>You must input time, in the following format: mm/yyyy</p>";
            else {
                $month_popular_item = "SELECT RIT.itemID, ITM.item_name AS item_name,
                              INV.selling_price AS selling_price, SUM(RIT.profit) AS total_profit,
                              SUM(RIT.revenue) AS total_revenue, SUM(RIT.sold_quantity) AS sold_quantity,
                              COUNT(REC.saleID) AS total_sales
                              FROM $table REC INNER JOIN $table_ri RIT ON REC.saleID = RIT.saleID
                              INNER JOIN $inv_table INV ON RIT.itemID = INV.itemID
                              INNER JOIN $item_table ITM ON INV.itemID = ITM.itemID
                              WHERE YEAR(REC.date)='$year' AND MONTH(REC.date)='$month'
                              GROUP BY itemID ORDER BY sold_quantity DESC";

                $month_popular_item_result = mysqli_query($connection, $month_popular_item);

                if ($month_popular_item_result->num_rows > 0) {
                    echo "<h1>Top Product of $month_name, $year</h1>\n";
                    echo "<table border=\"1\">";
                    echo "<tr>"
                        . "<th scope=\"col\">Product ID</th>"
                        . "<th scope=\"col\">Product Name</th>"
                        . "<th scope=\"col\">Total Sold Quantity</th>"
                        . "<th scope=\"col\">Total Revenue</th>"
                        . "<th scope=\"col\">Total Profits</th>"
                        . "<th scope=\"col\">Total Number of Sales</th>"
                        . "<th scope=\"col\">Selling Price</th>"
                        . "</tr>";
                    while ($row = mysqli_fetch_assoc($month_popular_item_result)) {
                        echo "<tr>";
                        echo "<td>", $row["itemID"], "</td>";
                        echo "<td>", $row["item_name"], "</td>";
                        echo "<td>", $row["sold_quantity"], "</td>";
                        echo "<td>", $row["total_revenue"], "</td>";
                        echo "<td>", $row["total_profit"], "</td>";
                        echo "<td>", $row["total_sales"], "</td>";
                        echo "<td>", $row["selling_price"], "</td>";
                        echo "</tr>";
                    }
                    echo "</table><br/>";
                }
                else $errMsg = "No information available for $month, $year";
            }
        }
        //if the view is year
        else if ($option == "year_view") {
            if ($year == "")
                $errMsg = "<p>You must input time, in the following format: yyyy</p>";
            else {
                $year_popular_item = "SELECT RIT.itemID, ITM.item_name AS item_name,
                              INV.selling_price AS selling_price, SUM(RIT.profit) AS total_profit,
                              SUM(RIT.revenue) AS total_revenue, SUM(RIT.sold_quantity) AS sold_quantity,
                              COUNT(REC.saleID) AS total_sales
                              FROM $table REC INNER JOIN $table_ri RIT ON REC.saleID = RIT.saleID
                              INNER JOIN $inv_table INV ON RIT.itemID = INV.itemID
                              INNER JOIN $item_table ITM ON INV.itemID = ITM.itemID
                              WHERE YEAR(REC.date)='$year'
                              GROUP BY itemID ORDER BY sold_quantity DESC";

                $year_popular_item_result = mysqli_query($connection, $year_popular_item);
                //print "number is $year_popular_item_result->num_rows";
                //print "option is $option";
                if ($year_popular_item_result->num_rows > 0) {

                    echo "<h1>Top Product of $year</h1>\n";
                    echo "<table border=\"1\">";
                    echo "<tr>"
                        . "<th scope=\"col\">Product ID</th>"
                        . "<th scope=\"col\">Product Name</th>"
                        . "<th scope=\"col\">Total Sold Quantity</th>"
                        . "<th scope=\"col\">Total Revenue</th>"
                        . "<th scope=\"col\">Total Profits</th>"
                        . "<th scope=\"col\">Total Number of Sales</th>"
                        . "<th scope=\"col\">Selling Price</th>"
                        . "</tr>";
                    while ($row = mysqli_fetch_assoc($year_popular_item_result)) {
                        echo "<tr>";
                        echo "<td>", $row["itemID"], "</td>";
                        echo "<td>", $row["item_name"], "</td>";
                        echo "<td>", $row["sold_quantity"], "</td>";
                        echo "<td>", $row["total_revenue"], "</td>";
                        echo "<td>", $row["total_profit"], "</td>";
                        echo "<td>", $row["total_sales"], "</td>";
                        echo "<td>", $row["selling_price"], "</td>";
                        echo "</tr>";
                    }
                    echo "</table><br/>";
                }
                else $errMsg = "No information available for $year";
            }
        }
        //the view is unselected
        else{
            $errMsg = "<p>You must select the view</p>";
        }


    echo $errMsg;
    mysqli_close($connection);
}