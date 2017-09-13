<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Pharmacy</title>
</head>
<body>

<form id="register" method="post" action="register.php">
    <fieldset>
        <legend>
            Register
        </legend>

        <label for="full_name">
            User's Full Name:
        </label>
        <input type="text" id="full_name" name="full_name"/><br/>

        <label for="user_id">
            Username:
        </label>
        <input type="text" id="user_id" name="user_id"/><br/>

        <label for="password">
            User's Passcode:
        </label>
        <input type="password" id="password" name="password"/><br/>

        <label for="confirm_password">
            Confirm Passcode:
        </label>
        <input type="password" id="confirm_password" name="confirm_password"/><br/>

        <label for="email">
            User's Email:
        </label>
        <input type="text" id="email" name="email"/><br/>

        <label for="role">
            User's Role:
        </label>
        <select id="role" name="role">
            <option value="">Click to select</option>
            <option value="manager">Manager</option>
            <option value="staff">Staff</option>
        </select><br/>

        <input type="submit" id="submit_register" name="submit_register" value="Register"/>
    </fieldset>
</form><br/>

<form id="login" method="post" action="login.php">
    <fieldset>
        <legend>
            User Login
        </legend>

        <label for="user_id">
            Username:
        </label>
        <input type="text" id="user_id" name="user_id"/><br/>

        <label for="password">
            Password:
        </label>
        <input type="password" id="password" name="password"/><br/>

        <input type="submit" id="submit_login" name="submit_login" value="Login"/>
    </fieldset>
</form><br/>

<form id="logout" method="post" action="logout.php">
    <fieldset>
        <legend>
            Logout
        </legend>
        <input type="submit" id="submit_logout" name="submit_logout" value="Logout"/>
    </fieldset>
</form><br/>

<form id="add_category" method="post" action="add_category.php">
    <fieldset>
        <legend>
            Add Category
        </legend>

        <label for="categoryID">CategoryID: </label>
        <input type="text" id="categoryID" name="categoryID"/><br/>

        <label for="category_name">Category Name: </label>
        <input type="text" id="category_name" name="category_name"/>

        <br/><input type="submit" id="submit_add_category" name="submit_add_category" value="Add Category"/>
    </fieldset>
</form><br/>

<form id='add_item' method='post' action='add_item.php'>
    <fieldset>
        <legend>
            Add Item to Table
        </legend>

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
            $category__table = "Category";
            @mysqli_select_db($connection, $category__table);
            $cat_query = "SELECT categoryID, CONCAT('(',categoryID,') - ',category_name) AS cat_full FROM $category__table ORDER BY categoryID ASC";
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

        <br/><input type='submit' id='submit_add_item' name='submit_add_item' value='Add Item'/>
    </fieldset>
</form><br/>

<form id="add_item_to_inventory" method="post" action="add_item_to_inventory.php">
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
        $item__table = "Item";
        @mysqli_select_db($connection, $category__table);
        $itm_query = "SELECT itemID, CONCAT('(',itemID,') - ',item_name) AS itm_full FROM $item__table ORDER BY itemID ASC";
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

        <input type="hidden" id="inv_update_reason" name="add_new"/>

        <!-- Assign username with username of logged in user -->
        <input type="hidden" id="inv_username" name="inv_username"/>

        <br/><input type="submit" id="submit_add_item_to_inventory" name="submit_add_item_to_inventory" value="Add Item to Inventory"/>
    </fieldset>
</form><br/>

<form id="update_inventory" method="post" action="edit_inventory.php">
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
            $item__table = "Item";
            @mysqli_select_db($connection, $category__table);
            $itm_query = "SELECT itemID, CONCAT('(',itemID,') - ',item_name) AS itm_full FROM $item__table ORDER BY itemID ASC";
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
            <option value="update_quantity">Update Quantity</option>
            <option value="update_selling price">Update Selling Prices</option>
        </select>

        <!-- Assign username with username of logged in user -->
        <input type="hidden" id="inv_username" name="inv_username"/>

        <br/><input type="submit" id="submit_edit_inventory" name="submit_edit_inventory" value="Update Inventory"/>
    </fieldset>
</form><br/>

<form id="add_sale_record" method="post" action="add_sale_record.php">
    <fieldset>
        <legend>
            Add Sale Record
        </legend>

        <label for="rec_itemID">ItemID: </label>
        <!-- <input type="text" id="rec_itemID" name="rec_itemID"/><br/> -->
        <select id="rec_itemID" name="rec_itemID">
        <?php
        /*connect database*/
        error_reporting(0);
        $connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
        $inv__table = "Inventory";
        $item_table = "Item";
        @mysqli_select_db($connection, $category__table);
        @mysqli_select_db($connection, $item_table);
        $itm_query = "SELECT inv.itemID AS ID, CONCAT('(',inv.itemID,') - ',itm.item_name) AS itm_full FROM $inv__table inv, $item_table itm WHERE inv.itemID = itm.itemID ORDER BY inv.itemID ASC";
        $list_item = mysqli_query($connection, $itm_query);
        echo '<option value="">Click to select</option>';
        while ($row = $list_item->fetch_assoc())
        {
            unset($itm);
            $itm = $row['ID'];
            $itm_full = $row['itm_full'];
            echo '<option value="'.$itm.'">'.$itm_full.'</option>';
        }
        mysqli_close($connection);
        ?>
        </select><br/>

        <!-- Assign rec_date a value of current datetime when the sale record updated -->
        <input type="hidden" id="rec_date" name="rec_date"/>

        <label for="rec_quantity">Sold Quantity: </label>
        <input type="text" id="rec_quantity" name="rec_quantity"/>

        <!-- Calculated by multiple selling_price of the item with its sold quantity  -->
        <input type="hidden" id="rec_revenue" name="rec_revenue"/>

        <!-- Calculated by minus revenue with total cost  -->
        <input type="hidden" id="rec_profit" name="rec_profit"/>

        <!-- Assign username with username of logged in user -->
        <input type="hidden" id="rec_username" name="rec_username"/>

        <br/><input type="submit" id="submit_add_sale_record" name="submit_add_sale_record" value="Add Record"/>
    </fieldset>
</form><br/>

<form id="display_record" method="post" action="display_records.php">
    <fieldset>
        <legend>
            Display sale records
        </legend>
        <input type="submit" id="display_sale_record" name="display_sale_record" value="Display Sale Records"/>
    </fieldset>
</form><br/>

<form id="display_inventory" method="post" action="display_inventory.php">
    <fieldset>
        <legend>
            Display Inventory
        </legend>
        <input type="submit" id="display_inv" name="display_inv" value="Display Inventory"/>
    </fieldset>
</form><br/>

<form id="display_monthly_report" method="post" action="display_monthly_report.php">
    <fieldset>
        <legend>
            Display Monthly Report
        </legend>
        <select id="month_select" name="month_select" class="boxform">
            <option value="0">Please Select Month</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7" select = "selected">July</option>
            <option value="8">August</option>
            <option value="9" >September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>

        <!--<select id="year_select" name="year_select" onchange="if(this.value != 0) {this.form.submit();}" class="boxform">-->
        <select id="year_select" name="year_select" class="boxform">
            <option value="0">Please Select Year</option>
            <option value="2016">2016</option>
            <option value="2017" select="selected">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
        </select>

        <select id="view_select" name="view_select" class="boxform">
            <option value="date_view" select="selected">View By Date</option>
            <option value="item_view">View By Item</option>
        </select>

        <input type="submit" id="time_select" name="time_select" value="Display Monthly Report"/>
    </fieldset>
</form><br/>

<form id="export_csv" method="post" action="export_csv.php">
    <fieldset>
        <legend>
            Export to CSV File
        </legend>
        <!-- Select Month   >-->
        <select id="month_select_csv" name="month_select_csv" class="boxform">
            <option value="0">Please Select Month</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7" select = "selected">July</option>
            <option value="8">August</option>
            <option value="9" >September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
        <!-- Select Year   >-->
        <select id="year_select_csv" name="year_select_csv" class="boxform">
            <option value="0">Please Select Year</option>
            <option value="2016">2016</option>
            <option value="2017" select="selected">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
        </select>
        <!-- Select View   >-->
        <select id="view_select_csv" name="view_select_csv" class="boxform">
            <option value="date_view" select="selected">View By Date</option>
            <option value="item_view">View By Item</option>
        </select>

        <br/><br/><input type="submit" id="export" name="export" value="Export to CSV File"/>
    </fieldset>
</form>

</body>
</html>