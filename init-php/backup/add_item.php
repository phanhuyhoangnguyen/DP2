<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/8/17
 * Time: 9:17 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "Item";
@mysqli_select_db($connection, $table);
/*events after button "Show" is clicked*/

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["submit_add_item"]))
{
    /*defines*/
    $itemID = mysqli_real_escape_string($connection, $_POST["itemID"]);
    $item_name = mysqli_real_escape_string($connection, $_POST["item_name"]);
    $item_category = mysqli_real_escape_string($connection, $_POST["item_category"]);

    $query = "INSERT INTO $table (itemID, item_name, categoryID) VALUES ('$itemID', '$item_name', '$item_category')";
    $add_item = mysqli_query($connection, $query);
}