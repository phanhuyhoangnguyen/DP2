<?php
/**
 * Created by DonDave on 04/10/17.
 */

 /* Include the `fusioncharts.php` file that contains functions	to embed the charts. */
 include("fusioncharts.php");

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
    $month = mysqli_real_escape_string($connection, $_POST["select_month"]);
    $year = mysqli_real_escape_string($connection, $_POST["select_year"]);
    $option = mysqli_real_escape_string($connection, $_POST["option"]);


    //if the view is day
    if ($option == "daily_type") 
    {
        //if the year, month or date is empty
        if ($year == "" || $month == "" || $date== "")
        { $errMsg = "<p>You must input time, in the following format: dd/mm/yyyy</p>";}
        else 
        {
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
            $jsonArray = array();
            //print data if the query return results
            if ($daily_popular_item_result->num_rows > 0) {
                //Converting the results into an associative array
                while($row = $daily_popular_item_result->fetch_assoc()) {
                  $jsonArrayItem = array();
                  $jsonArrayItem['label'] = $row['item_name'];
                  $jsonArrayItem['value'] = $row['sold_quantity'];
                  //append the above created object into the main array.
                  array_push($jsonArray, $jsonArrayItem);
                }
              }
            else 
            {   
                  $errMsg = "No information available for $date, $month, $year";
            }
           
        } 
    }
    //if the view is month
    else if ($option == "monthly_type") {
        if ($year == "" || $month == "")
            $errMsg = "<p>You must input time, in the following format: mm/yyyy</p>";
        else 
        {
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

            $jsonArray = array();
            //print data if the query return results
            if ($month_popular_item_result->num_rows > 0) 
            {
                //Converting the results into an associative array
                while($row = $month_popular_item_result->fetch_assoc()) {
                  $jsonArrayItem = array();
                  $jsonArrayItem['label'] = $row['item_name'];
                  $jsonArrayItem['value'] = $row['sold_quantity'];
                  //append the above created object into the main array.
                  array_push($jsonArray, $jsonArrayItem);
                }
            }
            else $errMsg = "No information available for $month, $year";
        }
            
    }
    
    //the view is unselected
    else{
        $errMsg = "<p>You must select the view</p>";
    }
    $_SESSION['data'] = $jsonArray;
    echo $errMsg;

   
    mysqli_close($connection);
}