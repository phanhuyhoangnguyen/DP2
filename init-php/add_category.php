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
    /*error messages*/
    $errMsg = "";

    /*defines*/
    $categoryID = mysqli_real_escape_string($connection, $_POST["categoryID"]);
    $category_name = mysqli_real_escape_string($connection, $_POST["category_name"]);

    if ($categoryID=="") {
        $errMsg .= "<p>You must provide an ID for category.</p>";
    } else if (!preg_match("/^[A-Z0-9 ]*$/", $categoryID)) {
        $errMsg .= "<p>Only uppercase alpha letters and numbers allowed for categoryID.</p>";
    }

    if ($category_name=="") {
        $errMsg .= "<p>You must provide category name.</p>";
    } else if (!preg_match("/^[a-zA-Z0-9- ]*$/", $category_name)) {
        $errMsg .= "<p>Only alpha letters, numbers and hyphens allowed for category name.</p>";
    }

    if ($errMsg != "") {
        echo "<script type='text/javascript'>";
        echo "alert('$errMsg');";
        echo "</script>";
    } else {
        $query = "INSERT INTO $table (categoryID, category_name) VALUES ('$categoryID', '$category_name')";
        $add_category = mysqli_query($connection, $query);
    }
}