<?php
/*
/---------------------------------------------------------/
    Task: Generate a monthly sales report as a CSV file
    Date Created: 11 - Sep - 2017
    Author: Don Dave (Duy The Nguyen)
    Last Modified: 02:55am 12 - Sep -2017
 /---------------------------------------------------------/
 */

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

// Check connection
if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
// After press Export
} else if (isset($_POST["export"])) {

    if ($_SESSION["username"] == "")
    {
        echo "<p>You must login to use the system.</p>";
    } else {

        $errMsg = "";

        $month = mysqli_real_escape_string($connection, $_POST["month_select_csv"]);
        $year = mysqli_real_escape_string($connection, $_POST["year_select_csv"]);
        $view = mysqli_real_escape_string($connection, $_POST["view_select_csv"]);

        //Check whether input is valid
        if ($year == "" || $month == "") {
            $errMsg .= "<p>You must select month and year of the report.</p>";
        }

        if ($view == "") {
            $errMsg .= "<p>You must select an option to save the reports.</p>";
        }

    if ($errMsg != "") {
        echo $errMsg;
    } else {

        $query = "";
        $a = "";

        if ($view == "item_view") {
            $a = array('itemID', 'Item Description', 'Selling Price', 'Sold in Number of Sales', 'Total Sold', 'Total Revenue',
                'Total Profit', 'Total Cost', 'Remaining in Stock');

            $query = "SELECT ri.itemID AS itemID, itm.item_name AS item_name, inv.selling_price AS selling_price, COUNT(ri.saleID) AS in_sales,
                  SUM(ri.sold_quantity) AS total_sold, SUM(ri.revenue) AS total_revenue, SUM(ri.profit) AS total_profit, 
                  inv.total_cost AS total_cost, inv.quantity AS remaining 
                  FROM $table r, $table_Ri ri, $inv_table inv, $item_table itm 
                  WHERE r.saleID = ri.saleID AND ri.itemID = inv.itemID AND itm.itemID = inv.itemID 
                  AND YEAR(r.date)='$year' AND MONTH(r.date)='$month' GROUP BY ri.itemID ORDER BY ri.itemID ASC";
        } else {
            $a = array('Total Sales', 'Total Revenue', 'Total Profit', 'Total Cost of Sold Items', 'Number of Kinds of Sold Item', 'Total Sold Items');

            $query = "SELECT COUNT(DISTINCT ri.saleID) AS total_sales, SUM(ri.revenue) AS total_revenue, 
                          SUM(ri.profit) AS total_profit, SUM(DISTINCT inv.total_cost) AS total_cost, 
                          COUNT(DISTINCT ri.itemID) AS total_items, SUM(ri.sold_quantity) AS total_items_sold 
                          FROM $table r, $table_Ri ri, $inv_table inv WHERE ri.saleID = r.saleID AND ri.itemID = inv.itemID 
                          AND YEAR(r.date)='$year' AND MONTH(r.date)='$month' GROUP BY ri.saleID AND YEAR(r.date) AND MONTH(r.date)";
        }

        $result = mysqli_query($connection, $query);

        //Check whether record exists
        if (!($result->num_rows > 0)) {
            echo "<script type='text/javascript'>";
            echo "alert('There is no record');";
            echo "</script>";
            echo nl2br("Download Failed\nPlease go back to previous page.");
        } else {
            //Create file type csv
            header('Content-Type: text/csv; charset=utf-8');

            //Make file downloadable and file name is data.csv
            header('Content-Disposition: attachment; filename=data.csv');

            //Create a write-only stream that allows write access to the output buffer mechanism
            $output = fopen("php://output", "w");

            //Write the header
            fputcsv($output, $a);
            //Write the other
            while ($row = mysqli_fetch_assoc($result)) {
                fputcsv($output, $row);
            }
                fclose($output);
            }
        }
    }
}