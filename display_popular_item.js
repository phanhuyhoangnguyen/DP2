/**
 * Created by phanNguyen on 04/09/17.
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
function display_popular_item()
{
    if(xHRObject)
    {
        var url = "php/display_popular_item.php";
        var option = document.getElementById("select_view").value;
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
        if (option == "day_view") {
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

        else if (option == "month_view") {
            if (time_splitter.length != 2) {
                alert("You must input time, in the following format: mm/yyyy");
                vars = "&option=" + encodeURIComponent(option) + "&select_date=" + encodeURIComponent("") + "&select_month=" + encodeURIComponent("") + "&select_year=" + encodeURIComponent("");
            }
            else {
                month = time_splitter[0];
                year = time_splitter[1];
                vars = "&option=" + encodeURIComponent(option) + "&select_date=" + encodeURIComponent("") + "&select_month=" + encodeURIComponent("") + "&select_year=" + encodeURIComponent("");
            }
        }


        else if (option == "year_view") {

            if (time_splitter.length != 1) {
                alert("You must input time, in the following format: yyyy");
                vars = "&option=" + encodeURIComponent(option) + "&select_date=" + encodeURIComponent("") + "&select_month=" + encodeURIComponent("") + "&select_year=" + encodeURIComponent("");
            }
            else{
                year = time_splitter[0];
                vars = "&option="+encodeURIComponent(option)+ "&select_year=" + encodeURIComponent(year);
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
    }
    return false;
}