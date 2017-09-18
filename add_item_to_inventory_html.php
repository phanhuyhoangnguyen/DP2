<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Item to Inventory</title>
    <script type="text/javascript" src="javascript/add_item_to_inventory.js"></script>
</head>
<body>
<form id="add_item_to_inventory" onsubmit="return add_item_to_inventory();">
    <fieldset>
        <legend>
            Add Item to Inventory
        </legend>

        <label for="inv_itemID">ItemID: </label>
        <!-- <input type="text" id="inv_itemID" name="inv_itemID"/><br/> -->

        <select id="inv_itemID" name="inv_itemID">
            <?php
            /*connect database*/
            error_reporting(0);
            $connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
            $item_table = "Item";
            @mysqli_select_db($connection, $item__table);
            $itm_query = "SELECT itemID, CONCAT('(',itemID,') - ',item_name) AS itm_full FROM $item_table ORDER BY itemID ASC";
            $list_item = mysqli_query($connection, $itm_query);
            echo '<option value="">Click to select</option>';
            while ($row = $list_item->fetch_assoc())
            {
                unset($itm);
                $itm = $row['itemID'];
                $itm_full = $row['itm_full'];
                echo '<option value="'.$itm.'">'.$itm_full.'</option>';
            }
            mysqli_close($connection);
            ?>
        </select><br/>

        <label for="inv_quantity">Quantity: </label>
        <input type="text" id="inv_quantity" name="inv_quantity"/><br/>

        <label for="inv_purchased_price">Purchased Price: </label>
        <input type="text" id="inv_purchased_price" name="inv_purchased_price"/><br/>

        <label for="inv_selling_price">Selling Price: </label>
        <input type="text" id="inv_selling_price" name="inv_selling_price"/>

        <!-- total cost = purchased_price * quantity -->
        <input type="hidden" id="inv_total_cost" name="inv_total_cost"/>
        <!-- latest_update = date() -->
        <input type="hidden" id="inv_latest_update" name="inv_latest_update"/>

        <!-- Assign username with username of logged in user -->
        <input type="hidden" id="inv_username" name="inv_username"/>

        <br/><button type="submit" value="add_item_to_inventory">Add Item to Inventory</button>
        <div id="echo_add_item_to_inventory"></div>
    </fieldset>
</form>
</body>
</html>