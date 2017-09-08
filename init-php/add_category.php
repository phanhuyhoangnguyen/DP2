<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/8/17
 * Time: 8:09 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "Category";
@mysqli_select_db($connection, $table);
/*events after button "Show" is clicked*/

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["submit_add_category"]))
{
    /*defines*/
    $categoryID = mysqli_real_escape_string($connection, $_POST["categoryID"]);
    $category_name = mysqli_real_escape_string($connection, $_POST["category_name"]);

    $query = "INSERT INTO $table (categoryID, category_name) VALUES ('$categoryID', '$category_name')";
    $add_category = mysqli_query($connection, $query);
}