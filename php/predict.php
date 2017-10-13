<?php
/**
 * Created by PhpStorm.
 * User: Viet
 * Date: 10/12/2017
 * Time: 4:13 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "root", "root", "westudyi_pharmacy");

$user_table = "user";
$category_table = "category";
$item_table = "item";
$inventory_table = "inventory";
$records_table = "records";
$record_items_table = "record_items";
/*For statistics purposes*/
$tdist_table = "tdist";
$zleft_table = "zleft";
$zright_table = "zright";

@mysqli_select_db($connection, $user_table);
@mysqli_select_db($connection, $category_table);
@mysqli_select_db($connection, $item_table);
@mysqli_select_db($connection, $inventory_table);
@mysqli_select_db($connection, $records_table);
@mysqli_select_db($connection, $record_items_table);
/*For statistics purposes*/
@mysqli_select_db($connection, $tdist_table);
@mysqli_select_db($connection, $zleft_table);
@mysqli_select_db($connection, $zright_table);

session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if ($_SESSION["username"] == "") {
                echo "<p>You must login to view the inventory.</p>";
} else if (isset($_POST["submit"])) {
     //echo "Hello World";
    $errMsg = "";

    $day_name = mysqli_escape_string($connection, $_POST["day"]);

    $listing_query = "SELECT DAYNAME(r.date) as day, CONCAT(YEAR(r.date),'-',MONTH(r.date),'-',DAY(r.date)) as date,
                    COUNT(DISTINCT r.saleID) as total_sales,
                    COUNT(DISTINCT i.itemID) as total_kinds_of_sold_products,
                    SUM(i.sold_quantity) as total_sold, SUM(i.profit) AS total_profit, SUM(i.revenue) as total_revenue
                    FROM records r, record_items i
                    WHERE r.saleID = i.saleID GROUP BY DAY(r.date), MONTH(r.date), YEAR(r.date)";

    $listing_day_query = "SELECT DAYNAME(r.date) as day, CONCAT(YEAR(r.date),'-',MONTH(r.date),'-',DAY(r.date)) as date,
                    COUNT(DISTINCT r.saleID) as total_sales,
                    COUNT(DISTINCT i.itemID) as total_kinds_of_sold_products,
                    SUM(i.sold_quantity) as total_sold, SUM(i.profit) AS total_profit, SUM(i.revenue) as total_revenue
                    FROM records r, record_items i
                    WHERE r.saleID = i.saleID AND DAYNAME(r.date)='$day_name' GROUP BY DAY(r.date), MONTH(r.date), YEAR(r.date), DAYNAME(r.date)";
    $count_day_query = "SELECT DAYNAME(date) as day, COUNT(DISTINCT DAY(date), MONTH(date), YEAR(date)) as total_occ
                    FROM records GROUP BY DAYNAME(date)";
    $count_single_day_query = "SELECT DAYNAME(date) as day, COUNT(DISTINCT DAY(date), MONTH(date), YEAR(date)) as total_occ
                    FROM records WHERE DAYNAME(date)='$day_name'";

    $result_listing = mysqli_query($connection, $listing_query);
    $result_day_listing = mysqli_query($connection, $listing_day_query);
    $result_count_day = mysqli_query($connection, $count_day_query);
    $result_count_single_day = mysqli_query($connection, $count_single_day_query);

    function Calculating($option) {

        $final_results = array();

        $array_total_sales = array();
        $array_total_kinds_of_sold_products = array();
        $array_total_sold = array();
        $array_total_profit = array();
        $array_total_revenue = array();

        while ($row = mysqli_fetch_assoc($option)) {
            $array_total_sales[] = $row["total_sales"];
            $array_total_kinds_of_sold_products[] = $row["total_kinds_of_sold_products"];
            $array_total_sold[] = $row["total_sold"];
            $array_total_profit[] = $row["total_profit"];
            $array_total_revenue[] = $row["total_revenue"];
        }

        $results_sales = array();
        $results_kinds = array();
        $results_sold = array();
        $results_profit = array();
        $results_revenue = array();

        $all_arrays = array($array_total_sales, $array_total_kinds_of_sold_products, $array_total_sold, $array_total_profit, $array_total_revenue);

        for ($t = 0; $t < count($all_arrays); $t++) {

            $average = round((array_sum($all_arrays[$t]) / count($all_arrays[$t])), 4);
            $variance = 0;
            for ($i = 0; $i < count($all_arrays[$t]); $i++) {
                $variance += round(((($all_arrays[$t][$i] - $average) * ($all_arrays[$t][$i] - $average)) / count($all_arrays[$t])), 4);
            }
            $deviation = round(sqrt($variance), 4);

            switch($t) {
                case 0:
                    $results_sales[] = $average;
                    $results_sales[] = $variance;
                    $results_sales[] = $deviation;
                    break;
                case 1:
                    $results_kinds[] = $average;
                    $results_kinds[] = $variance;
                    $results_kinds[] = $deviation;
                    break;
                case 2:
                    $results_sold[] = $average;
                    $results_sold[] = $variance;
                    $results_sold[] = $deviation;
                    break;
                case 3:
                    $results_profit[] = $average;
                    $results_profit[] = $variance;
                    $results_profit[] = $deviation;
                    break;
                case 4:
                    $results_revenue[] = $average;
                    $results_revenue[] = $variance;
                    $results_revenue[] = $deviation;
                    break;
            }
        }

        $final_results[] = $results_sales;
        $final_results[] = $results_kinds;
        $final_results[] = $results_sold;
        $final_results[] = $results_profit;
        $final_results[] = $results_revenue;

        return $final_results;
    }
    function SumDay($option) {
        $total_days = array();
        while ($row = mysqli_fetch_assoc($option)) {
            $total_days[] = $row["total_occ"];
        }
        return $sum_days = array_sum($total_days);
    }
    function Col_name($col){
        $exact_col = "";
        switch ($col) {
            case '0':
                $exact_col = "one";
                break;
            case '1':
                $exact_col = "two";
                break;
            case '2':
                $exact_col = "three";
                break;
            case '3':
                $exact_col = "four";
                break;
            case '4':
                $exact_col = "five";
                break;
            case '5':
                $exact_col = "six";
                break;
            case '6':
                $exact_col = "seven";
                break;
            case '7':
                $exact_col = "eight";
                break;
            case '8':
                $exact_col = "nine";
                break;
            case '9':
                $exact_col = "ten";
                break;
            default:
                $exact_col = "one";
                break;
        }
        return $exact_col;
    }

    if ($day_name == "") {
        $errMsg .= "Please complete the inputs.";
    }

    if ($errMsg != "") {
        echo "<p>$errMsg</p>";
    } else {
        echo "<p>"."Forecast results for next ".$day_name."</p>";
        $result_sum_days = array();
        $grand_results = array();
        $deviation_of_mean_results = array();

        $option = array($result_listing, $result_day_listing);
        $option_2 = array($result_count_day, $result_count_single_day);

        for ($t = 0; $t < count($option); $t++) {
            $result_sum_days[] = SumDay($option_2[$t]);
        }

        for ($i = 0; $i < count($option); $i++) {
            $grand_results[] = Calculating($option[$i]);
        }

        $deviation_mean = array();
        for ($z = 0; $z < count($grand_results); $z++ /*2*/) {
            $sd_mean = array();
            for ($u = 0; $u < count($grand_results[$z]); $u++) {
                $sd_mean[] = $grand_results[$z][$u][2] / sqrt($result_sum_days[$z]);
            }
            $deviation_mean[] = $sd_mean;
        }
        $avg_array = array();
        for ($z = 0; $z < count($grand_results); $z++ /*2*/) {
            $avg = array();
            for ($u = 0; $u < count($grand_results[$z]); $u++) {
                $avg[] = $grand_results[$z][$u][0];
            }
            $avg_array[] = $avg;
        }

        $observed_dates_results = array($avg_array[0], $deviation_mean[0]);
        $low_mean_dates_results = array();
        $high_mean_dates_results = array();
        $avg_mean_dates_results = array();
        for ($u = 0; $u < count($avg_array[0]); $u++) {
            $low_mean_dates_results[] = $observed_dates_results[0][$u] - $observed_dates_results[1][$u];
            $high_mean_dates_results[] = $observed_dates_results[0][$u] + $observed_dates_results[1][$u];
            $avg_mean_dates_results[] = $observed_dates_results[0][$u];
        }

        $observed_day_results = array($avg_array[1], $deviation_mean[1]);
        $low_mean_days_results = array();
        $high_mean_days_results = array();
        $avg_mean_days_results = array();
        for ($u = 0; $u < count($avg_array[1]); $u++) {
            $low_mean_days_results[] = $observed_day_results[0][$u] - $observed_day_results[1][$u];
            $high_mean_days_results[] = $observed_day_results[0][$u] + $observed_day_results[1][$u];
            $avg_mean_days_results[] = $observed_day_results[0][$u];
        }

        $low = array();
        $high = array();
        $mean = array();
        for ($i = 0; $i < count($low_mean_dates_results); $i++) {
            $low[] = round((($low_mean_dates_results[$i] + $low_mean_days_results[$i]) / 2), 0);
            $high[] = round((($high_mean_dates_results[$i] + $high_mean_days_results[$i]) / 2), 0);
            $mean[] = round((($avg_mean_dates_results[$i] + $avg_mean_days_results[$i]) /2), 0);
        }

        $low_z_index = array();
        $high_z_index = array();
        for ($i = 0; $i < count($deviation_mean[1]); $i++) {
            $low_z_index[] = round((($mean[$i] - $low[$i]) / $deviation_mean[1][$i]), 2);
            $high_z_index[] = round((($high[$i] - $mean[$i]) / $deviation_mean[1][$i]), 2);
        }

        $percent_low_more = array();
        for ($i = 0; $i < count($low_z_index); $i++) {
            $z_col = str_split($low_z_index[$i], 3);
            if ($low_z_index != "0") {
                $z_index = $z_col[0];
                $col = $z_col[1];
            } else {
                $z_index = '0';
                $col = '0';
            }
            $exact_col_left = Col_name($col);
            $zleft_query = "SELECT $exact_col_left FROM zleft WHERE CAST(z as CHAR) = CAST($z_index as CHAR)";
            $z_score_fetch = $connection->query($zleft_query)->fetch_assoc();
            $percent_low_more[] = $z_score = (1 - $z_score_fetch[$exact_col_left]) * 100;
        }

        $percent_high_more = array();
        for ($i = 0; $i < count($high_z_index); $i++) {
            $z_col_right = str_split($high_z_index[$i], 3);
            if ($high_z_index != "0") {
                $z_index_right = $z_col_right[0];
                $col_right = $z_col_right[1];
            } else {
                $z_index_right = '0';
                $col_right = '0';
            }
            $exact_col_right = Col_name($col_right);
            $zright_query = "SELECT $exact_col_right FROM zright WHERE CAST(z as CHAR) = CAST($z_index_right as CHAR)";
            $z_right_score_fetch = $connection->query($zright_query)->fetch_assoc();
            $percent_high_more[] = ($z_right_score_fetch[$exact_col_right]) * 100;
        }

        $predict_results = array();
        for ($i = 0; $i < count($percent_low_more); $i++) {
            if ($percent_low_more[$i] < $percent_high_more[$i]) {
                $predict_results[] = $high[$i];
            } else {
                $predict_results[] = $low[$i];
            }
        }

        echo "<table border='1px'>";
        echo "<tr>"
            . "<th scope=\"col\">Total Sales</th>"
            . "<th scope=\"col\">Total Kinds of Sold Products</th>"
            . "<th scope=\"col\">Total Sold</th>"
            . "<th scope=\"col\">Total Profit</th>"
            . "<th scope=\"col\">Total Revenue</th>"
            . "</tr>";
            echo "<tr>";
            echo "<td>", $predict_results[0], "</td>";
            echo "<td>", $predict_results[1], "</td>";
            echo "<td>", $predict_results[2], "</td>";
            echo "<td>", $predict_results[3]." (without Linear Regression)", "</td>";
            echo "<td>", $predict_results[4]." (without Linear Regression)", "</td>";
            echo "</tr>";
        echo "</table>";
    }
    mysqli_close($connection);
}