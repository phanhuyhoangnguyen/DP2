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
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else {
    if ($_SESSION["username"] == "")
    {
        echo "<p>You must login to add new category into the system.</p>";
    } else {

        /*error messages*/
        $errMsg = "";

        /*defines*/
        $categoryID = mysqli_real_escape_string($connection, $_POST["categoryID"]);
        $category_name = mysqli_real_escape_string($connection, $_POST["category_name"]);

        /* validates categoryID input */
        //case categoryID is empty
        if ($categoryID == "") {
            $errMsg .= "<p>You must provide an ID for category.</p>";
        } //case categoryID is filled with wrong format
        else if (!preg_match("/^[A-Z]{3}[0-9]{4}$/", $categoryID)) {
            $errMsg .= "<p>Category ID must follow this form: ABCxxxx where ABC is first 3 uppercase characters of category name and xxxx is category's index number.</p>";
        } else if (preg_match("/^[A-Z]{3}[0]{4}$/", $categoryID)) {
            $errMsg .= "<p>Index number for category cannot be zero.</p>";
        }

        /* validates duplicate categoryID */
        $search_duplicate_categoryID_query = "SELECT DISTINCT categoryID FROM $table";
        $search_duplicate_categoryID = mysqli_query($connection, $search_duplicate_categoryID_query);

        while ($exist_categoryID = $search_duplicate_categoryID->fetch_assoc()) {
            $added_categoryID = $exist_categoryID['categoryID'];
            if ($added_categoryID == $categoryID) {
                $errMsg .= "<p>$categoryID is already added. Please try again!</p>";
            }
        }

        /* validates category_name */
        //case category_name is empty
        if ($category_name == "") {
            $errMsg .= "<p>You must provide a category name.</p>";
        } //case category_name is filled with wrong format
        else if (!preg_match("/^[a-zA-Z0-9- ]*$/", $category_name)) {
            $errMsg .= "<p>Only alpha letters, numbers and hyphens allowed for category name.</p>";
        }

        /* display error message when it is not empty */
        if ($errMsg != "") {
            echo $errMsg;
        } else {
            $query = "INSERT INTO $table (categoryID, category_name) VALUES ('$categoryID', '$category_name')";
            $add_category = mysqli_query($connection, $query);
            echo "<p>Category $categoryID added.</p>";
        }
    }
    mysqli_close($connection);
}