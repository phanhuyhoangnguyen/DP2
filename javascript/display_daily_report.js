/**
 * Created by phanNguyen on 20/09/17.
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
function display_daily_report()
{
    if(xHRObject)
    {
        var url = "php/display_daily_report.php";
        /*receive value from html*/
        var time = document.getElementById("select_time").value;

        var time_splitter;
        var divider = "-";
        var vars;

        if(time.match(/-/))
                time_splitter = time.split("-");
        else
                time_splitter = time.split("/");


        if (time_splitter.length != 3) {
            //alert user to input correct format
            alert("You must input time, in the following format: dd/mm/yyyy");
            vars = "&option="+encodeURIComponent(option)+"&select_date="+encodeURIComponent("")+"&select_month="+encodeURIComponent("")+"&select_year="+encodeURIComponent("");
        }
        else {
            /*extract value and assign them into variable*/

            date = time_splitter[0];
            month = time_splitter[1];
            year = time_splitter[2];
            vars = "&option=" + encodeURIComponent(option) + "&select_date=" + encodeURIComponent(date) + "&select_month=" + encodeURIComponent(month) + "&select_year=" + encodeURIComponent(year);
        }

        /*if the above variable is not null*/
        //if (date != null && month != null && year != null) {
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
        //}
    }
    return false;
}