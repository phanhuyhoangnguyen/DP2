/**
 * Created by vietnguyenswin on 9/17/17.
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
function display_refund_report()
{
    if(xHRObject)
    {
        var url = "php/display_refund_report.php";

        var month = document.getElementById("select_month").value;
        var year = document.getElementById("select_year").value;
        var option = document.getElementById("display_option").value;

        if (month == "" || year == "" || option=="")
            alert("Please select month, year and view");

        var vars = "&display_option=" + encodeURIComponent(option) + "&select_month="+encodeURIComponent(month)+"&select_year="+encodeURIComponent(year);
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