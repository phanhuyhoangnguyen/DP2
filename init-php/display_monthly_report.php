<!DOCTYPE html>
<html lang="en">
<head>
    <title>Friendly Pharmacy | Sale Reporting and Prediction System</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Sale Reporting and Prediction System"/>
    <meta name="keywords" content="Sale, Report, Predict, System, Pharmacy"/>
    <meta name="author" content="Phan Huy Hoang Nguyen"/>

    <link href="resources/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="resources/css/layout.css" rel="stylesheet" type="text/css"/>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- References to external responsive CSS file -->
    <link href="resources/css/responsive_desktop.css" rel="stylesheet" media="screen and (max-width: 1919px)"/>
    <link href="resources/css/responsive_tabletandmobile.css" rel="stylesheet" media="screen and (max-width: 680px)"/>
</head>

<body>
<header class="headerwrapper">
    <div class="topwrapper">
        <a href="login.php">
            <img src="resources/images/logout_n.png" alt="admin Icon"/>
            <span>Log Out</span></a>

        <a href="">
            <img src="resources/images/admin.png" alt="logout"/>
            <span id="adminIcon">Login as Admin</span>
        </a>
    </div>

    <div id="logoandsearch">
        <a href="index.php"><img src="resources/images/logo.png" alt="WatchStyle Logo" title="Home - WatchStyle"/></a>

        <div class="searchwrapper">
            <form>
                <input type="text" name="search" placeholder="Search for Products/Brand"/>
            </form>
        </div>
    </div>
</header>


<nav>
    <ul>
        <li id="active"><a href="http://pharmacy.westudyit.com/manage.php"><img src="resources/images/home.png"/><span>Home</span></a></li>
        <li><a href=""><img src="resources/images/notification.png"/><span>Notification</span></a></li>
        <li><a href=""><img src="resources/images/setting.png"/><span>Setting</span></a></li>
    </ul>
</nav>

<div class="stylequote">
    <p>Sale Report</p>
</div>

<form method="post" class="row" action="">
    <!--<select id="month_select" name="month_select" onchange="if(this.value != 0) {this.form.submit();}" class="boxform">-->

</form>

<div id="horizontalbar1">
    <ul >
        <li><a href="reportdaily.php"><span id="icon1">Daily</span></a></li>
        <li><a href="reportweekly.php"><span id="icon2">Weekly</span></a></li>
        <li class="active"><a href="display_monthly_report.php"><span id="icon3">Monthly</span></a></li>
    </ul>
</div>

<div id="horizontalbar2">
    <ul >
        <li><a href=""><span id="popular">Popular Product</span></a></li>
        <li><a href=""><span id="chart">Chart View</span></a></li>
        <li><a href=""><span id="export">Export File</span></a></li>
        <li><a href=""><span id="predict">Predict Sale</span></a></li>
    </ul>
</div>

<?php
/**
 * User: phanHuyHoangNguyen
 * Date: 9/9/17
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "Records";
@mysqli_select_db($connection, $table);
/*events after button "Show" is clicked*/

/*Check for database connection*/
if (!$connection) {
    echo "<script type='text/javascript'>";
    /*Error Message@*/
    echo "alert('Database connection failure');";
    echo "</script>";
}
/*this will be executed when the time are selected*/
else if(isset($_POST["time_select"])) {

    /*Extract values from drop-down form*/
    $month = mysqli_real_escape_string($connection, $_POST["month_select"]);
    $year = mysqli_real_escape_string($connection, $_POST["year_select"]);
    $view = mysqli_real_escape_string($connection, $_POST["view_select"]);

    /*Display the error message, when user haven't choose the valid time*/
    if ($month == 0 || $year == 0)
        echo "Please Select Month and Year";

    /*Executed if user want to view Sale by Item*/
    else if ($view == "item_view") {

        /*Query to retrieve info from database*/
        $v_item_query = "SELECT REC.itemID, ITM.item_name, CAT.category_name, INV.quantity AS stock_count, COUNT(REC.itemID) AS TOTAL_SALE,
                          INV.selling_price, INV.purchased_price, SUM(REC.sold_quantity) AS TOTAL_ITEM, SUM(REC.revenue) AS TOTAL_REV, 
                          SUM(REC.profit) AS TOTAL_PROFIT, INV.total_cost FROM Records REC 
                          INNER JOIN Inventory INV ON INV.itemID = REC.itemID 
                          INNER JOIN Item ITM ON ITM.itemID = INV.itemID
                          INNER JOIN Category CAT ON ITM.categoryID = CAT.categoryID
                          WHERE MONTH(date)='$month' AND YEAR(date)='$year' GROUP BY REC.itemID ";
        $result_item = mysqli_query($connection, $v_item_query);

       if ($result_item -> num_rows > 0) {
           echo "<p class='stylequote'>Report</p>\n";
           echo "<table id='tableReport'>";
            echo "<tr>"
                . "<th scope=\"col\">ItemID</th>"
                . "<th scope=\"col\">Item Name</th>"
                . "<th scope=\"col\">Category</th>"
                . "<th scope=\"col\">Stock Count</th>"
                . "<th scope=\"col\">Selling Price</th>"
                . "<th scope=\"col\">Purchased Price</th>"
                . "<th scope=\"col\">Total Sales Quantity</th>"
                . "<th scope=\"col\">Total Item Sold</th>"
                . "<th scope=\"col\">Total Revenue</th>"
                . "<th scope=\"col\">Total Cost</th>"
                . "<th scope=\"col\">Total Profit</th>"
                . "</tr>";
            while ($row = mysqli_fetch_assoc($result_item)) {
                echo "<tr>";
                echo "<td>", $row["itemID"], "</td>";
                echo "<td>", $row["item_name"], "</td>";
                echo "<td>", $row["category_name"], "</td>";
                echo "<td>", $row["stock_count"], "</td>";
                echo "<td>", $row["selling_price"], "</td>";
                echo "<td>", $row["purchased_price"], "</td>";
                echo "<td>", $row["TOTAL_SALE"], "</td>";
                echo "<td>", $row["TOTAL_ITEM"], "</td>";
                echo "<td>", $row["TOTAL_REV"], "</td>";
                echo "<td>", $row["total_cost"], "</td>";
                echo "<td>", $row["TOTAL_PROFIT"], "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "There is no record!";
        }
    }
        /*if the user want to View Sale Report By Day*/
    else {

        /*Retrieve Sale data by Date*/
        $v_date_query = "SELECT date, SUM(sold_quantity) AS TOTAL_SALE, SUM(revenue) AS TOTAL_REV, SUM(profit) AS TOTAL_PROFIT, 
                        (SUM(revenue) - SUM(profit)) AS TOTAL_COST, username FROM Records WHERE MONTH(date)='$month' AND YEAR(date)
                        ='$year' GROUP BY date ";

        $total_query = "SELECT SUM(sold_quantity) AS SUM_TOTAL_SALE, SUM(revenue) AS SUM_TOTAL_REV, SUM(profit) AS SUM_TOTAL_PROFIT, 
                        (SUM(revenue) - SUM(profit)) AS SUM_TOTAL_COST FROM Records WHERE MONTH(date)='$month' AND YEAR(date)='$year'";

        $result_date = mysqli_query($connection, $v_date_query);
        $result_total = mysqli_query($connection, $total_query);

        if ($result_date -> num_rows > 0) {
            echo "<p class='stylequote'>Report</p>\n";
            echo "<table id='tableReport'>";
            echo "<tr>"
                . "<th scope=\"col\">Date</th>"
                . "<th scope=\"col\">Total Sale Quanity</th>"
                . "<th scope=\"col\">Total Revenue</th>"
                . "<th scope=\"col\">Total Cost</th>"
                . "<th scope=\"col\">Total Profit</th>"
                . "<th scope=\"col\">Cashier</th>"
                . "</tr>";

            while ($row = mysqli_fetch_assoc($result_date)) {
                echo "<tr>";
                echo "<td>", $row["date"], "</td>";
                echo "<td>", $row["TOTAL_SALE"], "</td>";
                echo "<td>", $row["TOTAL_REV"], "</td>";
                echo "<td>", $row["TOTAL_COST"], "</td>";
                echo "<td>", $row["TOTAL_PROFIT"], "</td>";
                echo "<td>", $row["username"], "</td>";
                echo "</tr>";
            }

            /*Print table footer for total data*/
            while ($row = mysqli_fetch_assoc($result_total)) {
                echo "<tr>";
                echo "<td>Total</td>";
                echo "<td>", $row["SUM_TOTAL_SALE"], "</td>";
                echo "<td>", $row["SUM_TOTAL_REV"], "</td>";
                echo "<td>", $row["SUM_TOTAL_COST"], "</td>";
                echo "<td>", $row["SUM_TOTAL_PROFIT"], "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "There is no Record!";
        }
    }
}
?>
</body>
</html>