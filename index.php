<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Pharmacy</title>
</head>
<body>

<?php
if ($_SESSION["username"] != "") {
    echo "<h3>Logged user: ".$_SESSION["username"]." "."<form id='logout' method='post' action='php/logout.php'>
            <input type='submit' id='submit_logout' name='submit_logout' value='Logout'/></form>"."</h3>";
} else {
    echo "<h3>Please login to use the system.</h3>";
}
?>

<ul>
    <li><a href="login.html">Login</a></li>
    <li><a href="register.html">Register</a></li>
    <li><a href="add_category.html">Add Category</a></li>
    <li><a href="add_item_html.php">Add Item</a></li>
    <li><a href="add_item_to_inventory_html.php">Add Item to Inventory</a></li>
    <li><a href="edit_inventory_html.php">Update Inventory</a></li>
    <li><a href="add_sale_records_html.php">Add Sale Records</a></li>
    <li><a href="return.html">Process Return</a></li>
    <li><a href="display_inventory.html">Display Inventory</a></li>
    <li><a href="display_records.html">Display Sale Records</a></li>
    <li><a href="display_month_report.html">Display Monthly Reports</a></li>
    <li><a href="export_csv.html">Export to CSV file</a></li>
    <li><a href="restock_reminder.html">Restock Reminder</a></li>
</ul>

</body>
</html>