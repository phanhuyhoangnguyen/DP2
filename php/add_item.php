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
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else
{
    if ($_SESSION["username"] == "")
    {
        echo "<p>You must login to add an item into the system.</p>";
    } else {
        /* Error message */
        $errMsg = "";

        /*defines*/
        $itemID = mysqli_real_escape_string($connection, $_POST["itemID"]);
        $item_name = mysqli_real_escape_string($connection, $_POST["item_name"]);
        $item_category = mysqli_real_escape_string($connection, $_POST["item_category"]);


        /* validates categoryID input */
        //case itemID is empty
        if ($itemID == "") {
            $errMsg .= "<p>You must provide an ID for item.</p>";
        } //case itemID is filled with wrong format
        else if (!preg_match("/^[I][T][M][0-9]{6}$/", $itemID)) {
            $errMsg .= "<p>Item ID must follow this form: ITMxxxxxx where xxxxxx is item's index number.</p>";
        } else if (preg_match("/^[I][T][M][0]{6}$/", $itemID)) {
            $errMsg .= "<p>Index number for item cannot be zero.</p>";
        }

        /* validates duplicate itemID */
        $search_duplicate_itemID_query = "SELECT DISTINCT itemID FROM $table";
        $search_duplicate_itemID = mysqli_query($connection, $search_duplicate_itemID_query);

        while ($exist_itemID = $search_duplicate_itemID->fetch_assoc()) {
            $added_itemID = $exist_itemID['itemID'];
            if ($added_itemID == $itemID) {
                $errMsg .= "<p>$itemID is already added. Please try again!</p>";
            }
        }

        /* validates item_name */
        //case item_name is empty
        if ($item_name == "") {
            $errMsg .= "<p>You must provide item name.</p>";
        } //case item_name is filled with wrong format
        else if (!preg_match("/^[a-zA-Z0-9- ]*$/", $item_name)) {
            $errMsg .= "<p>Only alpha letters, numbers and hyphens allowed for category name.</p>";
        }

        /* validate item's associated category */
        if ($item_category == "") {
            $errMsg .= "<p>You must select a category for the item.</p>";
        }

        if ($errMsg != "") {
            echo $errMsg;
        } else {
            $query = "INSERT INTO $table (itemID, item_name, categoryID) VALUES ('$itemID', '$item_name', '$item_category')";
            $add_item = mysqli_query($connection, $query);
            echo "<p>Item $itemID added.</p>";
        }
    }
    mysqli_close($connection);
}