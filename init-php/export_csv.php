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
@mysqli_select_db($connection, $table);


// Check connection
if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
// After press Export
} else if (isset($_POST["export"]))
{
    $month = mysqli_real_escape_string($connection, $_POST["month_select_csv"]);
    $year = mysqli_real_escape_string($connection, $_POST["year_select_csv"]);
    $view = mysqli_real_escape_string($connection, $_POST["view_select_csv"]);

    //Check whether input is valid
    if($month ==0 || $year ==0)
    {
        echo "<script type='text/javascript'>";
        echo "alert('Invalid Date and View');";
        echo "</script>";
        echo nl2br("Download Failed\nPlease go back to previous page.");
    }
    else 
    {
       $result_total=null;

        // View By Item
        if($view == "item_view")
        {
            $a =  array('itemID','Item Name','Category','Stock Count','Selling Price','Purchased Price',
                        'Total Sales Quantity','Total Item Sold','Total Revenue','Total Cost','Total Profit');
                        
            $query = "SELECT REC.itemID, ITM.item_name, CAT.category_name, INV.quantity , INV.selling_price,
                             INV.purchased_price,COUNT(REC.itemID), SUM(REC.sold_quantity) , SUM(REC.revenue) , 
                             INV.total_cost ,  SUM(REC.profit)
                      FROM Records REC 

                      INNER JOIN Inventory INV 
                      ON INV.itemID = REC.itemID 
                          
                      INNER JOIN Item ITM 
                      ON ITM.itemID = INV.itemID

                      INNER JOIN Category CAT 
                      ON ITM.categoryID = CAT.categoryID
                      WHERE MONTH(date)='$month' 
                      AND YEAR(date)='$year' 
                      GROUP BY REC.itemID ";
                            
        }
        // View By Date
        else{
            $a = array('Date','Total Sale Quanity','Total Revenue','Total Cost','Total Profit','Cashier');
            $query = "SELECT date, SUM(sold_quantity), SUM(revenue),  (SUM(revenue) - SUM(profit)) , SUM(profit),username 
                      FROM Records 
                      WHERE MONTH(date)='$month' 
                      AND YEAR(date)='$year' 
                      GROUP BY date ";

            //For Total
            $total_query = "SELECT SUM(sold_quantity) , SUM(revenue), SUM(profit) , (SUM(revenue) - SUM(profit)) 
                            FROM Records 
                            WHERE MONTH(date)='$month' 
                            AND YEAR(date)='$year'";
            $result_total = mysqli_query($connection, $total_query);
        }

        $result = mysqli_query($connection, $query);
        //Check whether record exists
        if(!($result -> num_rows >0))
        {
            echo "<script type='text/javascript'>";
            echo "alert('There is no record');";
            echo "</script>";
            echo nl2br("Download Failed\nPlease go back to previous page.");
        }
        else{
            //Create file type csv
            header('Content-Type: text/csv; charset=utf-8');  

            //Make file downloadable and file name is data.csv
            header('Content-Disposition: attachment; filename=data.csv'); 

            //Create a write-only stream that allows write access to the output buffer mechanism
            $output = fopen("php://output", "w");      

            //Write the header
            fputcsv($output,$a);
            //Write the other
            while($row = mysqli_fetch_assoc($result))  
            {  
                fputcsv($output, $row);  
            }  

            // For View By Date only
            if($result_total!=null)
            {
                while($row = array('Total') + mysqli_fetch_assoc($result_total))    
                    fputcsv($output, $row);  
            }
            fclose($output);  
        }
    }
}
?>