<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Sale Records</title>
</head>
<body>
<form id="add_sale_record" method="post" action="php/add_sale_record.php">
    <fieldset>
        <legend>
            Add Sale Record
        </legend>

        <?php
            /*connect database*/
            error_reporting(0);
            $connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
            $inv_table = "Inventory";
            $item_table = "Item";
            $cat_table = "Category";
            @mysqli_select_db($connection, $inv__table);
            @mysqli_select_db($connection, $item_table);
            @mysqli_select_db($connection, $cat_table);
            session_start();

            $cat_query = "SELECT cat.categoryID AS categoryID,
                          CONCAT(cat.category_name,' (',itm.categoryID,')') AS cat_full FROM $cat_table cat,
                          $inv_table inv, $item_table itm WHERE itm.itemID = inv.itemID AND cat.categoryID = itm.categoryID
                          GROUP BY itm.categoryID ORDER BY cat.categoryID ASC";

            $list_category = mysqli_query($connection, $cat_query);
            $numbers = mysqli_num_rows($list_category);
            $i = 1;
            $listing = array();
            echo "<ul>";
            while ($row = $list_category->fetch_assoc())
            {
                unset($cat);
                $cat = $row['categoryID'];
                $cat_full = $row['cat_full'];
                if ($i == 1) {
                    echo '<li>' . $cat_full . '</li>';
                } else
                {
                    echo '<br/><li>' . $cat_full . '</li>';
                }
                $itm_query = "SELECT inv.itemID AS ID, CONCAT(itm.itemID,' - ',itm.item_name) AS itm_full FROM $inv_table inv, $item_table itm, $cat_table cat WHERE inv.itemID = itm.itemID AND itm.categoryID = cat.categoryID AND itm.categoryID = '$cat' ORDER BY itm.categoryID ASC";
                $list_item = mysqli_query($connection, $itm_query);
                while ($row = $list_item->fetch_assoc()) {
                    unset($itm);
                    $itm = $row['ID'];
                    $itm_full = $row['itm_full'];
                    $listing[] = "$itm";
                    echo "<input type='checkbox' name='cart_$itm' id='cart_$itm' value='$itm'>" . $itm_full . "</input><label for='quantity_$m'> ------------ Quantity: </label><input type='text' name='quantity_$itm' id='quantity_$itm' size='5'/><br/>";
                }
                $i++;
            }
            echo "</ul>";
            $_SESSION["listing"] = $listing;
            mysqli_close($connection);
        ?>

        <!-- Assign rec_date a value of current datetime when the sale record updated -->
        <input type="hidden" id="rec_date" name="rec_date"/>

        <!-- Calculated by multiple selling_price of the item with its sold quantity  -->
        <input type="hidden" id="rec_revenue" name="rec_revenue"/>

        <!-- Calculated by minus revenue with total cost  -->
        <input type="hidden" id="rec_profit" name="rec_profit"/>

        <!-- Assign username with username of logged in user -->
        <input type="hidden" id="rec_username" name="rec_username"/>

        <input type="submit" id="submit_add_sale_record" name="submit_add_sale_record" value="Add Record"/>
    </fieldset>
</form>
</body>
</html>