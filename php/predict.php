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

$user_table = "User";
$category_table = "Category";
$item_table = "Item";
$inventory_table = "Inventory";
$Records_table = "Records";
$Record_items_table = "Record_items";
/*For statistics purposes*/
$tdist_table = "Tdist";
$zleft_table = "Zleft";
$zright_table = "Zright";

@mysqli_select_db($connection, $user_table);
@mysqli_select_db($connection, $category_table);
@mysqli_select_db($connection, $item_table);
@mysqli_select_db($connection, $inventory_table);
@mysqli_select_db($connection, $Records_table);
@mysqli_select_db($connection, $Record_items_table);
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
} /*else if ($_SESSION["username"] == "") {
                echo "<p>You must login to view the inventory.</p>";
 }*/ else if (isset($_POST["submit"])) {
    //echo "Hello World";
    $errMsg = "";

    $day_name = mysqli_escape_string($connection, $_POST["day"]);

    $listing_query = "SELECT DAYNAME(r.date) as day, CONCAT(YEAR(r.date),'-',MONTH(r.date),'-',DAY(r.date)) as date,
                    COUNT(DISTINCT r.saleID) as total_sales,
                    COUNT(DISTINCT i.itemID) as total_kinds_of_sold_products,
                    SUM(i.sold_quantity) as total_sold, SUM(i.profit) AS total_profit, SUM(i.revenue) as total_revenue
                    FROM Records r, Record_items i
                    WHERE r.saleID = i.saleID GROUP BY DAY(r.date), MONTH(r.date), YEAR(r.date)";

    $listing_day_query = "SELECT DAYNAME(r.date) as day, CONCAT(YEAR(r.date),'-',MONTH(r.date),'-',DAY(r.date)) as date,
                    COUNT(DISTINCT r.saleID) as total_sales,
                    COUNT(DISTINCT i.itemID) as total_kinds_of_sold_products,
                    SUM(i.sold_quantity) as total_sold, SUM(i.profit) AS total_profit, SUM(i.revenue) as total_revenue
                    FROM Records r, Record_items i
                    WHERE r.saleID = i.saleID AND DAYNAME(r.date)='$day_name' GROUP BY DAY(r.date), MONTH(r.date), YEAR(r.date), DAYNAME(r.date)";
    $count_day_query = "SELECT DAYNAME(date) as day, COUNT(DISTINCT DAY(date), MONTH(date), YEAR(date)) as total_occ
                    FROM Records GROUP BY DAYNAME(date)";
    $count_single_day_query = "SELECT DAYNAME(date) as day, COUNT(DISTINCT DAY(date), MONTH(date), YEAR(date)) as total_occ
                    FROM Records WHERE DAYNAME(date)='$day_name'";

    $linear_observed_dates_profit_query = "SELECT i.sold_quantity AS sold, i.profit AS figure FROM Record_items i, Records r
                                            WHERE r.saleID = i.saleID ORDER BY r.saleID ASC";
    $linear_observed_dates_revenue_query = "SELECT i.sold_quantity AS sold, i.revenue AS figure FROM Record_items i, Records r
                                            WHERE r.saleID = i.saleID ORDER BY r.saleID ASC";
    $linear_observed_days_profit_query = "SELECT i.sold_quantity AS sold, i.profit AS figure FROM Record_items i, Records r
                                            WHERE r.saleID = i.saleID AND DAYNAME(r.date)='$day_name'
                                            ORDER BY r.saleID ASC";
    $linear_observed_days_revenue_query = "SELECT i.sold_quantity AS sold, i.revenue AS figure FROM Record_items i, Records r
                                            WHERE r.saleID = i.saleID AND DAYNAME(r.date)='$day_name'
                                            ORDER BY r.saleID ASC";
    $item_summary_query = "SELECT i.itemID as itemID, i.sold_quantity as sold, inv.quantity AS available FROM Record_items i, inventory inv WHERE i.itemID = inv.itemID GROUP BY i.itemID";

    $result_listing = mysqli_query($connection, $listing_query);
    $result_day_listing = mysqli_query($connection, $listing_day_query);
    $result_count_day = mysqli_query($connection, $count_day_query);
    $result_count_single_day = mysqli_query($connection, $count_single_day_query);
    $result_count_single_day = mysqli_query($connection, $count_single_day_query);
    $item_summary_results = mysqli_query($connection, $item_summary_query);

    $linear_observed_dates_profit_results = mysqli_query($connection, $linear_observed_dates_profit_query);
    $linear_observed_dates_revenue_results = mysqli_query($connection, $linear_observed_dates_revenue_query);
    $linear_observed_days_profit_results = mysqli_query($connection, $linear_observed_days_profit_query);
    $linear_observed_days_revenue_results = mysqli_query($connection, $linear_observed_days_revenue_query);

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
                $exact_col = "One";
                break;
            case '1':
                $exact_col = "Two";
                break;
            case '2':
                $exact_col = "Three";
                break;
            case '3':
                $exact_col = "Four";
                break;
            case '4':
                $exact_col = "Five";
                break;
            case '5':
                $exact_col = "Six";
                break;
            case '6':
                $exact_col = "Seven";
                break;
            case '7':
                $exact_col = "Eight";
                break;
            case '8':
                $exact_col = "Nine";
                break;
            case '9':
                $exact_col = "Ten";
                break;
            default:
                $exact_col = "One";
                break;
        }
        return $exact_col;
    }
    function Linear_Regression($option, $x_0) {

        $results = array();
        global $connection;

        $array_x_sold_quantity = array();
        $array_y_figures = array();

        while ($row = mysqli_fetch_assoc($option)) {
            $array_x_sold_quantity[] = $row["sold"];
            $array_y_figures[] = $row["figure"];
        }

        $avg_x = round((array_sum($array_x_sold_quantity)/count($array_x_sold_quantity)),4);
        $avg_y = round((array_sum($array_y_figures)/count($array_y_figures)),4);

        $x_x_y_y = 0;
        $x_avg_x_2 = 0;

        $n = count($array_x_sold_quantity);

        for ($i = 0; $i < count($array_x_sold_quantity); $i++) {
            $x_x_y_y += ((($array_x_sold_quantity[$i] - $avg_x) * ($array_y_figures[$i] - $avg_y)));
            $x_avg_x_2 += (($array_x_sold_quantity[$i] - $avg_x) * ($array_x_sold_quantity[$i] - $avg_x));
        }

        $b1 = ($x_x_y_y/$x_avg_x_2);
        $b1 = round($b1,6);

        $sum_y = 0;
        for ($t = 0; $t < count($array_y_figures); $t++) {
            $sum_y += (($array_y_figures[$t]) - ($b1 * $array_x_sold_quantity[$t]));
        }
        $sum_y = round(($sum_y/count($array_y_figures)),6);

        $s_x_x = 0;
        for ($z = 0; $z < count($array_x_sold_quantity); $z++) {
            $s_x_x += (($array_x_sold_quantity[$z] - $avg_x) * ($array_x_sold_quantity[$z] - $avg_x));
        }
        $s_x_x = round($s_x_x,6);

        $s_y_y = 0;
        for ($u = 0; $u < count($array_x_sold_quantity); $u++) {
            $s_y_y += (($array_y_figures[$u] - $avg_y) * ($array_y_figures[$u] - $avg_y));
        }

        $s_y_y = round($s_y_y,6);
        //$s_x_y = round($x_x_y_y,6);
        $s = round((sqrt((($s_y_y - $b1 * $x_x_y_y)/(count($array_x_sold_quantity) - 2)))), 6);
        $z_index = ($n-2);
        $x0 = $x_0;

        if ($z_index > 1000) {
            $z_index = 1001;
        }

        $t_score_query = "SELECT Six FROM tdist WHERE DF='$z_index'";
        $t_soore_fetch = $connection->query($t_score_query)->fetch_assoc();
        $t = $t_soore_fetch["Six"];
        $y_o = $sum_y + $b1*$x0;

        $results[] = $low = round(($y_o - $t*$s*sqrt((1/$n) + ((($x0 - $avg_x)*($x0 - $avg_x))/$s_x_x))), 6);
        $results[] = $high = round(($y_o + $t*$s*sqrt((1/$n) + ((($x0 - $avg_x)*($x0 - $avg_x))/$s_x_x))), 6);
        $results[] = $avg = ($low + $high) / 2;

        return $results;
    }

    if ($day_name == "") {
        $errMsg .= "Please complete the inputs.";
    }

    if ($errMsg != "") {
        echo "<p>$errMsg</p>";
    } else {
        echo "<p>"."Forecast results for next ".$day_name."</p>";
        echo "<p>"."Warning: all are estimated values based on previous data."."</p>";
        $result_sum_days = array();
        $grand_results = array();
        $deviation_of_mean_results = array();

        $option = array($result_listing, $result_day_listing);
        $option_2 = array($result_count_day, $result_count_single_day);


        $item_id = array();
        $item_sold = array();
        $item_avai = array();

        while ($row = mysqli_fetch_assoc($item_summary_results)) {
            $item_id[] = $row["itemID"];
            $item_sold[] = $row["sold"];
            $item_avai[] = $row["available"];
        }

        $percent_sold = array();
        for ($i = 0; $i < count($item_id); $i++) {
            $percent_sold[] = round((($item_sold[$i] * 100) / array_sum($item_sold)), 2);
        }

        $red_alert = array();
        for ($i = 0; $i < count($percent_sold); $i++) {
            if (($percent_sold[$i] >= 1) && ($item_avai[$i] < 10)) {
                $red_alert[] = $item_id[$i];
            }
        }

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
            $low_mean_dates_results[] = round(($observed_dates_results[0][$u] - $observed_dates_results[1][$u]), 4);
            $high_mean_dates_results[] = round(($observed_dates_results[0][$u] + $observed_dates_results[1][$u]), 4);
            $avg_mean_dates_results[] = round(($observed_dates_results[0][$u]), 4);
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
            //$low[] = round(($low_mean_days_results[$i]), 0);
            $high[] = round((($high_mean_dates_results[$i] + $high_mean_days_results[$i]) / 2), 0);
            //$high[] = round(($high_mean_days_results[$i]), 0);
            $mean[] = round((($avg_mean_dates_results[$i] + $avg_mean_days_results[$i]) /2), 0);
            //$mean[] = round(($avg_mean_days_results[$i]), 0);
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

        /*print_r($low);
        echo "<br/>";
        print_r($high);*/

        $predict_results = array();
        for ($i = 0; $i < count($percent_low_more); $i++) {
            if ($percent_low_more[$i] <= $percent_high_more[$i]) {
                $predict_results[] = $high[$i];
            } else {
                $predict_results[] = $low[$i];
            }
        }

        $option_3 = array($linear_observed_dates_profit_results, $linear_observed_dates_revenue_results);
        $dates_linear = array();
        for ($i = 0; $i < count($option_3); $i++) {
            $dates_linear[] = Linear_Regression($option_3[$i], $predict_results[2]);
        }

        $option_4 = array($linear_observed_days_profit_results, $linear_observed_days_revenue_results);
        $days_linear = array();
        for ($i = 0; $i < count($option_4); $i++) {
            $days_linear[] = Linear_Regression($option_4[$i], $predict_results[2]);
        }

        $avg_dates_profit_linear = (($dates_linear[0][1] + $dates_linear[0][2]) / 2);
        $avg_days_profit_linear = (($days_linear[0][1] + $days_linear[0][2]) / 2);
        $final_predicted_profit = round((($avg_dates_profit_linear + $avg_days_profit_linear + $predict_results[3]) / 3), 0);

        $avg_dates_revenue_linear = (($dates_linear[1][1] + $dates_linear[1][2]) / 2);
        $avg_days_revenue_linear = (($days_linear[1][1] + $days_linear[1][2]) / 2);
        $final_predicted_revenue = round((($avg_dates_revenue_linear + $avg_days_revenue_linear + $predict_results[4]) / 3), 0);

        echo "<h4>Predicted future sales</h4>";
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
        echo "<td>", $final_predicted_profit, "</td>";
        echo "<td>", $final_predicted_revenue, "</td>";
        echo "</tr>";
        echo "</table>";

        echo "<h4>Items should be concerned</h4>";
        echo "<table border='1px'>";
        echo "<tr>"
            . "<th scope=\"col\">itemID</th>"
            . "</tr>";
        for($i = 0; $i < count($red_alert); $i++) {
            echo "<tr>";
            echo "<td>", $red_alert[$i], "</td>";
            echo "</tr>";
        }
        echo "</table><br/>";

        $cat = array();
        for ($i = 0; $i < count($red_alert); $i++) {
            $cat_query = "SELECT categoryID FROM item WHERE itemID='$red_alert[$i]'";
            $cat_fetch = $connection->query($cat_query)->fetch_assoc();
            $cat[] = $cat_fetch["categoryID"];
        }
        $result_cat = array_unique($cat);
        echo "<h4>Categories should be concerned</h4>";
        echo "<table border='1px'>";
        echo "<tr>"
            . "<th scope=\"col\">CategoryID</th>"
            . "</tr>";
        for($i = 0; $i < count($result_cat); $i++) {
            echo "<tr>";
            echo "<td>", $result_cat[$i], "</td>";
            echo "</tr>";
        }
        echo "</table><br/>";
    }
    mysqli_close($connection);
}