<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/15/17
 * Time: 8:32 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "Records";
$table_ri = "record_items";
@mysqli_select_db($connection, $table);
@mysqli_select_db($connection, $table_ri);
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else {
    echo $saleID = $_POST["saleid"];
}