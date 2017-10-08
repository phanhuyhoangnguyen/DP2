<?php
/**
 * Created by PhpStorm.
 * User: phanNguyen
 * Date: 07/10/17
 * Time: 1:20 PM
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

/*Check for database connection*/
if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if ($_SESSION["username"] == "") {
    /* Error Mesage*/
    echo "<p>You must login to use the system.</p>";
} else {
    $errMsg = "";
    /*Retrive value from js*/
    $year = mysqli_real_escape_string($connection, $_POST["select_year"]);
    $month = mysqli_real_escape_string($connection, $_POST["select_month"]);

    /*check if any value is empty*/
    if ($year == "" || $month == "") {
        $errMsg = "<p>You must select month and year of the report.</p>";
    }
    else if ($year == null || $month == null){
        $errMsg = "<p>variable are null</p>";
    }
    else {
        $errMsg = "";
        /*refund query*/
        $query = "SELECT REC.saleID AS saleID, REC.date AS date, REC.username AS username,
                  RIT.itemID as itemID, RIT.sold_quantity AS sold_quantity, RIT.returned AS returned,
                  ITM.item_name AS item_name, INV.selling_price AS selling_price
                  FROM $table REC INNER JOIN $table_ri RIT ON REC.saleID = RIT.saleID
                  INNER JOIN $inv_table INV ON RIT.itemID = INV.itemID
                  INNER JOIN $item_table ITM ON INV.itemID = ITM.itemID
                  WHERE YEAR(REC.date)='$year' AND MONTH(REC.date)='$month' ORDER BY saleID ASC";

        $result_report = mysqli_query($connection, $query);



        /*group item view query*/
        $item_query = "SELECT RIT.itemID as itemID, SUM(RIT.sold_quantity) AS sold_quantity, SUM(RIT.returned) AS returned,
                  ITM.item_name AS item_name, INV.selling_price AS selling_price
                          FROM $table REC INNER JOIN $table_ri RIT ON REC.saleID = RIT.saleID
                        INNER JOIN $inv_table INV ON RIT.itemID = INV.itemID
                        INNER JOIN $item_table ITM ON INV.itemID = ITM.itemID
                  WHERE YEAR(REC.date)='$year' AND MONTH(REC.date)='$month' GROUP BY itemID";

        $result_item_report = mysqli_query($connection, $item_query);
        /*convert from number to month's name*/
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

        /*display if the result is found*/
        if ($result_report -> num_rows > 0 || $result_item_report->num_rows > 0) {
            echo "<h1>Refund Report By Item of $month_name, $year</h1>\n";
            echo "<table border=\"1\">";
            echo "<tr>"
                . "<th scope=\"col\">Item ID</th>"
                . "<th scope=\"col\">Item Description</th>"
                . "<th scope=\"col\">Selling Price</th>"
                . "<th scope=\"col\">Sold Quantity</th>"
                . "<th scope=\"col\">Returned</th>"
                . "</tr>";
            while ($row = mysqli_fetch_assoc($result_item_report)) {
                echo "<tr>";
                echo "<td>", $row["itemID"], "</td>";
                echo "<td>", $row["item_name"], "</td>";
                echo "<td>", $row["selling_price"], "</td>";
                echo "<td>", $row["sold_quantity"], "</td>";
                echo "<td>", $row["returned"], "</td>";
                echo "</tr>";
            }
            echo "</table><br>";

            echo "<h1>Refund Items of $month_name, $year</h1>\n";
            echo "<table border=\"1\">";
            echo "<tr>"
                . "<th scope=\"col\">Sale ID</th>"
                . "<th scope=\"col\">Date</th>"
                . "<th scope=\"col\">Item ID</th>"
                . "<th scope=\"col\">Item Description</th>"
                . "<th scope=\"col\">Selling Price</th>"
                . "<th scope=\"col\">Sold Quantity</th>"
                . "<th scope=\"col\">Returned</th>"
                . "<th scope=\"col\">Cashier</th>"
                . "</tr>";
            while ($row = mysqli_fetch_assoc($result_report)) {
                echo "<tr>";
                echo "<td>", $row["saleID"], "</td>";
                echo "<td>", $row["date"], "</td>";
                echo "<td>", $row["itemID"], "</td>";
                echo "<td>", $row["item_name"], "</td>";
                echo "<td>", $row["selling_price"], "</td>";
                echo "<td>", $row["sold_quantity"], "</td>";
                echo "<td>", $row["returned"], "</td>";
                echo "<td>", $row["username"], "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else {
            echo "<p>No available information for Reports of $month_name - $year.</p>";
        }
    }
    mysqli_close($connection);
}