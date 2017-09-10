<!DOCTYPE html>
<html lang="en">
<head>
    <title>Friendly Pharmacy | Sale Reporting and Prediction System</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Sale Reporting and Prediction System"/>
    <meta name="keywords" content="Sale, Report, Predict, System, Pharmacy"/>
    <meta name="author" content="Phan Huy Hoang Nguyen"/>

    <link href="styles/style.css" rel="stylesheet" type="text/css"/>
    <link href="styles/layout.css" rel="stylesheet" type="text/css"/>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- References to external responsive CSS file -->
    <link href="styles/responsive_desktop.css" rel="stylesheet" media="screen and (max-width: 1919px)"/>
    <link href="styles/responsive_tabletandmobile.css" rel="stylesheet" media="screen and (max-width: 680px)"/>
</head>

<body>
<header class="headerwrapper">
    <div class="topwrapper">
        <a href="login.html">
            <img src="images/logout_n.png" alt="admin Icon"/>
            <span>Log Out</span></a>

        <a href="">
            <img src="images/admin.png" alt="logout"/>
            <span id="adminIcon">Login as Admin</span>
        </a>
    </div>

    <div id="logoandsearch">
        <a href="index.php"><img src="images/logo.png" alt="WatchStyle Logo" title="Home - WatchStyle"/></a>

        <div class="searchwrapper">
            <form>
                <input type="text" name="search" placeholder="Search for Products/Brand"/>
            </form>
        </div>
    </div>
</header>


<nav>
    <ul>
        <li id="active"><a href="home.html"><img src="images/home.png"/><span>Home</span></a></li>
        <li><a href=""><img src="images/notification.png"/><span>Notification</span></a></li>
        <li><a href=""><img src="images/setting.png"/><span>Setting</span></a></li>
    </ul>
</nav>

<div class="stylequote">
    <p>Sale Report</p>
</div>

<form method="post" class="row" action="">
    <!--<select id="month_select" name="month_select" onchange="if(this.value != 0) {this.form.submit();}" class="boxform">-->
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
        <option value="date_view" select="selected">View By Day</option>
        <option value="item_view">View By Item</option>
    </select>

    <input type="submit" id="time_select" name="time_select" value="Display Report"/>
</form>

<div id="horizontalbar1">
    <ul >
        <li><a href="reportdaily.html"><span id="icon1">Daily</span></a></li>
        <li><a href="reportweekly.html"><span id="icon2">Weekly</span></a></li>
        <li class="active"><a href="reportmonthly.php"><span id="icon3">Monthly</span></a></li>
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
        $v_item_query = "SELECT r.itemID, COUNT(r.itemID) AS TOTAL_ITEM, SUM(r.sold_quantity) AS TOTAL_SALE, SUM(r.revenue) AS TOTAL_REV, 
                          SUM(r.profit) AS TOTAL_PROFIT, i.total_cost FROM Records r INNER JOIN Inventory i ON i.itemID = r.itemID WHERE 
                          MONTH(date)='$month' AND YEAR(date)='$year' GROUP BY r.itemID ";
        $result_item = mysqli_query($connection, $v_item_query);

        if (mysql_num_rows($result_item) > 0) {

            echo "<h1>Sale By Item</h1>\n";
            echo "<table border=\"1\">";
            echo "<tr>"
                . "<th scope=\"col\">itemID</th>"
                . "<th scope=\"col\">Total Sales Quantity</th>"
                . "<th scope=\"col\">Total Item Sold</th>"
                . "<th scope=\"col\">Total Revenue</th>"
                . "<th scope=\"col\">Total Cost</th>"
                . "<th scope=\"col\">Total Profit</th>"
                . "</tr>";
            while ($row = mysqli_fetch_assoc($result_item)) {
                echo "<tr>";
                echo "<td>", $row["itemID"], "</td>";
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

        if (mysql_num_rows($result_item) > 0) {
            echo "<h1>Report</h1>\n";
            echo "<table border=\"1\">";
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
        } else
            echo "There is no Record!";
    }
}
?>
</body>
</html>