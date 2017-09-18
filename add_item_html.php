<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Item</title>
    <script type="text/javascript" src="javascript/add_item.js"></script>
</head>
<body>
<form id='add_item' onsubmit="return add_item();">
    <fieldset>
        <legend>Add Item to Table</legend>

        <label for='itemID'>ItemID: </label>
        <input type='text' id='itemID' name='itemID'/><br/>

        <label for='item_name'>Item Name: </label>
        <input type='text' id='item_name' name='item_name'/><br/>

        <label for="item_category">CategoryID: </label>
        <select name='item_category' id='item_category'>

            <?php
            /*connect database*/
            error_reporting(0);
            $connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
            $category_table = "Category";
            @mysqli_select_db($connection, $category__table);
            $cat_query = "SELECT categoryID, CONCAT('(',categoryID,') - ',category_name) AS cat_full FROM $category_table ORDER BY categoryID ASC";
            $list_category = mysqli_query($connection, $cat_query);
            echo '<option value="">Click to select</option>';
            while ($row = $list_category->fetch_assoc())
            {
                unset($cat);
                $cat = $row['categoryID'];
                $cat_full = $row['cat_full'];
                echo '<option value="'.$cat.'">'.$cat_full.'</option>';
            }
            mysqli_close($connection);
            ?>

        </select>

        <br/><button type='submit' value='add_item'>Add Item</button>
        <div id="echo_add_item"></div>
    </fieldset>
</form>
</body>
</html>