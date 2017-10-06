/**
 * Created by DonDave on 04/10/17.
 */
var xHRObject = false;
if (window.XMLHttpRequest)
{
    xHRObject = new XMLHttpRequest();
}
else if (window.ActiveXObject)
{
    xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
}
function generate_chart()
{
    if(xHRObject)
    {
        var url = "php/chart_function.php";
        var option = document.getElementById("select_type").value;
        var time = document.getElementById("select_time").value;

        var time_splitter;

        //Handle different format of date and extract to get date, month, year
        if(time.match(/-/))
            time_splitter = time.split("-");
        else
            time_splitter = time.split("/");

        /*extract value and assign them into variable*/
        var date;
        var month;
        var year;
        var vars;

        //if the view is day
        if (option == "daily_type") {
            //Check if the length of date is sufficient
            if (time_splitter.length != 3) {
                //alert user to input correct format
                alert("You must input time, in the following format: dd/mm/yyyy");
                vars = "&option="+encodeURIComponent(option)+"&select_date="+encodeURIComponent("")+"&select_month="+encodeURIComponent("")+"&select_year="+encodeURIComponent("");
            }
            else {
                date = time_splitter[0];
                month = time_splitter[1];
                year = time_splitter[2];
                vars = "&option=" + encodeURIComponent(option) + "&select_date=" + encodeURIComponent(date) + "&select_month=" + encodeURIComponent(month) + "&select_year=" + encodeURIComponent(year);
            }
        }

        else if (option == "monthly_type") {
            if (time_splitter.length != 2) {
                alert("You must input time, in the following format: mm/yyyy");
                vars = "&option=" + encodeURIComponent(option) + "&select_month=" + encodeURIComponent("") + "&select_year=" + encodeURIComponent("");
            }
            else {
                month = time_splitter[0];
                year = time_splitter[1];
                vars = "&option=" + encodeURIComponent(option) + "&select_month=" + encodeURIComponent(month) + "&select_year=" + encodeURIComponent(year);
            }
        }

        else {
            vars = "&option="+encodeURIComponent("")+"&select_date="+encodeURIComponent("")+"&select_month="+encodeURIComponent("")+"&select_year="+encodeURIComponent("");

        }
        //Send variables value to php file
        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                return document.getElementById("echo_result").innerHTML = this.responseText;
            }
        }

        time_splitter = null;
    }
    return false;
}