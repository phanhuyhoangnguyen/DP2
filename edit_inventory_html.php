<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Inventory</title>
    <script type="text/javascript" src="javascript/edit_inventory.js"></script>
</head>
<body>
<form id="update_inventory" onsubmit="return edit_inv_ajax();">
    <fieldset>
        <legend>
            Update Inventory
        </legend>

        <label for="inv_itemID">ItemID: </label>
        <!-- <input type="text" id="inv_itemID" name="inv_itemID"/><br/> -->

        <select id="inv_itemID" name="inv_itemID">
            <?php
                /*connect database*/
                error_reporting(0);
                $connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
                $item_table = "Item";
                $inv_table = "Inventory";
                @mysqli_select_db($connection, $item__table);
                @mysqli_select_db($connection, $inv__table);

                $itm_query = "SELECT inv.itemID, CONCAT('(',inv.itemID,') - ',itm.item_name) AS itm_full FROM $item_table itm,
                              $inv_table inv WHERE itm.itemID = inv.itemID ORDER BY inv.itemID ASC";
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

        <label for="inv_quantity">Adding amount: </label>
        <input type="text" id="inv_quantity" name="inv_quantity"/><br/>

        <label for="inv_purchased_price">Purchased Price: </label>
        <input type="text" id="inv_purchased_price" name="inv_purchased_price"/><br/>

        <label for="inv_selling_price">Selling Price: </label>
        <input type="text" id="inv_selling_price" name="inv_selling_price"/><br/>

        <!-- total cost = purchased_price * quantity -->
        <input type="hidden" id="inv_total_cost" name="inv_total_cost"/>
        <!-- latest_update = date() -->
        <input type="hidden" id="inv_latest_update" name="inv_latest_update"/>

        <label for="inv_update_reason">
            Update Reason:
        </label>
        <select id="inv_update_reason" name="inv_update_reason">
            <option value="">Click to Select</option>
            <option value="update_quantity">Update Quantity Only</option>
            <option value="update_selling_price">Update Selling Prices Only</option>
            <option value="update_both">Update Both Options</option>
        </select>

        <!-- Assign username with username of logged in user -->
        <input type="hidden" id="inv_username" name="inv_username"/>

        <br/><button type="submit" value="edit_inventory">Update Inventory</button>
        <div id="echo_edit_inventory"></div>
    </fieldset>
</form>
</body>
</html>