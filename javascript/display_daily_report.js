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

        var time = document.getElementById("select_time").value;

        var time_splitter = time.split("/");

        var date = time_splitter[0];
        var month = time_splitter[1];
        var year = time_splitter[2];

        var vars = "&select_date="+encodeURIComponent(date)+"&select_month="+encodeURIComponent(month)+"&select_year="+encodeURIComponent(year);
        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                return document.getElementById("echo_daily_report").innerHTML = this.responseText;
            }
        }
    }
    return false;
}